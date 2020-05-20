<?php

namespace App\Http\Admin;

use App\Model\Admin;
use App\Model\Role;
use App\Model\Syslog;
use App\Requests\AdminsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function index()
    {
        $list = Admin::all();
        $role_list = Role::all();
        $role_array = $role_list->keyBy('id')->toArray();
        return view('Admin.Admins.index',compact('list','role_array'));
    }

    public function create()
    {
        $role_list = Role::all();
        $act = 'add';
        return view('Admin.Admins.create',compact('role_list','act'));
    }

    public function edit(Admin $admins)
    {
        $role_list = Role::all();
        $act = 'edit';
        return view('Admin.Admins.edit',compact('admins','role_list','act'));
    }

    public function store(AdminsRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        $data = array(
            'username'  => $request->username,
            'password'  => bcrypt($request->password),
            'nickname'  => $request->nickname,
            'status'    => $request->status,
            'role_id'   => $request->role_id
        );
        Admin::create($data);

        $logs_data = array(
            'module'    => '添加管理员管理',
            'content'   => '添加管理员【'.$request->username.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('admins.index'));
    }

    public function update(AdminsRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $model = Admin::findOrFail($request->id);
        $model->nickname  = $request->nickname;
        $model->status    = $request->status;
        $model->role_id   = $request->role_id;
        if(isset($request->password))
        {
            $model->password = bcrypt($request->password);
        }
        if(isset($request->reset_code))
        {
            $model->google_key = null;
        }
        $model->save();

        $logs_data = array(
            'module'    => '更新管理员管理',
            'content'   => '更新管理员【'.$request->username.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('admins.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Admin::whereIn('id',$ids)->delete();
        $logs_data = array(
            'module'    => '删除管理员管理',
            'content'   => '删除管理员ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
