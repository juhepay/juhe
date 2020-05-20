<?php
namespace App\Common;

use App\Exceptions\CustomServiceException;
use App\Jobs\SendOrderAsyncNotify;
use App\Model\FundsTurnoverLog;
use App\Model\Order;
use App\Model\OrderAgent;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class BaseApi{
    protected $configs = [];
    public function loadConfigs($config)
    {
        foreach ($config as $k=>$v)
        {
            $arr = explode('_',$k);
            $this->configs[$arr[1]] = $v;
        }
    }

    public function formPost($url, $data)
    {
        $hash = md5(TimeMicroTime().rand(0000,9999));
        $data['url'] = $url;
        $text = json_encode($data);

        if(!Redis::set($hash, $text, "ex", 180, "nx")){
            return $this->formPost($url,$data);
        }
        return route('form_post').'?hash='.$hash;
    }

    public function changeDingdan(Order $order,$act = true)
    {
        if($act)
        {
            $timeout = 30 * 60;
            $order_time = strtotime($order->created_at) + $timeout;
            if($order_time < time()) return false;
        }
        DB::connection('mysql')->transaction(function () use ($order) {
            $tmp['status'] = 1;
            $tmp['paytime'] = time();
            $result = Order::where(['order_no'=>$order->order_no,'status'=>0])->update($tmp);
            $token = TimeMicroTime();
            redisLock("user:lock:".$order->uid, $token, 10);
            //查询商户原金额
            $user = User::where('uid', $order->uid)->select('balance')->first();
            $result && $result = User::where('uid', $order->uid)->increment('balance', $order->user_amount);
            unlock("user:lock:".$order->uid, $token);

            if($result)
            {
                // 资金流水
                $data = array(
                    'uid'           => $order->uid,
                    'amount'        => $order->user_amount,
                    'before_balance'=> $user->balance,
                    'after_balance' => bcadd($user->balance,$order->user_amount,2),
                    'type'          => 1,
                    'content'       => "资金流水记录：订单金额{$order->amount}元，到账金额{$order->user_amount}元",
                );
                $result = FundsTurnoverLog::create($data);
            }

            if($result)
            {
                $orderAgent = OrderAgent::where('order_no',$order->order_no)->where('money','>',0)->get();
                if(count($orderAgent)){
                    foreach ($orderAgent as $v)
                    {
                        $token = TimeMicroTime();
                        redisLock("user:lock:".$v->agent, $token, 10);
                        //查询商户原金额
                        $user = User::where('uid', $v->agent)->select('balance')->first();
                        $result = User::where('uid', $v->agent)->increment('balance', $v->money);
                        unlock("user:lock:".$v->agent, $token);

                        // 资金流水
                        $agent_data = array(
                            'uid'           => $v->agent,
                            'amount'        => $v->money,
                            'before_balance'=> $user->balance,
                            'after_balance' => bcadd($user->balance,$v->money,2),
                            'type'          => 1,
                            'content'       => "佣金记录：分润金额{$v->money}元",
                        );
                        FundsTurnoverLog::create($agent_data);
                    }
                }
            }

            if (!$result) {
                Log::info("回调失败",['order_no'=>$order->order_no,'result'=>$result]);
                throw new CustomServiceException('回调失败');
            }
        }, 1);
        SendOrderAsyncNotify::dispatch($order)->onQueue('orderNotify');
        return true;
    }
}
