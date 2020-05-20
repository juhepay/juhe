<?php

namespace App\Http\Home;

use App\Model\FundsTurnoverLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $request->query();
        $model = FundsTurnoverLog::query();
        $where['uid'] = $user->uid;
        if(isset($request->type))
        {
            $where['type'] = $request->type;
        }

        if( (isset($request->start_time) && $request->start_time) &&  (isset($request->end_time) && $request->end_time) )
        {
            $model->whereBetween('created_at',[$request->start_time,$request->end_time]);
        }
        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Home.MoneyLog.index',compact('list','query'));
    }
}
