<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;
use Illuminate\Support\Facades\Log;
class XulongApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
        'use_form' => '',
    ];

    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        $uri = $this->configs['gateway']; //网关
        //提交数据
        $data = [
            'merchantNum' => $this->configs['appid'],
            'payType'=> 'alipay',
            'orderNo' => $request['order_no'],
            'amount' => $request['amount'],
            'notifyUrl' => route('pay.notify',[$request['style']]),
            'returnUrl' => route('pay.backurl',[$request['order_no']]),
        ];

        $data['sign'] = md5($data['merchantNum'].$data['orderNo'].(string)$data['amount'].$data['notifyUrl'].$this->configs['key']);

        $tmp = CURL($uri, $data);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if ($ret['code'] == 200) {
            return [1, $ret['data']['payUrl']];
        } else {
            return [0, $ret['msg']];
        }
    }

    public function notify($request)
    {
        $ret = $request->all();
        $ddh = $ret['orderNo'];
        $price = $ret['amount']; //返回出来的订单金额

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
        $sign = md5($ret['state'].$ret['merchantNum'].$ret['orderNo'].$ret['amount'].$this->configs['key']);
        if ($sign != $ret['sign']) {
            die('签名错误');
        }
        //支付成功后
        if ($ret['state'] == 1) {
            $this->changeDingdan($ddBuffer);
        }
        die('success');
    }
   
}
