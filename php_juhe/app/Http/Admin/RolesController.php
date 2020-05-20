<?php

namespace App\Http\Admin;

use App\Model\Power;
use App\Model\Role;
use App\Model\Syslog;
use App\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $list = Role::all();
        return view('Admin.Role.index',compact('list'));
    }

    public function create()
    {
        $power_list = Power::orderBy('id','desc')->get()->toArray();
        $data = [];
        foreach ($power_list as $k=>$v)
        {
            $tmp = explode('.',$v['powers_mark']);
            $data[$tmp[0]][] = $v;
        }
        return view('Admin.Role.create',compact('data'));
    }

    public function edit(Role $role)
    {
        $power_list = Power::orderBy('id','desc')->get()->toArray();
        $data = [];
        foreach ($power_list as $k=>$v) {
            $tmp = explode('.', $v['powers_mark']);
            $data[$tmp[0]][] = $v;
        }
        return view('Admin.Role.edit',compact('role','data'));
    }

    public function store(RoleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        $data['role_name'] = $request->role_name;
        if( in_array('all',$request->power_ids))
        {
            $data['power_ids'] = 'all';
        }else{
            $data['power_ids'] = json_encode($request->power_ids);
        }
        Role::create($data);

        $logs_data = array(
            'module'    => '添加角色管理',
            'content'   => '添加角色【'.$request->role_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('roles.index'));
    }

    public function update(RoleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $model = Role::find($request->id);
        $model->role_name  = $request->role_name;
        if( in_array('all',$request->power_ids))
        {
            $model->power_ids = 'all';
        }else{
            $model->power_ids = json_encode($request->power_ids);
        }
        $model->save();

        $logs_data = array(
            'module'    => '更新角色管理',
            'content'   => '更新角色【'.$request->role_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('roles.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Role::whereIn('id',$ids)->delete();

        $logs_data = array(
            'module'    => '删除角色管理',
            'content'   => '删除角色ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
