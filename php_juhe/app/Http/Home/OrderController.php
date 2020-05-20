<?php

namespace App\Http\Home;

use App\Model\Apistyle;
use App\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Tool\ExportTool;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $query = $request->query();
        $model = Order::query();
        $where['uid'] = $user->uid;

        if(isset($request->order_no) && $request->order_no)
        {
            $where['order_no'] = $user->uid.$request->order_no;
        }

        if(isset($request->api_style) && $request->api_style)
        {
            $where['api_style'] = $request->api_style;
        }

        if(isset($request->status))
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
                    substr_replace($v->order_no,"",strpos($v->order_no,(string)$v->uid),strlen($v->uid)),$v->amount,$v->user_amount,(string)$v->created_at,$v->paytime
                );
            }
            if(!isset($data)) $data = [];
            $exportTool = new ExportTool();
            $header = ['订单号','订单金额','商户实收','添加时间','支付时间'];
            $exportTool->D($data,$header,'order.xlsx');
        }

        $raw = DB::raw('SUM(amount) as amount,SUM(fee) as fee');
        $data = $model->where($where)->select($raw)->first()->toArray();

        $list = $model->where($where)->select('*')->orderBy('id','desc')->paginate(30);
        $apistyle = Apistyle::all();
        $apistyle = $apistyle->keyBy('id')->toArray();

        return view('Home.Order.index', compact('list', 'query','apistyle','data'));
    }
}
