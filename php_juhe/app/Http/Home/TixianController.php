<?php

namespace App\Http\Home;

use App\Exceptions\CustomServiceException;
use App\Model\BankCard;
use App\Model\Sysconfig;
use App\Model\Syslog;
use App\Model\Tixian;
use App\Model\User;
use App\Model\FundsTurnoverLog;
use App\Requests\TixianRequest;
use App\Services\ManyErrorLockService;
use App\Tool\GoogleAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TixianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $request->query();
        $model = Tixian::query();
        $where['uid'] = $user->uid;
        if(isset($request->order_no))
        {
            $where['order_no'] = $request->order_no;
        }

        if(isset($request->status))
        {
            $where['status'] = $request->status;
        }

        if( (isset($request->start_time) && $request->start_time) &&  (isset($request->end_time) && $request->end_time) )
        {
            $model->whereBetween('created_at',[$request->start_time,$request->end_time]);
        }
        $list = $model->where($where)->orderBy('id','desc')->paginate(30);

        $time0 = date('Y-m-d 00:00:00', strtotime("-1 day"));
        $time1 = date('Y-m-d 00:00:00', time());
        $time2 = date('Y-m-d 00:00:00', strtotime("+1 day"));

        $data['today'] = Tixian::where(['uid'=>$user->uid,'status'=>1])->whereBetween('created_at',[$time1,$time2])->sum('money');
        $data['yesterday'] = Tixian::where(['uid'=>$user->uid,'status'=>1])->whereBetween('created_at',[$time0,$time1])->sum('money');
        $data['count'] = Tixian::where(['uid'=>$user->uid,'status'=>1])->sum('money');
        return view('Home.Tixian.index',compact('list','query','data'));
    }

    public function create()
    {
        $bankcard = BankCard::where('uid',Auth::user()->uid)->get();
        $sysconfig = Sysconfig::first();
        return view('Home.Tixian.create',compact('bankcard','sysconfig'));
    }

    public function store(TixianRequest $request)
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
        if(!$googleAuthenticator->verifyCode($user->google_key,$request->auth_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }

        if(!password_verify($request->save_code, $user->save_code)){
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'提现密码错误');
        }
        $manyErrorLockService->clearLoginAttempts($request);

        if( $request->money > $user->balance) return ajaxReturn(0,'余额不足');
        $sysconfig = Sysconfig::first();

        if($sysconfig->min_price && $sysconfig->min_price > $request->money) return ajaxReturn(0,'最小提现金额：'.$sysconfig->min_price);

        // 手续费计算
        if($sysconfig->fl_type == 0)// 单笔
        {
            $fee = $sysconfig->tx_fl;
        }else{ // 比例手续费
            $fee =  $sysconfig->tx_fl ? bcmul($sysconfig->tx_fl/100, $request->money,2) : 0;
        }

        $bankcard = BankCard::where(['id'=>$request->bankcard_id,'uid'=>$user->uid])->first();
        if(empty($bankcard)) return ajaxReturn(0,'银行卡不存在');

        $params['real_name']= $bankcard->real_name;
        $params['card_no']  = $bankcard->card_no;
        $params['bank_name']= $bankcard->bank_name;
        $params['uid']      = $user->uid;
        $params['fee']      = $fee;
        $params['money']    = $request->money;
        $params['order_no'] = 'TX'.time().rand(00000,99999);

        DB::connection('mysql')->transaction(function () use ($params) {
            $token = TimeMicroTime();
            redisLock("user:lock:".$params['uid'],$token,10);
            //查询用户原金额
            $user_info = User::where('uid',$params['uid'])->select('balance')->first();
            //账户余额扣除
            $result = User::where('uid',$params['uid'])->where('balance','>=',$params['money'])->decrement('balance',$params['money']);
            unlock("user:lock:".$params['uid'],$token);
            //添加提现信息
            $result && $result = Tixian::create($params);
            if($result)
            {
                // 资金流水
                $data = array(
                    'uid'           => $params['uid'],
                    'amount'        => -$params['money'],
                    'before_balance'=> $user_info->balance,
                    'after_balance' => bcsub($user_info->balance,$params['money'],2),
                    'type'          => 2,
                    'content'       => '申请提现【'.$params['money'].'元】 手续费：'.$params['fee'].'元',
                );
                $result = FundsTurnoverLog::create($data);
            }

            if (!$result) {
                throw new CustomServiceException('提现申请失败');
            }
        }, 1);


        $logs_data = array(
            'module'    => '申请提现',
            'content'   => '提现银行卡【'.$bankcard->card_no.'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('member.tixian.index'));
    }
}
