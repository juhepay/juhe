<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class ZhipayApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
    ];

    protected function notifySign($arr)
    {
        ksort($arr, SORT_STRING);
        $query = [];
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != null && $key != 'sign' && $key != 'attach' && $key != 'asynchronous_url') {
                $query[] = $key . "=" . $val;
            }
        }
        $str = implode('&', $query) . '&key=' . $this->configs['key'];
        return strtoupper(md5($str));
    }

	protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $query = [];
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != null && $key != 'sign' && $key != 'attach') {
                $query[] = $key . "=" . $val;
            }
        }
        $str = implode('&', $query) . '&key=' . $this->configs['key'];
        return strtoupper(md5($str));
    }

    public function pay($request)
    {
        $this->loadConfigs($request['config']);

        if($request['pay_code'] == 'alipay_scan')
        {
            $type = 175409;
        }elseif ($request['pay_code'] == 'wechat_scan'){
            $type = 219410;
        }else{
            $type = $request['pay_code'];
        }
        //提交数据
        $data = [
            'business_no'       => $this->configs['appid'],
            'pay_code'          => $type,
            'amount'            => $request['amount'],
            'business_order_no' => $request['order_no'],
            'asynchronous_url'  => route('pay.notify', [$request['style']])
        ];

        $data['sign'] = $this->sign($data);

        $url = $this->formPost($this->configs['gateway'], $data);

        return [1, $url];
    }

    public function notify($request)
    {
        $ret = $request->all();
        $ddh = $ret['order_no']; // 订单号
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
        if ($ret['status'] == 'SUCCESS') {
            $this->changeDingdan($ddBuffer);
        }
        die('success');
    }
}
