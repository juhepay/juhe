<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;
use Illuminate\Support\Facades\Log;

class GhabpayApi extends BaseApi {

    protected $configs = [
        'gateway'   => '',
        'appid'     => '',
        'key'       => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        $data = [
            'merId'        => $this->configs['appid'],
            'payPlat'      => 'quickpay',
            'payType'      => 'wap',
            'notifyType'   => 0,
            'orderAmt'     => sprintf('%.2f',$request['amount']),
            'merOrderNo'   => $request['order_no'],
            'notifyUrl'    => route('pay.notify',[$request['style']]),
            'callbackUrl'  => route('pay.backurl',[$request['order_no']]),
        ];
        $data['sign'] = $this->sign($data);
        $tmp = CURL($this->configs['gateway'].'?'.http_build_query($data), []);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if($ret['respCode'] != '0000')
        {
            return [0, $ret['respMsg']];
        }

        return [1, $ret['jumpUrl']];
    }

    public function notify($request)
    {
        $ret = $request->all();
        $ddh = $ret['merOrderNo']; // 订单号
        $price = floatval($ret['orderAmt']);

        if (!preg_match("/^\w+$/", $ddh)) {
            die('order number error.');
        }

        //根据订单号查找对应支付账户
        $ddBuffer = Order::where(['order_no'=>$ddh,'status'=>0])->first();
        if (!$ddBuffer) die('订单不存在');
        //金额验证
        if(bccomp($price,$ddBuffer->amount,2) != 0 ){
        	die('订单金额错误');
        } 
        //验证通道是否关闭
        $exists = Apizj::where(['apistyle_id'=>$ddBuffer->apistyle_id,'upaccount_id'=>$ddBuffer->upaccount_id,'status'=>1])->exists();
        if (!$exists) die('通道已关闭');
        //获取支付账号
        $pzBuffer = Upaccount::find($ddBuffer->upaccount_id);
        //加载配置
        $this->loadConfigs($pzBuffer->upaccount_params);
        //签名校验
        $sign = $this->sign($ret);
        if ($sign != $ret['sign']) {
            die('签名错误');
        }

        //支付成功后
        if($ret['payStatus'] != 2)
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
            if ($val !== '' && $val !== 'null' && $val !== null && $key != 'sign') {
                $str .= $key . "=" . $val.'&';
            }
        }
        $str .= 'key=' . $this->configs['key'];
        return md5($str);
    }
}

