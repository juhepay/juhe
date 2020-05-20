<?php
namespace App\Gateway;
use App\Common\BaseApi;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Upaccount;

class YilupayApi extends BaseApi {

    protected $configs = [
        'gateway'   => '',
        'appid'     => '',
        'key'       => '',
    ];
    public function pay($request)
    {
        $this->loadConfigs($request['config']);
        $data = [
            'userid'     => $this->configs['appid'],
			'amount'     => sprintf('%.2f',$request['amount']),
			'payway'     => 2,
			'type'	 	 => 1,
			'addtime'	 => time(),
			'timestamp'  => time()+180,
            'order_sn'   => $request['order_no'],
            'notify_url' => route('pay.notify',[$request['style']]),
            
        ];
        $data['sign'] = $this->sign($data);
        $tmp = CURL($this->configs['gateway'],$data);
        $ret = json_decode($tmp, true);
        if(!is_array($ret)) return [0, $tmp];
		
        if($ret['status'] != 1)
        {
            return [0, $ret['info']];
        }
        return [1, $ret['link']];
    }

    public function notify($request)
    {
        $ret = $request->all();
       
        $ddh = $ret['order_sn']; // 订单号
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
        $sign = md5($ret['amount'].$ret['order_sn'].$ret['status'].$ret['userid'].$this->configs['key']);
        if ($sign != $ret['sign']) {
            die('签名错误');
        }
        //支付成功后
		if($ret['status'] == 2)
		{
			$this->changeDingdan($ddBuffer);
		}
       
        die('success');
    }

    protected function sign($arr)
    {
        ksort($arr, SORT_STRING);
        $params = http_build_query($arr);
		$params = urldecode($params);
        return md5($params.$this->configs['key']);
    }
}

