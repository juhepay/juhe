<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class TailianApi extends BaseApi {

    protected $configs = [
        'gateway'   => '',
        'appid'     => '',
        'key'       => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        if($request['pay_code'] == 'alipay_scan')
        {
            $type = 903;
        }elseif($request['pay_code'] == 'alipay_h5'){
            $type = 904;
        } elseif($request['pay_code'] == 'wechat_scan'){
            $type = 902;
        } elseif($request['pay_code'] == 'wechat_h5'){
            $type = 901;
        } elseif($request['pay_code'] == 'unipay'){
            $type = 911;
        }else{
            $type = $request['pay_code'];
        }
        $data = [
            'customer_no'      => $this->configs['appid'],
            'customer_order'   => $request['order_no'],
            'produce_date'     => date('Y-m-d H:i:s',time()),
            'bank_code'        => $type,
            'amount'           => sprintf('%.2f',$request['amount']),
            'notify_url'       => route('pay.notify',[$request['style']]),
            'callback_url'     => route('pay.backurl',[$request['order_no']]),

        ];
        $data['sign_md5'] = $this->sign($data);
        $data['goods_name'] ='VIP基础服务';
        $url = $this->formPost($this->configs['gateway'],$data);
        return [1, $url];
    }

    public function notify($request)
    {
        $ret = $request->all();
        $ddh = $ret['customer_order']; // 订单号
        $price = $ret['amount'];

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
        $sign = $this->sign($ret);
        if ($sign != $ret['sign_md5']) {
            die('签名错误');
        }
        //支付成功后
        if($ret['trading_code'] == '00')
        {
            $this->changeDingdan($ddBuffer);
        }

        die('ok');
    }

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $str = '';
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != null && $key != 'sign_md5') {
                $str .= $key . "=" . $val.'&';
            }
        }
        $str .= 'key=' . $this->configs['key'];
        return strtoupper(md5($str));
    }
}

