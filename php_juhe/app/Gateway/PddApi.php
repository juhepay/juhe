<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;
use Illuminate\Support\Facades\Log;

class PddApi extends BaseApi {

    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
        'alipay_id' => '',
        'wechat_id' => '',
        'feelist' => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);

        //金额校验
        $feelist = explode(',', $this->configs['feelist']);
        if (!in_array($request['amount'], $feelist)) {
            return [0, sprintf('金额只能是: %s元', $this->configs['feelist'])];
        }

        $data = [
            'merchant'     => $this->configs['appid'],
            'order_no'     => $request['order_no'],
            'amount'       => $request['amount'],
            'pay_mode'     =>  ($request['pay_code'] == 'alipay_h5_pdd') ? $this->configs['alipay_id'] : $this->configs['wechat_id'],
            'notify_url'   => route('pay.notify',[$request['style']]),
            'return_url'   => route('pay.backurl',[$request['order_no']])
        ];
        $data['sign'] = $this->sign($data);
        $tmp = CURL($this->configs['gateway'], $data);
        $ret = json_decode($tmp, true);
        if ( is_array($ret) ) {
            $ret['msg'] == '数据未配置' && $ret['msg'] = '请换个金额试试？';
            return [0, $ret['msg']?$ret['msg']:'服务器太忙,请重试'];
        }

        $arr = [];

        //适应1
        preg_match('/content\="\d;url\=(.+)"/isU', $tmp,$arr);

        //适应2
        (is_array($arr) && isset($arr[1])) || preg_match('/location\.href\=\'(.+)\'/isU', $tmp, $arr);

        if (is_array($arr) && isset($arr[1]) && stristr($arr[1], 'http')) {
            return [1, $arr[1]];
        }else{
            //未能适配
            return [0, $ret ? $ret : '请求失败，请重试'];
        }
    }

    public function notify($request)
    {
        $ret = $request->all();
        $ddh = $ret['out_order_no']; // 订单号
        $price = $ret['amount']; //返回的订单金额
        Log::info('pdd_回调',$ret);
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
        if ($ret['sign'] != $sign) {
            die('签名错误');
        }
        //支付成功后
        $this->changeDingdan($ddBuffer);
        die('success');
    }

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $query = [];
        foreach ($arr as $key => $val) {
            if ($val != '' && $val != 'null' && $val != null && $key != 'sign') {
                $query[] = $key . "=" . $val;
            }
        }
        $str = implode('&', $query) . '&key=' . $this->configs['key'];
        return strtolower(md5($str));
    }
}

