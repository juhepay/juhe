<?php

namespace App\Http\Admin;

use App\Model\FundsTurnoverLog;
use App\Model\Order;
use App\Model\OrderCount;
use App\Model\Tixian;
use App\Model\Upaccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoneyLogController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $query = $request->query();
        $model = FundsTurnoverLog::query();
        $where = [];
        if(isset($request->type))
        {
            $where['type'] = $request->type;
        }
        if(isset($request->uid))
        {
            $where['uid'] = $request->uid;
        }
        if( (isset($request->start_time) && $request->start_time) &&  (isset($request->end_time) && $request->end_time) )
        {
            $model->whereBetween('created_at',[$request->start_time,$request->end_time]);
        }
        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Admin.MoneyLog.index',compact('list','query'));
    }

    public function tj(Request $request)
    {
        $query = $request->query();
        $model = Order::query();
        $tx_model = Tixian::query();
        if( (isset($request->start_time) && $request->start_time) )
        {
            $time0 = $request->start_time;
        }else{
            $time0 = '';
        }

        if (isset($request->end_time) && $request->end_time)
        {
            $time1 = $request->end_time;
        }else{
            $time1 = date('Y-m-d H:i:s',time());
        }
        if(isset($request->uid) && $request->uid){
            $model->where('uid',$request->uid);
            $tx_model->where('uid',$request->uid);
        }

        $orders = $model->whereBetween('created_at',[$time0,$time1])
            ->select('upaccount_id',
            DB::raw('COUNT(id) total_count'),
            DB::raw('count(if(status = 1,id,null)) as success_count'),
            DB::raw('SUM(if(status = 1,amount,null)) as amount'),
            DB::raw('SUM(if(status = 1,cost_amount,null)) as cost_amount'),
            DB::raw('SUM(if(status = 1,fee,null)) as fee'),
            DB::raw('SUM(if(status = 1,agent_amount,null)) as agent_amount')
        )->groupBy('upaccount_id')->get()->toArray();

        $tixian = $tx_model->where('status',1)->whereBetween('created_at',[$time0,$time1])->select('upaccount_id',
            DB::raw('COUNT(if(status = 1,id,null)) tx_count'),
            DB::raw('SUM(if(status = 1,money,null)) as tx_money'),
            DB::raw('SUM(if(status = 1,fee,null)) as tx_fee')
        )->groupBy('upaccount_id')->get();
        $tixian = $tixian->keyBy('upaccount_id')->toArray();

        $data['totla_amount'] = array_sum(array_column($orders, 'amount'));
        $data['totla_cost_amount'] = array_sum(array_column($orders, 'cost_amount'));
        $data['tx_amount'] = array_sum(array_column($tixian, 'tx_money'));
        $data['tx_fee'] = array_sum(array_column($tixian, 'tx_fee'));
        $upaccount = Upaccount::select('upaccount_name','id')->get();
        $upaccount = $upaccount->keyBy('id')->toArray();

        return view('Admin.MoneyLog.tj',compact('orders','upaccount','query','data','tixian'));
    }
}
