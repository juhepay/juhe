<?php

namespace App\Console\Commands;

use App\Model\Order;
use App\Model\OrderCount;
use Illuminate\Console\Command;

class SingleDayOrderCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'single day order count';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time1 = date('Y-m-d 00:00:00', strtotime("-1 day"));
        $time2 = date('Y-m-d 00:00:00', time());
        $orders = Order::where('status',1)->whereBetween('created_at',[$time1, $time2] )->select('amount','user_amount','cost_amount','agent_amount','fee')->get()->toArray();

        $data['total_amount']           = sprintf("%.2f", array_sum(array_column($orders, 'amount')));
        $data['success_cost_amount']    = sprintf("%.2f", array_sum(array_column($orders, 'cost_amount')));
        $data['success_agent_amount']   = sprintf("%.2f", array_sum(array_column($orders, 'agent_amount')));
        $data['success_fee']            = sprintf("%.2f", array_sum(array_column($orders, 'fee')));
        $data['success_count']          = count($orders);

        $data['success_amount'] = Order::whereBetween('created_at',[$time1, $time2] )->sum('amount');
        $data['total_count']    = Order::whereBetween('created_at',[$time1, $time2] )->count();
        unset($orders);
        $data['addtime'] = date('Ymd',strtotime("-1 day"));
        $orderCount = OrderCount::where('addtime',$data['addtime'])->first();
        if($orderCount)
        {
            $orderCount->total_amount           = $data['total_amount'];
            $orderCount->success_cost_amount    = $data['success_cost_amount'];
            $orderCount->success_agent_amount   = $data['total_amount'];
            $orderCount->success_agent_amount   = $data['success_agent_amount'];
            $orderCount->success_fee            = $data['success_fee'];
            $orderCount->success_count          = $data['success_count'];
            $orderCount->total_count            = $data['total_count'];
            $orderCount->save();
        }else{
            OrderCount::create($data);
        }
    }
}
