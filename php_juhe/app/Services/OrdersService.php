<?php
namespace App\Services;

use App\Model\Order;
use App\Model\OrderAgent;
use App\Model\User;
use App\Model\UserRate;

class OrdersService
{
    protected $sup_uid = array();//商户的代理
    protected $agent_commission = array(); //代理佣金
    public function add($request,$user,$user_rate,$apizj)
    {
        // 商户费率大于0且大于上游费率
        if($user_rate['rate'] > 0 && $user_rate['rate'] >= $apizj['costfl'])
        {
            $rate = $user_rate['rate'];
            $user_fee = bcmul($user_rate['rate']/100,$request['amount'],2);
        }else{
            $user_fee = bcmul($apizj['runfl']/100,$request['amount'],2);
            $rate = $apizj['runfl'];
        }
        // 成本费
        $cost_fee = bcmul($apizj['costfl']/100,$request['amount'],2);

        if($user['pid'])
        {
            //代理分润
            $users = User::where('group_type',1)->get();
            $users = $users->keyBy('uid')->toArray();
            $user_list = $this->getSupUid($user['pid'],$users);
            $user_list_rate = UserRate::whereIn('uid',$user_list)->where('apistyle_id',$user_rate['apistyle_id'])->select('uid','rate')->get();
            $user_list_rate = $user_list_rate->keyBy('uid')->toArray();
            $agent_fee = $this->agentFee($user_list,$user_list_rate,$rate,$request['amount'],$request['order_no']);
        }else{
            $agent_fee = 0;
        }
        // 成本手续费+代理佣金 > 商户手续费 = 代理佣金清零
        if($cost_fee+$agent_fee > $user_fee )
        {
            $agent_fee = 0;
            unset($this->agent_commission);
        }

        $data = array(
           'uid'           => $user['uid'],
           'upaccount_id'  => $user_rate['upaccount_id'] ? $user_rate['upaccount_id'] : $apizj['upaccount_id'] ,
           'apistyle_id'   => $user_rate['apistyle_id'],
           'order_no'      => $request['order_no'],
           'amount'        => $request['amount'],
           'user_amount'   => bcsub($request['amount'],$user_fee,2),
           'cost_amount'   => $cost_fee,
           'fee'           => $user_fee,
           'agent_amount'  => $agent_fee,
           'api_style'     => $request['pay_code'],
           'client_sign'   => $request['client_sign'],
           'status'        => 0,
           'fj'            => json_encode(['notify_url'=>$request['notify_url'],'return_url'=>$request['return_url']]),
        );

        $order = Order::create($data);
        if(isset($this->agent_commission))
        {
            OrderAgent::insert($this->agent_commission);
        }
        return $order;
    }

    /**
     * 递归获取商户的上级uid
     * @param $pid
     * @param $array
     * @return array
     */
    public function getSupUid($pid,$array)
    {
        if(array_key_exists($pid,$array))
        {
            $this->sup_uid[] = $pid;
            return $this->getSupUid($array[$pid]['pid'],$array);
        }
        return $this->sup_uid;
    }

    /**
     * 代理佣金计算
     * @param array $agents 所有上级id集合
     * @param array $agent_rate 所有代理费率
     * @param float $user_rate 商户费率
     * @param float $amount 订单金额
     * @param string $order_no 订单号
     * @return float
     */
    public function agentFee(array $agents,array $agent_rate, float $user_rate, float $amount, string $order_no)
    {

        foreach ($agents as $k=>$v) {
            $rate  = $agent_rate[$v]['rate'];//代理费率
            //代理费率高于商户费率，佣金为0
            if($rate > $user_rate)
            {
                $money = 0;
            }elseif($rate <= 0){ //代理费率小于等于0，佣金为0
                $money = 0;
            }else{
                if($k == 0)
                {
                    $money = bcmul( ($user_rate-$rate)/100,$amount,2);
                }else{
                    $money =  bcmul(($agent_rate[$agents[$k-1]]['rate']-$rate)/100,$amount,2);
                }
            }
            $this->agent_commission[] = array(
                'level' => $k+1,
                'agent' => $v,
                'money' => $money,
                'rate'  => $rate,
                'order_no' => $order_no,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time())
            );
        }
        return  sprintf("%.2f", array_sum(array_column($this->agent_commission, 'money')));
    }
}
