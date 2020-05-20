<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class JiupayApi extends BaseApi {

    protected $configs = [
        'gateway'   => '',
        'appid'     => '',
        'key'       => ''
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);

        if($request['pay_code']  == 'alipay_h5')
        {
            $type = '10107';
        }elseif($request['pay_code']  == 'wechat_scan'){
            $type = '10104';
        }else{
            $type = $request['pay_code'];
        }

        $data = [
            'partner'  => $this->configs['appid'],
            'service'  => $type,
            'tradeNo'  => $request['order_no'],
            'amount'  => sprintf('%0.2f',$request['amount']),
            'notifyUrl'  => route('pay.notify',[$request['style']]),
            //'resultType'  => 'web',
        ];

        $data['sign'] = $this->sign($data);

        $url = $this->formPost($this->configs['gateway'], $data);

        return [1, $url];

        /*$tmp = CURL($this->configs['gateway'], $data);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if($ret['code'] != 0)
        {
            return [0, $ret['msg']];
        }
        return [1, $ret['pay_url']];*/
    }

    public function notify($request)
    {
        $ret = $request->all();
        if($ret['status'] != '1'){
            die('订单支付失败');
        }

        $ddh = $ret['outTradeNo']; // 订单号
        $price = floatval($ret['amount']);

        if (!preg_match("/^\w+$/", $ddh)) {
            die('order number error.');
        }

        //根据订单号查找对应支付账户
        $ddBuffer = Order::where(['order_no'=>$ddh,'status'=>0])->first();
        if (!$ddBuffer) die('订单不存在');
        //金额验证
        if($price != floatval($ddBuffer->amount)) die('订单金额错误');
        //验证通道是否关闭
        $exists = Apizj::where(['apistyle_id'=>$ddBuffer->apistyle_id,'upaccount_id'=>$ddBuffer->upaccount_id,'status'=>1])->exists();
        if (!$exists) die('通道已关闭');
        //获取支付账号
        $pzBuffer = Upaccount::find($ddBuffer->upaccount_id);
        //加载配置
        $this->loadConfigs($pzBuffer->upaccount_params);
        //签名校验
        $sign = $this->sign($ret);
        if ($ret['sign'] != $sign) {
            //die('签名错误');
        }
        //支付成功后
        $this->changeDingdan($ddBuffer);

        die('success');
    }

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $str = '';
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != 'signType' && $val != 'charset' && $val != null && $key != 'sign') {
                $str .= $key . "=" . $val.'&';
            }
        }
        $str .= $this->configs['key'];
        return md5($str);
    }
}

