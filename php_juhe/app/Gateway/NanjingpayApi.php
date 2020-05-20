<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class NanjingpayApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
    ];

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $query = [];
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != null && $key != 'sign') {
                $query[] = $key . "=" . $val;
            }
        }
        $str = implode('&', $query).'&key='.$this->configs['key'];
        return strtoupper(md5($str));
    }

    public function pay($request)
    {
        $this->loadConfigs($request['config']);

        //提交数据
        $data = [
            'pay_memberid'      => $this->configs['appid'],
            'pay_applydate'     => date('Y-m-d H:i:s'),
            'pay_orderid'       => $request['order_no'],
            'pay_bankcode'      => 903,
            'pay_notifyurl'     => route('pay.notify', [$request['style']]),
            'pay_callbackurl'   => route('pay.backurl',[$request['order_no']]),
            'pay_amount'        => $request['amount'],
        ];
        $data['pay_md5sign'] = $this->sign($data);
        $data['pay_productname'] = 'shop';
        $data['pay_return_type'] = 'json';

        $tmp = CURL($this->configs['gateway'], $data);

        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if($ret['status'] != 'ok')
        {
            return [0, $ret['msg']];
        }

        return [1, $ret['data']['qrcode_url']];
    }

    public function notify($request)
    {
        $ret = $request->all();

        $ddh   = $ret['orderid']; // 订单号
        $price = $ret['amount']; //返回的订单金额

        //根据订单号查找对应支付账户
        $ddBuffer = Order::where(['order_no' => $ddh, 'status' => 0])->first();
        if (!$ddBuffer) die('订单不存在');
        //金额验证
        if (bccomp($price, $ddBuffer->amount, 2) != 0) die('订单金额错误');

        //验证通道是否关闭
        $exists = Apizj::where(['apistyle_id' => $ddBuffer->apistyle_id, 'upaccount_id' => $ddBuffer->upaccount_id, 'status' => 1])->exists();
        if (!$exists) die('通道已关闭');
        //获取支付账号
        $pzBuffer = Upaccount::find($ddBuffer->upaccount_id);
        //加载配置
        $this->loadConfigs($pzBuffer->upaccount_params);
        //签名校验
        $sign = $this->notifySign($ret);
        if ($ret['sign'] != $sign) {
            die('签名错误');
        }
        //支付成功后
        if($ret['returncode'] == '00') {
            $this->changeDingdan($ddBuffer);
        }
        die('OK');
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
