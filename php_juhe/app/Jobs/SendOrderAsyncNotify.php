<?php

namespace App\Jobs;

use App\Model\Order;
use App\Model\User;
use App\Tool\Md5Verify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderAsyncNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 6;
    protected $order;

    /**
     * SendOrderAsyncNotify constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = $this->order;
        $user = User::where('uid',$order->uid)->first();
        $paraBuild = $this->paraBuild($order);
        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($paraBuild, $user->api_key);
        $paraBuild['sign'] = $md5Verify->md5Encrypt($prestr,$user->api_key);

        if(strpos($order->fj['notify_url'],'webNotify') !== false)
        {
            $order->tz = 2;
            $order->save();
            return ;
        }
        $result = CURL($order->fj['notify_url'],$paraBuild);
        if(strtolower($result) == 'success'){
            $order->tz = 2;
            $order->save();
            Log::info('通知回调成功:', ['url'=>$order->fj['notify_url'], 'content' => json_encode($paraBuild),'callback'=>$result]);
        }else{
            Log::info('通知回调失败:', ['url'=>$order->fj['notify_url'], 'content' => json_encode($paraBuild),'callback'=>$result]);
        }

        if($result != 'success')
        {
            $num = $this->getDelay($this->job->attempts());
            if($num == 90)
            {
                $order->tz = 1;
                $order->errorstr = $result;
                $order->save();
            }
            $this->job->release($num);
        }
    }

    protected function paraBuild(Order $orders)
    {
        $param = array(
            'appid'      => $orders->uid,
            'amount'     => $orders->amount,
            'order_no'   => substr_replace($orders->order_no,"",strpos($orders->order_no,(string)$orders->uid),strlen($orders->uid)),
            'time'       => $orders->paytime
        );
        return $param;
    }

    /**
     * 回调间隔
     * @param int $num
     * @return float|int
     */
    protected function getDelay(int $num)
    {
        if($num == 1)
        {
            return 15;
        }else{
            return $num*15;
        }
    }
}
