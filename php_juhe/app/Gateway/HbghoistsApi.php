<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class HbghoistsApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
    ];

    public function pay($request)
    {
        $this->loadConfigs($request['config']);

        //提交数据
        $data = [
            'uid'         => $this->configs['appid'],
            'orderid'     => $request['order_no'],
            'istype'      => 1,
            'notify_url'  => route('pay.notify', [$request['style']]),
            'return_url'  => route('pay.backurl',[$request['order_no']]),
            'price'       => $request['amount'],
            'goodsname'   => '',
            'orderuid'    => ''
        ];
        $data['key'] = md5($data['goodsname']. $data['istype'] . $data['notify_url'] . $data['orderid'] . $data['orderuid'] . $data['price'] . $data['return_url'] . $this->configs['key'] . $data['uid']);
        $data['format'] = 'json';

        $tmp = CURL($this->configs['gateway'], $data);

        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if($ret['code'] != 1)
        {
            return [0, $ret['msg']];
        }
        return [1, $ret['data']['url']];
    }

    public function notify($request)
    {
        $ret = $request->all();
		
        $ddh   = $ret['orderid']; // 订单号
        $price = $ret['price']; //返回的订单金额

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
        $key=md5($ret['orderid'].$ret['orderuid'].$ret['pay_id'].$ret['price'].$ret['realprice'].$this->configs['key'] );
        if ($ret['key'] != $key) {
            die('签名错误');
        }
        //支付成功后
        $this->changeDingdan($ddBuffer);
        die('success');
    }
}
