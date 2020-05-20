<?php

namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;
use Illuminate\Support\Facades\Log;

class PersonalApi extends BaseApi
{
    protected $configs = [
        'gateway' => '',
        'appid' => '',
        'key' => '',
        'use_form' => '',
    ];

    protected function sign($arr)
    {
        $str = $arr['uid'] . $arr['price'] . $arr['paytype'] . $arr['notify_url'] . $arr['return_url'] . $arr['user_order_no'] . $this->configs['key'];
        return strtolower(md5($str));
    }

    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        $uri = $this->configs['gateway']; //网关
        //提交数据
        $data = [
            'uid' => $this->configs['appid'],
            'user_order_no' => $request['order_no'],
            'price' => $request['amount'],
            'notify_url' => route('pay.notify', [$request['style']]),
            'return_url' => route('pay.backurl', [$request['order_no']]),
        ];
        switch ($request['pay_code']) {
            case 'alipay_scan':
                $data['paytype'] = 13;
                break;
            case 'wechat_scan':
                $data['paytype'] = 1;
                break;
            case 'unipay_scan':
                $data['paytype'] = 9;
                break;
            default:
                $data['paytype'] = 14;
                break;
        }

        $data['sign'] = $this->sign($data);

        // 使用表单直接提交数据
        if ($this->configs['use_form']) {
            $gateway = preg_replace("/\/json$/i", '', $this->configs['gateway']);
            $url = $this->formPost($gateway, $data);
            return [1, $url];
        }
        $tmp = CURL($uri, $data, 'json');
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
        if ((int)$ret['Code'] == 1) {
            $url = getScanUrl($request['order_no'], $ret['QRCodeLink'],$ret['RealPrice']);
            return [1, $url];
        } else {
            $ret['Msg'] = preg_replace('/交易金额错误\s*/is', '', $ret['Msg']);
            return [0, $ret['Msg'] ? $ret['Msg'] : '通道网关异常'];
        }
    }

    public function notify($request)
    {
        $ret = json_decode($request->getContent(), true);
        if(!is_array($ret)) die('参数错误');

        $ddh = $ret['user_order_no'];
        $price = $ret['price']; //返回出来的订单金额

        //根据订单号查找对应支付账户
        $ddBuffer = Order::where(['order_no' => $ddh, 'status' => 0])->first();
        if (!$ddBuffer)die('订单不存在');
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
       
        if ($ret['sign'] != md5($ret['user_order_no'].$ret['orderno'].$ret['tradeno'].$ret['price'].$ret['realprice'].$this->configs['key'])){
            die('签名错误!');
        }
        
        //支付成功后
        if ((int)$ret['status'] == 3) {
            if($this->changeDingdan($ddBuffer)){
                $str = cache($ddh);
                if($str){
                    $arr = json_decode($str,true);
                    $arr['status'] = 1;
                    cache([$ddh => json_encode($arr)], now()->addMinutes(3));
                }
            }
        }
        die('success');
    }
}
