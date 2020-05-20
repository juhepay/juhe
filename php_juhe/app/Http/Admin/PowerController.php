<?php

namespace App\Http\Admin;

use App\Model\Power;
use App\Model\Syslog;
use App\Requests\PowerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PowerController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $model = Power::query();
        $where = [];

        if(isset($query['powers_name'])){
            $where['powers_name'] = $query['powers_name'];
        }

        if(isset($query['powers_mark'])){
            $where['powers_mark'] = $query['powers_mark'];
        }

        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Admin.Power.index',compact('list','query'));
    }

    public function create()
    {
        return view('Admin.Power.create');
    }

    public function edit(Power $power)
    {
        return view('Admin.Power.edit',compact('power'));
    }

    public function store(PowerRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $data = array(
            'powers_name'   => $request->powers_name,
            'powers_mark'   => $request->powers_mark,
            'powers_sort'   => $request->powers_sort,
        );
        Power::create($data);

        $logs_data = array(
            'module'    => '添加权限管理',
            'content'   => '添加权限【'.$request->powers_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('power.index'));
    }

    public function update(PowerRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $model = Power::findOrFail($request->id);
        $model->powers_name  = $request->powers_name;
        $model->powers_mark  = $request->powers_mark;
        $model->powers_sort  = $request->powers_sort;
        $model->save();

        $logs_data = array(
            'module'    => '更新权限管理',
            'content'   => '更新权限【'.$request->powers_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('power.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Power::whereIn('id',$ids)->delete();

        $logs_data = array(
            'module'    => '删除权限管理',
            'content'   => '删除权限ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
