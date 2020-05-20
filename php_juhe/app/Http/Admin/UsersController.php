<?php

namespace App\Http\Admin;

use App\Model\Apistyle;
use App\Model\Power;
use App\Model\Syslog;
use App\Model\User;
use App\Model\UserRate;
use App\Requests\UsersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $model = User::query();
        $where = [];

        if(isset($query['username'])){
            $where['username'] = $query['username'];
        }

        if(isset($query['uid'])){
            $where['uid'] = $query['uid'];
        }

        if(isset($query['status'])){
            $where['status'] = $query['status'];
        }

        if(isset($query['group_type'])){
            $where['group_type'] = $query['group_type'];
        }

        if(isset($query['agent']))
        {
            $where['pid'] = $query['agent'];
        }

        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Admin.Users.index',compact('list','query'));
    }

    public function create()
    {
        $act = 'add';
        return view('Admin.Users.create',compact('act'));
    }

    public function edit(User $user)
    {
        $act = 'edit';
        return view('Admin.Users.edit',compact('user','act'));
    }

    public function store(UsersRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        if(isset($request->pid) && $request->pid)
        {
            $exists = User::where(['uid'=>$request->pid,'group_type'=>1])->exists();
            if(!$exists) ajaxReturn(0,'代理不存在');
        }

        $max_uid = User::select('uid')->orderBy('uid','desc')->first();
        $data = array(
            'username'   => $request->username,
            'password'   => bcrypt($request->password),
            'save_code'  => bcrypt($request->save_code),
            'group_type' => $request->group_type,
            'status'     => $request->status,
            'is_jd'      => $request->is_jd,
            'pid'        => $request->pid,
            'remark'     => $request->remark,
            'api_key'    => md5(time().$request->username),
            'uid'        => $max_uid->uid+1
        );
        $user = User::create($data);
        //默认添加会员费率信息
        $apistyle = Apistyle::select('id')->get();
        foreach ($apistyle as $v)
        {
            $params[] = array(
                'uid'         => $user->uid,
                'apistyle_id' => $v->id,
                'rate'        => 0,
                'upaccount_id'=> 0,
                'status'      => 1,
            );
        }
        if(!empty($params)) UserRate::insert($params);

        $logs_data = array(
            'module'    => '添加会员管理',
            'content'   => '添加会员【'.$request->username.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('users.rates',[$user->id]));
    }

    public function update(UsersRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        if(isset($request->pid) && $request->pid)
        {
            $exists = User::where(['uid'=>$request->pid,'group_type'=>1])->exists();
            if(!$exists) return ajaxReturn(0,'代理不存在');
        }

        $model = User::find($request->id);
        if(isset($request->password))
        {
            $model->password = bcrypt($request->password);
        }
        if(isset($request->save_code))
        {
            $model->save_code = bcrypt($request->save_code);
        }

        if(isset($request->reset_code))
        {
            $model->google_key = null;
        }

        // 角色代理切换成商户时
        if($model->group_type == 1 && $request->group_type == 0 )
        {
            $change = 1;
        }

        $model->status  = $request->status;
        $model->is_jd   = $request->is_jd;
        $model->remark  = $request->remark;
        $model->pid     = $request->pid;
        $model->group_type = $request->group_type;
        $model->save();

        if(isset($change)) User::where('pid',$model->uid)->update(array('pid'=>0));

        $logs_data = array(
            'module'    => '更新会员管理',
            'content'   => '更新会员【'.$model->username.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('users.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        User::whereIn('uid',$ids)->delete();
        UserRate::whereIn('uid',$ids)->delete();
        $logs_data = array(
            'module'    => '删除会员管理',
            'content'   => '删除会员ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }

    public function rates(User $user)
    {
        $apistyle = Apistyle::with('upaccount')->get();
        $user_rate = UserRate::where('uid',$user->uid)->get();
        $user_rate = $user_rate->keyBy('apistyle_id')->toArray();
        return view('Admin.Users.rate',compact('apistyle','user','user_rate'));
    }

    public function ratesStore(User $user,Request $request)
    {
        $ids = $request->post('apistyle');
        if (empty($ids)){
            return ajaxReturn(0,'请先选择接口类型');
        }
        $user_rate = UserRate::where('uid',$user->uid)->whereIn('apistyle_id',$ids)->get();
        $user_rate = $user_rate->keyBy('apistyle_id')->toArray();
        foreach ($ids as $v)
        {
            $fl = 'fl_'.$v;
            $upaccount_id = 'account_'.$v;
            $status = 'status_'.$v;
            $flselect = 'flselect_'.$v;
            if($request->$flselect == 0)
            {
                $data['rate'] = 0;
            }else{
                $data['rate'] = floatval($request->$fl) ?? 0;
            }
            $data['uid']          = $user->uid;
            $data['upaccount_id'] = $request->$upaccount_id;
            $data['apistyle_id']  = $v;
            $data['status']  = $request->$status ?? 0;

            if(isset($user_rate[$v]))
            {
                UserRate::where(['apistyle_id'=>$v,'uid'=>$user->uid])->update($data);
            }else{
                UserRate::create($data);
            }
        }
        return ajaxReturnUrl(1,'操作成功',route('users.index'));
    }

    public function freeze(User $user)
    {
        return view('Admin.Users.freeze',compact('user'));
    }

    public function addfreezes(User $user, Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        $money = floatval($request->money);


        if($request->act == 1)
        {
            if($money <= 0 || $user->balance < $money) return ajaxReturn(0,'冻结金额有误');
            DB::connection('mysql')->transaction(function () use ($user,$money) {
                User::where('uid',$user->uid)->decrement('balance',$money);
                User::where('uid',$user->uid)->increment('djmoney',$money);
            }, 1);
            $module  = '会员冻结';
            $content = '会员ID:'.$user->uid.', 冻结金额【'.$money.'】';
        }else{
            if($money <= 0 || $user->djmoney < $money) return ajaxReturn(0,'解冻金额有误');
            DB::connection('mysql')->transaction(function () use ($user,$money) {
                User::where('uid',$user->uid)->decrement('djmoney',$money);
                User::where('uid',$user->uid)->increment('balance',$money);
            }, 1);

            $module  = '会员解冻';
            $content = '会员ID:'.$user->uid.', 解冻金额：【'.$money.'】';
        }

        $logs_data = array(
            'module'    => $module,
            'content'   => $content,
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('users.index'));
    }
}
