<?php

namespace App\Http\Admin;

use App\Model\Order;
use App\Model\OrderCount;
use App\Model\Syslog;
use App\Model\Tixian;
use App\Model\User;
use App\Requests\PassRequest;
use App\Tool\GoogleAuthenticator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function index()
    {
        $data['user_count'] = User::where('group_type',0)->count();
        $data['agent_count'] = User::where('group_type',1)->count();
        $users = User::get()->toArray();
        $data['user_balance'] = sprintf("%.2f", array_sum(array_column($users, 'balance')));
        $data['user_gt_balance'] = 0;
        foreach ($users as $v)
        {
            if($v['balance'] > 10000){
                $data['user_gt_balance'] += 1;
            }
        }

        //今日订单数据
        $time1 = date('Y-m-d 00:00:00', time());
        $time2 = date('Y-m-d 00:00:00', strtotime("+1 day"));
        $order = Order::where('status',1)->whereBetween('created_at',[$time1, $time2] )->select('amount','user_amount','agent_amount','cost_amount')->get()->toarray();
        $data['today_order_success_count']  = count($order); //今日成功总订单
        $data['today_order_success_amount'] = array_sum(array_column($order, 'amount'));//今日收款
        $data['today_order_user_amount']    = array_sum(array_column($order, 'user_amount'));//今日商户实收
        $data['today_order_agent_amount']   = array_sum(array_column($order, 'agent_amount'));//今日代理分润
        $data['today_order_cost_amount']    = array_sum(array_column($order, 'cost_amount'));//今日成本
        $data['today_order_total_count']    = Order::whereBetween('created_at',[$time1, $time2] )->count();//今日总订单

        // 总提现数据
        $data['tx_sum']     = Tixian::where('status',1)->sum('money');
        $data['tx_no_sum']  = Tixian::where('status',0)->sum('money');
        //今日提现数据
        $data['today_tx_sum']     = Tixian::where('status',1)->whereBetween('created_at',[$time1, $time2] )->sum('money');
        $data['today_tx_no_sum']  = Tixian::where('status',0)->whereBetween('created_at',[$time1, $time2] )->sum('money');
        for ($i=30;$i>0;$i--)
        {
            $time_array[] = date('Ymd',strtotime("-{$i} day"));
        }

        $time = date('Ymd',strtotime("-30 day"));
        $orderCount = OrderCount::where('addtime','>',$time)->orderBy('id','desc')->get();
        $orderCount = $orderCount->keyBy('addtime')->toArray();
        $data['month_success_count']= array_sum(array_column($orderCount, 'success_count'));//近30天成功订单笔数
        $data['month_total_count']  = array_sum(array_column($orderCount, 'total_count'));//近30天总订单笔数
        $data['month_ratio']        = $data['month_total_count'] ? sprintf('%.2f',$data['month_success_count']/$data['month_total_count']*100) : 0; //30天成功率
        $data['month_agent_amount'] = array_sum(array_column($orderCount, 'success_agent_amount'));//近30天代理收益
        $data['month_fee']          = array_sum(array_column($orderCount, 'success_fee'));//近30天总手续费
        $data['month_cost_amount']  = array_sum(array_column($orderCount, 'success_cost_amount'));//近30天成本费
        $data['sys_amount']         = sprintf('%.2f',$data['month_fee']-$data['month_cost_amount']-$data['month_agent_amount']) ;//近30天系统收益
        foreach ($time_array as $v)
        {
            $order_time_array[] = array(
                'date'      => date( 'm月d日', strtotime( $v ) ),
                'totalmoney'=> isset($orderCount[$v]) ? $orderCount[$v]['total_amount'] : 0
            );
        }
        $order_time_array = json_encode($order_time_array);

        // 总资金流水
        $total_amount = OrderCount::sum('success_amount');
        $data['order_sum'] = $total_amount+$data['today_order_success_amount'];
        $total_count = OrderCount::sum('success_count');
        $data['order_success_sum'] = $data['today_order_success_count']+$total_count;
        return view('Admin.index',compact('data','order_time_array'));
    }

    public function info()
    {
        $admin = Auth::user();

        $google = app(GoogleAuthenticator::class);
        $secret = $google->createSecret();
        $name   = $admin->username.'@'.$_SERVER['HTTP_HOST'];
        $qrCodeUrl  = $google->getQRCodeGoogleUrl($name, $secret);

        return view('Admin.info',compact('admin','qrCodeUrl','secret'));
    }

    /**
     * 修改个人资料
     * @param PassRequest $request
     * @return false|string
     */
    public function pass(PassRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        if($request->style == 1)
        {
            if(!Hash::check($request->passwordy, $admin->password)){
                return ajaxReturn(0,'原密码错误');
            }
            $admin->password = bcrypt($request->password);
        }elseif($request->style == 3){
            $google = app(GoogleAuthenticator::class);
            $result = $google->verifyCode($request->secret,$request->google_code,2);
            if(!$result) return ajaxReturn(0,'验证码错误，请重新添加');
            $admin->google_key = $request->secret;
        }else{
            return ajaxReturn(0,'参数错误');
        }
        $admin->save();

        $logs_data = array(
            'module'    => '我的资料',
            'content'   => '修改资料【'.$request->style.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);

        return ajaxReturnUrl(1,'操作成功',route('admin.dropout'));
    }

    public function checkneworder()
    {
        $count = Tixian::where('status',0)->count();
        if($count > 0 )
        {
            return json_encode(array('c' => 1, 'm' => '有新的代付订单','n'=>$count));
        }else{
            return json_encode(array('c' => 0, 'm' => ''));
        }
    }
}
