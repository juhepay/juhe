<?php

namespace App\Http\Home;

use App\Model\Apistyle;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Syslog;
use App\Model\Tixian;
use App\Model\UserRate;
use App\Requests\PassRequest;
use App\Services\ManyErrorLockService;
use App\Tool\GoogleAuthenticator;
use App\Tool\Md5Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $money = Tixian::where(['uid'=>$user->uid,'status'=>1])->sum('money');
        $times = array(
            date('Y-m-d 00:00:00',time()),
            date('Y-m-d 00:00:00',strtotime("+1 day"))
        );
        $today_money = Tixian::where(['uid'=>$user->uid,'status'=>1])->whereBetween('created_at',$times)->sum('money');
        $order_amount = Order::where(['uid'=>$user->uid,'status'=>1])->whereBetween('created_at',$times)->sum('amount');
        return view('Home.Index.index',compact('user','money','today_money','order_amount'));
    }

    /**
     * 我的资料
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info()
    {
        $user   = Auth::user();
        $google = app(GoogleAuthenticator::class);
        $secret = $google->createSecret();
        $name   = $user->username.'@'.$_SERVER['HTTP_HOST'];
        $qrCodeUrl  = $google->getQRCodeGoogleUrl($name, $secret);
        return view('Home.Index.info',compact('user','secret','qrCodeUrl'));
    }

    /**
     * 修改个人资料
     * @param PassRequest $request
     * @return false|string
     */
    public function pass(PassRequest $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        if($request->style == 1)
        {
            if(!Hash::check($request->passwordy, $user->password)){
                return ajaxReturn(0,'原密码错误');
            }
            $user->password = bcrypt($request->password);
        }elseif($request->style == 2){
            if(!Hash::check($request->passwordy, $user->save_code)){
                return ajaxReturn(0,'原密码错误');
            }
            $user->save_code = bcrypt($request->password);
        }elseif($request->style == 3){
            $google = app(GoogleAuthenticator::class);
            $result = $google->verifyCode($request->secret,$request->google_code,2);
            if(!$result) return ajaxReturn(0,'验证码错误，请重新添加');
            $user->google_key = $request->secret;
        }else{
            return ajaxReturn(0,'参数错误');
        }
        $user->save();

        $logs_data = array(
            'module'    => '我的资料',
            'content'   => '修改资料【'.$request->style.'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);

        return ajaxReturnUrl(1,'操作成功',route('member.info'));
    }

    /**
     * 费率详情
     */
    public function rate()
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');
        //获取启用的上游接口类型
        $apistyle = Apistyle::where('status',1)->get();
        $apistyle = $apistyle->keyBy('id')->toArray();
        //获取轮训账户配置id
        foreach ($apistyle as $k=>$v)
        {
            if($v['is_polling'] == 1)
            {
                $apistyle[$k]['polling_id'] = $v['polling_ids'] ?? 0;
            }else{
                $apistyle[$k]['polling_id'] = 0;
            }
        }

        $apizj_apistyle_id = [];
        //获取会员开启的接口类型
        $list  = UserRate::where('uid',$user->uid)->get();
        //获取上游接口账户详情
        $apizj = Apizj::get();
        foreach ($apizj as $v)
        {
            if($v->ifchoose == 1) {
                $apizj_apistyle_id[$v->apistyle_id] = $v->runfl;
            }
        }
        $apizj_account_id = $apizj->keyBy('upaccount_id')->toArray();
        return view('Home.Index.rate',compact('apistyle','list','apizj_account_id','apizj_apistyle_id'));
    }

    public function recharge()
    {
        $user = Auth::user();
        $apistyle = Apistyle::where('status',1)->get();
        return view('Home.Index.recharge',compact('user','apistyle'));
    }

    public function rechargeStore(Request $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $data = [
            'appid'        => $user->uid,
            'order_no'     => TimeMicroTime().rand(00,99),
            'amount'       => $request->money,
            'pay_code'     => $request->pay_code,
            'notify_url'   => route('pay.notify',['webNotify']),
            'return_url'   => route('pay.backurl',['webBackurl']),
        ];

        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($data, $user->api_key);
        $data['sign'] = $md5Verify->md5Encrypt($prestr,$user->api_key);
        $tmp = CURL(route('pay.index'),$data);
        $ret = json_decode($tmp,true);
        if(!is_array($ret)) return ajaxReturn(0,$tmp);
        if($ret['code'] == 0)
        {
            return ajaxReturn(0,$ret['msg']);
        }
        return ajaxReturnUrl(1,'下单成功',$ret['pay_url']);
    }

    public function getUserKey(Request $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $request->offsetSet('username',$user->username);
        $manyErrorLockService = app(ManyErrorLockService::class);
        if( $manyErrorLockService->hasTooManyActionAttempts($request) )
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'错误次数过多,请稍后再来');
        }

        $googleAuthenticator = app(GoogleAuthenticator::class);
        if(!$googleAuthenticator->verifyCode($user->google_key,$request->google_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }

        $manyErrorLockService->clearLoginAttempts($request);

        return json_encode(array('code'=>1,'key'=>$user->api_key,'msg'=>'获取成功'));
    }

    public function resetUserKey(Request $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $request->offsetSet('username',$user->username);
        $manyErrorLockService = app(ManyErrorLockService::class);
        if( $manyErrorLockService->hasTooManyActionAttempts($request) )
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'错误次数过多,请稍后再来');
        }

        $googleAuthenticator = app(GoogleAuthenticator::class);
        if(!$googleAuthenticator->verifyCode($user->google_key,$request->google_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }

        $manyErrorLockService->clearLoginAttempts($request);
        $user->api_key = md5(TimeMicroTime().rand(0000,9999));
        $user->save();
        return ajaxReturn(1,'重置成功');
    }
}
