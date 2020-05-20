<?php

namespace App\Http\Home;

use App\Model\Apistyle;
use App\Model\Apizj;
use App\Model\Order;
use App\Model\Syslog;
use App\Model\Tixian;
use App\Model\User;
use App\Model\UserRate;
use App\Requests\UsersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function dluser(Request $request)
    {
        $user  = Auth::user();
        $query = $request->query();
        if($user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        $time = [date('Y-m-d 00:00:00', time()), date('Y-m-d H:i:s', time())];
        $model = User::query();

        $where['pid'] = $user->uid;
        if(isset($request->uid) && $request->uid)
        {
            $where['uid'] = $request->uid;
        }
        if(isset($request->status))
        {
            $where['status'] = $request->status;
        }
        $list = $model->where($where)->orWhere('uid', $user->uid)->orderBy('id','desc')->paginate(30);
        if(count($list)){
            $pay_order = Order::whereBetween('created_at',$time)
                ->select('uid',
                    DB::raw('SUM(if(status = 1,amount,null)) as amount')
                )->groupBy('uid')->get();
            $pay_order = $pay_order->keyBy('uid')->toArray();

            $tx_order = Tixian::where('status',1)
                ->select('uid',
                    DB::raw('SUM(money) as money')
                )->groupBy('uid')->get();
            $tx_order = $tx_order->keyBy('uid')->toArray();
            foreach ($list as $k=>$v)
            {
                $list[$k]['amount'] = $pay_order[$v->uid]['amount'] ?? 0;
                $list[$k]['tx_amount'] = $tx_order[$v->uid]['money'] ?? 0;
            }
        }
        return view('Home.Agent.dluser',compact('list','user','query'));
    }

    public function dladduser()
    {
        $user  = Auth::user();
        if($user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        return view('Home.Agent.dladduser');
    }

    public function storeUser(UsersRequest $request)
    {
        $user  = Auth::user();
        if($user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        $max_uid = User::select('uid')->orderBy('uid','desc')->first();
        $data = array(
            'username'   => $request->username,
            'password'   => bcrypt($request->password),
            'save_code'  => bcrypt($request->save_code),
            'group_type' => $request->group_type,
            'remark'     => $request->remark,
            'status'     => 1,
            'is_jd'      => 1,
            'pid'        => $user->uid,
            'api_key'    => md5(time().$request->username),
            'uid'        => $max_uid->uid+1
        );
        $add_user = User::create($data);
        $apistyle = Apistyle::select('id')->get();
        foreach ($apistyle as $v)
        {
            $params[] = array(
                'uid'   => $add_user->uid,
                'apistyle_id' => $v->id,
                'rate'        => 0,
                'upaccount_id'=> 0,
                'status'      => 1,
            );
        }
        if(!empty($params)) UserRate::insert($params);

        $logs_data = array(
            'module'    => '添加会员',
            'content'   => '添加会员【'.$request->username.'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('member.dluser'));
    }

    public function dlfl(User $user)
    {
        $act_user  = Auth::user();
        if($act_user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($user->pid != $act_user->uid) return ajaxReturn(0,'没有操作权限');
        if($act_user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        //获取开启的接口类型
        $apistyle = Apistyle::where('status',1)->get();
        //获取我的费率
        $my_fl = UserRate::where('uid',$act_user->uid)->get();
        $my_fl = $my_fl->keyBy('apistyle_id')->toArray();
        //获取配置用户费率
        $user_fl = UserRate::where('uid',$user->uid)->get();
        $user_fl = $user_fl->keyBy('apistyle_id')->toArray();

        return view('Home.Agent.fl',compact('user','apistyle','my_fl','user_fl'));
    }

    public function storeFl(User $user,Request $request)
    {
        $act_user  = Auth::user();
        if($act_user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($user->pid != $act_user->uid) return ajaxReturn(0,'没有操作权限');
        if($act_user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        //操作人费率
        $act_user_fl = UserRate::where('uid',$act_user->uid)->get();
        $act_user_fl = $act_user_fl->keyBy('apistyle_id')->toArray();

        $ids = $request->post('apistyle');
        foreach ($ids as $v)
        {
            $fl = 'fl_'.$v;
            $status = 'status_'.$v;
            $flselect = 'flselect_'.$v;
            if($act_user_fl[$v]['rate'] > 0 && $act_user_fl[$v]['status'] == 1 && $request->$flselect == 1 && $act_user_fl[$v]['rate'] <= $request->$fl )
            {
                $data = array(
                    'rate'   => floatval($request->$fl),
                    'status' => $request->$status
                );
                UserRate::where(['uid'=>$user->uid,'apistyle_id'=>$v])->update($data);
            }else if($request->$flselect == 0 && $act_user_fl[$v]['status'] == 1){
                UserRate::where(['uid'=>$user->uid,'apistyle_id'=>$v])->update(array('status'=>$request->$status));
            }
        }
        return ajaxReturnUrl(1,'操作成功',route('member.dluser'));
    }

    public function dldd(Request $request)
    {
        $act_user  = Auth::user();
        if($act_user->group_type != 1) return ajaxReturn(0,'没有操作权限');
        if($act_user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系管理员');
        $users = User::where('pid', $act_user->uid)->select('uid')->get()->toArray();
        $uids = array_column($users,'uid');
        $query = $request->query();
        $model = Order::query();
        $where = [];
        if(isset($request->order_no) && $request->order_no)
        {
            $model->where('order_no','like','%'.$request->order_no.'%');
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

        $raw = DB::raw('SUM(amount) as amount');
        $data = $model->whereIn('uid',$uids)->where($where)->select($raw)->first()->toArray();

        $list = $model->where($where)->select('*')->orderBy('id','desc')->paginate(30);
        $apistyle = Apistyle::all();
        $apistyle = $apistyle->keyBy('id')->toArray();

        return view('Home.Agent.dldd',compact('list', 'query','apistyle','data'));
    }
}
