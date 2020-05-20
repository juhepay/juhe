<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class LianheApi extends BaseApi {

    protected $configs = [
        'gateway'   => '',
        'appid'     => '',
        'key'       => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        $data = [
            'appid'      => $this->configs['appid'],
            'order_no'   => $request['order_no'],
            'amount'     => $request['amount']*100,
            'product_name'=> '商品',
            'bank_code'  => 901,
            'notify_url' => route('pay.notify',[$request['style']]),
            'return_url' => route('pay.backurl',[$request['order_no']]),
            'secret'     => $this->configs['key']
        ];
        $data['sign'] = $this->sign($data);
        unset($data['secret']);
        $tmp = CURL($this->configs['gateway'],$data);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        
        if($ret['status'] != 1)
        {
            return [0, $ret['message']];
        }
        return [1, $ret['data']['redirect_url']];
    }

    public function notify($request)
    {
        $ret = json_decode($request->getContent(), true);
        if(!is_array($ret)) die('参数错误');
        $ddh = $ret['order_no']; // 订单号
        $price = $ret['amount']/100;

        if (!preg_match("/^\w+$/", $ddh)) {
            die('order number error.');
        }

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
        $ret['secret'] = $this->configs['key'];
        $sign = $this->sign($ret);
        if ($sign != $ret['sign']) {
            die('签名错误');
        }
        //支付成功后
        if($ret['pay_status'] == 1)
        {
            $this->changeDingdan($ddBuffer);
        }

        die('success');
    }

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $str = '';
        foreach ($arr as $key => $val) {
            if ($key != 'sign') {
                $str .= $key . "=" . $val.'&';
            }
        }
        $str = rtrim($str,'&');
        return strtolower(md5($str));
    }
}

