<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class ThepayApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        // if($request['pay_mode'] == 'alipay_onetime')
        // {
        //     $type = '201';
        // }elseif($request['pay_mode'] == 'alipay_h5'){
        //     $type = '206';
        // }else{
        //     $type = '203';
        // }

        $data = array(
            'memberid'  => $this->configs['appid'],
            'pay_type'  => 206,
            'trade_no'  => $request['order_no'],
            'amount'    => sprintf("%.2f",$request['amount']),
            'notify_url'=> route('pay.notify',[$request['style']]),
            'backurl'   => route('pay.backurl',[$request['order_no']]),
            'json'      => '1'
        );

        $data['sign'] = md5("memberid={$data['memberid']}&pay_type={$data['pay_type']}&trade_no={$data['trade_no']}&amount={$data['amount']}&notify_url={$data['notify_url']}{$this->configs['key']}");
        $tmp = CURL($this->configs['gateway'], $data);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if($ret['code'] != 200)
        {
            return [0, $ret['msg']];
        }

        return [1, $ret['data']['qrcode']];
    }

    public function notify($request)
    {
        $ret = $request;
        $ddh   = $ret['trade_no']; // 订单号
        $price = $ret['pay_amount']; //返回的订单金额


        //根据订单号查找对应支付账户
        $ddBuffer = Order::where(['order_no'=>$ddh,'status'=>0])->first();
        if (!$ddBuffer) die('订单不存在');
        //金额验证
        if(bccomp($price,$ddBuffer->amount,2) != 0 ) die('订单金额错误');

        //验证通道是否关闭
        $exists = Apizj::where(['apistyle_id'=>$ddBuffer->apistyle_id,'upaccount_id'=>$ddBuffer->upaccount_id,'status'=>1])->exists();
        if (!$exists) die('通道已关闭');
        //获取支付账号
        $pzBuffer = Upaccount::find($ddBuffer->upaccount_id);
        //加载配置
        $this->loadConfigs($pzBuffer->upaccount_params);
        //签名校验
        $sign = md5("trade_no={$ret['trade_no']}&pay_type={$ret['pay_type']}&pay_amount={$ret['pay_amount']}&receipt_amount={$ret['receipt_amount']}&paydate={$ret['paydate']}{$this->configs['key']}");
        if ($ret['sign'] != $sign) {
            die('签名错误');
        }
        //支付成功后
        $this->changeDingdan($ddBuffer);

        die('success');
    }


    public function repay($request)
    {
        $this->loadConfigs($request['params']);
        $pay = $request['paybuffer'];
        $data = [
            'ddh' => $request['ddh'],
            'money' => $pay['money'],
            'outddh' => '',
            'msg' => '后台下款',
            'status' => 1,
            'ifnotify' => $request['ifnotify'],
        ];
        $result = $this->changestatus($data);
        return $result;
    }

    public function repaynotify($request) {
        die('success');
    }

    public function repayselect($request)
    {
        $this->loadConfigs($request['peizhi']);
        return [1, '手动下款'];
    }

}
