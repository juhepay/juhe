<?php

namespace App\Http\Pay;

use App\Common\Gateway;
use App\Model\Apistyle;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\PayLog;
use App\Model\Upaccount;
use App\Model\User;
use App\Model\UserRate;
use App\Requests\PayRequest;
use App\Services\OrdersService;
use App\Tool\Md5Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class DefaultController extends Controller
{
    public function index(PayRequest $request)
    {
        $user = User::where(['uid'=>$request->appid,'status'=>1,'is_jd'=>1])->first();
        if(!$user) return json_encode(['code'=>0,'msg'=>'商户不存在或未启用'],256);
        $data = array(
            'uid'       => $request->appid,
            'order_no'  => $request->order_no,
            'content'   => json_encode($request->all()),
            'amount'    => $request->amount,
            'pay_code'  => $request->pay_code,
            'ip'        => $request->getClientIp()
        );

        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($request->all());
        $sign = $md5Verify->md5Encrypt($prestr,$user->api_key);
        if($sign != $request->sign)
        {
            $data['result'] = '验签失败-系统签名串:'.$prestr.', 签名:'.$sign;
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'验签失败'],256);
        }
        //获取接口类型
        $apistyle = Apistyle::where(['api_mark'=>$request->pay_code,'status'=>1])->first();
        if(!$apistyle)
        {
            $data['result'] = '接口类型未启用';
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'接口未启用'],256);
        }
        //获取用户费率配置
        $user_rate = UserRate::where(['uid'=>$request->appid,'apistyle_id'=>$apistyle->id,'status'=>1])->first();
        if(!$user_rate)
        {
            $data['result'] = '商户接口类型未启用';
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'商户接口类型未启用'],256);
        }
        //商户未指定接口账户
        if($user_rate->upaccount_id == 0)
        {
            //开启轮询
            if($apistyle->is_polling == 1)
            {
                if(!$apistyle->polling_ids){
                    $data['result'] = '接口轮询未配置';
                    PayLog::create($data);
                    return json_encode(['code'=>0,'msg'=>'接口轮询未配置'],256);
                }
                $arr = get_rand($apistyle->polling_ids);
                $apizj = Apizj::find($arr['id']);//配置了轮询的接口账户，只是关闭接口账户没用，必须关闭轮询的配置
            }else{
                //接口账户应用的接口类型
                $apizj = Apizj::where(['apistyle_id'=>$apistyle->id,'ifchoose'=>1,'status'=>1])->first();
            }
            $upaccount_id = $apizj->upaccount_id; //上游接口账户id
        }else{
            $upaccount_id = $user_rate->upaccount_id;//指定接口账户id
            $apizj = Apizj::where(['apistyle_id'=>$apistyle->id,'upaccount_id'=>$upaccount_id,'status'=>1])->first();
        }
        if(!$apizj)
        {
            $data['result'] = '接口账户未配置';
            PayLog::create($data);
            return json_encode(['code'=>1,'msg'=>'接口账户未配置'],256);
        }
        //验证单笔限额
        if( ($apizj->minje && $apizj->minje > $request->amount) || ($apizj->maxje && $apizj->maxje < $request->amount) )
        {
            $data['result'] = '单笔限额:'.$apizj->minje.'--'.$apizj->maxje;
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'单笔限额:'.$apizj->minje.'--'.$apizj->maxje],256);
        }
        // 单日限额
        if($apizj->todayje && ( bcadd($request->amount,$apizj->usedje,2) > $apizj->todayje ) )
        {
            $data['result'] = '接口账户今日额度已满';
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'今日额度已满'],256);
        }
        $upaccount = Upaccount::find($upaccount_id);
        if(!$upaccount) {
            $data['result'] = '接口账户不存在';
            PayLog::create($data);
            return json_encode(['code'=>0,'msg'=>'接口账户不存在'],256);
        }

        $order_no = $data['uid'].$data['order_no'];
        $params = array(
            'style'     => $upaccount->upaccount_mark,
            'config'    => $upaccount->upaccount_params,
            'order_no'  => $order_no,
            'amount'    => $request->amount,
            'notify_url'=> $request->notify_url,
            'return_url'=> $request->return_url,
            'pay_code'  => $request->pay_code
        );

        $tmp['order_data'] = $params;
        $tmp['style']      = $upaccount->upaccount_mark;
        $tmp['pay_log']    = $data;
        $tmp['user']       = array('uid'=>$user->uid,'pid'=>$user->pid);
        $tmp['user_rate']  = $user_rate->toarray();
        $tmp['apizj']      = $apizj->toarray();

        //客户端验证
        $hash = md5($order_no);
        $text = json_encode($tmp);
        Redis::set($hash, $text, "ex", 60, "nx");
        return json_encode(['code'=>1,'pay_url'=>route('pay.go').'?hash='.$hash],256);
    }

    public function go(Request $request)
    {
        $hash = $request->hash;
        return  view('Pay.go',compact('hash'));
    }

    public function client(Request $request)
    {
        $hash   = $request->hash;
        // 获取订单数据
        $params = Redis::get($hash);
        if (!$params) return json_encode(['code'=>0,'msg'=>'订单已超时,请重新下单'],256);
        $params = json_decode($params,true);

        if(isset($params['pay_url'])) return ajaxReturnUrl(1,'下单成功',$params['pay_url']);

        $client = $params['user']['uid'].$request->client;
        $sort = Redis::zRank('blacklist',$client);
        if($sort !== false) return json_encode(['code'=>0,'msg'=>'无权下单，请联系管理员'],256);

        $app = $params['style'];
        $ret = Gateway::$app()->pay($params['order_data']);

        $data = $params['pay_log'];
        $data['result'] = $ret[1];
        PayLog::create($data);
        if($ret[0] == 0)
        {
            return json_encode(['code'=>0,'msg'=>$ret[1]],256);
        }
        $text = json_encode(array('pay_url'=>$ret[1]));
        Redis::set($hash, $text, "ex", 60, "");
        $params['order_data']['client_sign'] = $client;
        // 计算订单数据
        $orders = app(OrdersService::class);
        $orders->add($params['order_data'],$params['user'],$params['user_rate'],$params['apizj']);

        $back_data = array(
            'appid'     => $params['user']['uid'],
            'order_no'  => $params['order_data']['order_no'],
            'amount'    => $params['order_data']['amount'],
            'back_url'  => $params['order_data']['return_url'],
            'time'      => time(),
        );
        cache(['back'.$back_data['order_no'] => json_encode($back_data)], now()->addMinutes(5));
        return ajaxReturnUrl(1,'下单成功',$ret[1]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function notify(Request $request)
    {
        $app = $request->style;
        $result = Gateway::$app()->notify($request);
        die($result);
    }

    public function backurl(Request $request)
    {
        if(isset($request->orderno) && $request->orderno)
        {
            if($request->orderno == 'webBackurl') return response()->json(['code'=>1,'msg'=>'测试支付成功'],200,[],256);;;
            $tmp = cache('back'.$request->orderno);
            if(!$tmp) return response()->json(['code'=>0,'msg'=>'订单已失效'],200,[],256);;
            $arr = json_decode($tmp,true);
            $user = User::where('uid',$arr['appid'])->first();
            if(!$user) return response()->json(['code'=>0,'msg'=>'订单已失效'],200,[],256);;;
            $md5Verify = app(Md5Verify::class);
            $back_data = array(
                'appid'     => $arr['appid'],
                'order_no'  => substr_replace($arr['order_no'],"",strpos($arr['order_no'],(string)$arr['appid']),strlen($arr['appid'])) ,
                'amount'    => $arr['amount'],
                'time'      => time(),
            );
            $prestr = $md5Verify->getSign($back_data, $user->api_key);
            $back_data['sign'] = $md5Verify->md5Encrypt($prestr,$user->api_key);
            header("Location:".$arr['back_url'].'?'.http_build_query($back_data));
        }
    }

    public function query(Request $request)
    {
        if( !isset($request->appid) || !isset($request->sign) || !isset($request->order_no) )
        {
            return json_encode(['code'=>0, 'msg'=>'缺少参数'],256);
        }

        $user = User::where(['uid'=>$request->appid])->select('api_key')->first();
        if(!$user) return json_encode(['code'=>0, 'msg'=>'商户不存在'],256);

        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($request->all());
        $sign = $md5Verify->md5Encrypt($prestr,$user->api_key);
        if($sign != $request->sign)
        {
            return json_encode(['code'=>0, 'msg'=>'验签失败'],256);
        }
        $order = Order::where(['order_no'=>$request->appid.$request->order_no,'uid'=>$request->appid])->select('status','amount','order_no')->first();
        if(!$order) return json_encode(['code'=>0, 'msg'=>'订单不存在'],256);
        $data = [
            'code'     => 1,
            'msg'      => '查询成功',
            'order_no' => $request->order_no,
            'status'   => $order->status,
            'amount'   => $order->amount,
            'time'     => $order->paytime
        ];

        return json_encode($data,256);
    }

    /**
     * 表单上游发起支付
     * @param Request $request
     * @return false|string
     */
    public function formPost(Request $request)
    {
        $jstr = Redis::get($request->hash);
        if(!$jstr) return json_encode(['code'=>0,'msg'=>'订单已过期'],256);
        $data = json_decode($jstr,true);
        if(empty($data['url'])) return json_encode(['code'=>0,'msg'=>'没有网关地址'],256);
        $str = '<form id="Form1" name="Form1" method="post" action="' . $data['url'] . '" >';
        unset($data['url']);
        foreach ($data as $key => $val) {
            $str .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $str .= '</form>';
        $str .= '<script>';
        $str .= 'document.Form1.submit();';
        $str .= '</script>';
        return $str;
    }

    public function scan(Request $request)
    {
        if(isset($request->ddh) && $request->ddh )
        {
            $str = cache($request->ddh);
            if(!$str) return json_encode(['code'=>0, 'msg'=>'订单已过期'],256);
            $data = json_decode($str,true);
            return view('Pay.scan',compact('data'));
        }else{
            return json_encode(['code'=>0, 'msg'=>'缺少参数'],256);
        }
    }

    public function cacheImg(Request $request)
    {
        if(isset($request->ddh) && $request->ddh )
        {
            $str = cache('scan'.$request->ddh);
            if(!$str) return '';
            echo $str;
        }else{
            return '';
        }
    }

    public function getddhstatus(Request $request)
    {
        if(isset($request->ddh) && $request->ddh )
        {
            $str = cache($request->ddh);
            if(!$str) array('status'=>0);
            $arr = json_decode($str,true);
            return array('status'=>$arr['status']);
        }else{
            return array('status'=>0);
        }
    }
}
