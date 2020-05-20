<?php

namespace App\Http\Admin;

use App\Common\BaseApi;
use App\Jobs\SendOrderAsyncNotify;
use App\Model\Apistyle;
use App\Model\FundsTurnoverLog;
use App\Model\Order;
use App\Model\OrderAgent;
use App\Model\PayLog;
use App\Model\Syslog;
use App\Model\Upaccount;
use App\Model\User;
use App\Services\ManyErrorLockService;
use App\Tool\ExportTool;
use App\Tool\GoogleAuthenticator;
use App\Tool\Md5Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $query = $request->query();
        $model = Order::query();
        $where = [];
        $title = '订单管理';

        if(isset($request->uid) && $request->uid)
        {
            $where['uid'] = $request->uid;
        }
        if(isset($request->order_no) && $request->order_no)
        {
            $model->where('order_no','like','%'.$request->order_no.'%');
        }
        if(isset($request->client_sign) && $request->client_sign)
        {
            $where['client_sign'] = $request->client_sign;
        }
        if(isset($request->api_style) && $request->api_style)
        {
            $where['api_style'] = $request->api_style;
        }
        if(isset($request->upaccount_id) && $request->upaccount_id)
        {
            $where['upaccount_id'] = $request->upaccount_id;
        }
        if(isset($request->status) )
        {
            $where['status'] = $request->status;
        }

        if( (isset($request->start_time) && $request->start_time) )
        {
            $time0 = $request->start_time;
        }else{
            $time0 = date('Y-m-d 00:00:00');
        }
        if (isset($request->end_time) && $request->end_time)
        {
            $time1 = $request->end_time;
        }else{
            $time1 = date('Y-m-d H:i:s',time());
        }

        $model->whereBetween('created_at',[$time0,$time1]);
        // 导出
        if(isset($request->export) && $request->export == 1)
        {
            $orders = $model->where($where)->orderBy('id','desc')->get();
            foreach ($orders as $k=>$v)
            {
                $data[] = array(
                    $v->id,$v->uid,$v->order_no,$v->amount,$v->user_amount,$v->agent_amount,$v->cost_amount,(string)$v->created_at,$v->paytime
                );
            }
            if(!isset($data)) $data = [];
            $exportTool = new ExportTool();
            $header = ['id', '商户id','订单号','订单金额','商户实收','代理佣金','上游扣费','添加时间','支付时间'];
            $exportTool->D($data,$header,'订单列表'.$time0.'.xlsx');
        }


        $raw = DB::raw('SUM(amount) as amount,SUM(agent_amount) as agent_amount,SUM(cost_amount) as cost_amount,SUM(fee) as fee,COUNT(IF(pay_orders.status = 1 , pay_orders.id, null))  as success_count,COUNT(id) as count');
        $data = $model->where($where)->select($raw)->first()->toArray();

        $data['sys_amount']  = sprintf('%.2f',$data['fee']-$data['cost_amount']-$data['agent_amount']) ;
        $data['radio']       = $data['count'] ? sprintf('%.2f',$data['success_count']/$data['count']*100): 0 ;

        $apistyle = Apistyle::all();
        $apistyle = $apistyle->keyBy('id')->toArray();

        $upaccount = Upaccount::orderBy('id','desc')->select('upaccount_name','id')->get();
        $upaccount = $upaccount->keyBy('id')->toArray();

        $query['start_time'] = $time0;

        $list = $model->where($where)->select('*')->orderBy('id','desc')->paginate(100);
        return view('Admin.Order.index', compact('list', 'query','title','apistyle','upaccount','data'));
    }

    public function edit(Order $order)
    {
        $order_agent = OrderAgent::where('order_no',$order->order_no)->get();
        return view('Admin.Order.edit',compact('order','order_agent'));
    }

    public function budan(Order $order,Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        if($order->status != 0) return ajaxReturn(0,'未支付订单才能补单');

        $request->offsetSet('username',$admin->username);
        $manyErrorLockService = app(ManyErrorLockService::class);
        if( $manyErrorLockService->hasTooManyActionAttempts($request) )
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'错误次数过多,请稍后再来');
        }

        $googleAuthenticator = app(GoogleAuthenticator::class);
        if(!$googleAuthenticator->verifyCode($admin->google_key,$request->google_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }

        $baseApi = app(BaseApi::class);
        $baseApi->changeDingdan($order,false);

        $logs_data = array(
            'module'    => '订单补单',
            'content'   => '订单补单单号【'.$order->order_no.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'补单成功');
    }

    public function notice(Order $order,Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        if($order->status != 1) return ajaxReturn(0,'订单未支付');

        $request->offsetSet('username',$admin->username);
        $manyErrorLockService = app(ManyErrorLockService::class);
        if( $manyErrorLockService->hasTooManyActionAttempts($request) )
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'错误次数过多,请稍后再来');
        }

        $googleAuthenticator = app(GoogleAuthenticator::class);
        if(!$googleAuthenticator->verifyCode($admin->google_key,$request->google_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }
        $manyErrorLockService->clearLoginAttempts($request);

        $user = User::where('uid',$order->uid)->first();
        $paraBuild = $this->paraBuild($order);
        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($paraBuild, $user->api_key);
        $paraBuild['sign'] = $md5Verify->md5Encrypt($prestr,$user->api_key);
        $result = CURL($order->fj['notify_url'],$paraBuild);
        if(strtolower($result) == 'success'){
            $order->tz = 2;
            $order->save();
            return ajaxReturn(1,'通知成功');
        }else{
            return ajaxReturn(0,'通知失败：'.$result);
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

    public function back(Order $order,Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        if($order->status != 1) return ajaxReturn(0,'订单未支付');
        $request->offsetSet('username',$admin->username);
        $manyErrorLockService = app(ManyErrorLockService::class);
        if( $manyErrorLockService->hasTooManyActionAttempts($request) )
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'错误次数过多,请稍后再来');
        }

        $googleAuthenticator = app(GoogleAuthenticator::class);
        if(!$googleAuthenticator->verifyCode($admin->google_key,$request->google_code))
        {
            $manyErrorLockService->incrementActionAttempts($request);
            return ajaxReturn(0,'谷歌验证码错误');
        }
        $manyErrorLockService->clearLoginAttempts($request);

        DB::connection('mysql')->transaction(function () use ($order) {
            $tmp['status']   = 0;
            $tmp['paytime']  = null;
            $tmp['tz']       = 0;
            $tmp['errorstr'] = null;
            $token = TimeMicroTime();
            $result = Order::where(['order_no'=>$order->order_no,'status'=>1])->update($tmp);
            redisLock("user:lock:".$order->uid, $token, 10);
            //查询商户原金额
            $user = User::where('uid', $order->uid)->select('balance')->first();
            $result && $result = User::where('uid', $order->uid)->decrement('balance', $order->user_amount);
            unlock("user:lock:".$order->uid, $token);

            if($result)
            {
                // 资金流水
                $data = array(
                    'uid'           => $order->uid,
                    'amount'        => -$order->user_amount,
                    'before_balance'=> $user->balance,
                    'after_balance' => bcsub($user->balance,$order->user_amount,2),
                    'type'          => 1,
                    'content'       => "退单记录：订单号{$order->order_no}，退款金额{$order->user_amount}元",
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
                        $result = User::where('uid', $v->agent)->decrement('balance', $v->money);
                        unlock("user:lock:".$v->agent, $token);

                        // 资金流水
                        $agent_data = array(
                            'uid'           => $v->agent,
                            'amount'        => -$v->money,
                            'before_balance'=> $user->balance,
                            'after_balance' => bcsub($user->balance,$v->money,2),
                            'type'          => 1,
                            'content'       => "退单记录：订单号{$order->order_no},退佣金{$v->money}元",
                        );
                        FundsTurnoverLog::create($agent_data);
                    }
                }
            }
        }, 1);

        $logs_data = array(
            'module'    => '订单退款',
            'content'   => '订单退款单号【'.$order->order_no.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'退单成功');
    }

    public function payLog(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $query = $request->query();
        $model = PayLog::query();
        $where = [];

        if(isset($request->uid) && $request->uid)
        {
            $where['uid'] = $request->uid;
        }
        if(isset($request->order_no) && $request->order_no)
        {
            $where['order_no'] = $request->order_no;
        }
        if( (isset($request->start_time) && $request->start_time) )
        {
            $time0 = $request->start_time;
        }else{
            $time0 = date('Y-m-d 00:00:00');
        }
        if (isset($request->end_time) && $request->end_time)
        {
            $time1 = $request->start_time;
        }else{
            $time1 = date('Y-m-d H:i:s',time());;
        }
        $model->whereBetween('created_at',[$time0,$time1]);
        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Admin.Order.paylog',compact('list','query'));
    }

    public function payLogDelete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        PayLog::whereIn('id',$ids)->delete();
        $logs_data = array(
            'module'    => '删除接口日志',
            'content'   => '删除接口日志ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
