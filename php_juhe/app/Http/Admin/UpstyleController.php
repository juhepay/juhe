<?php

namespace App\Http\Admin;

use App\Model\Syslog;
use App\Model\Upstyle;
use App\Requests\UpstyleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpstyleController extends Controller
{
    public function index()
    {
        $list = Upstyle::all();
        return view('Admin.Upstyle.index',compact('list'));
    }

    public function create()
    {
        return view('Admin.Upstyle.create');
    }

    public function edit(Upstyle $upstyle)
    {
        return view('Admin.Upstyle.edit',compact('upstyle'));
    }

    public function store(UpstyleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        foreach ($request->paramstitle as $k=>$v)
        {
            $param[] = array(
                'paramstitle' => $v,
                'paramsen'    => $request->paramsen[$k],
                'paramsinput' => $request->paramsinput[$k],
                'paramsvalue' => $request->paramsvalue[$k],
            );
        }

        $data = array(
            'upstyle_name'  => $request->upstyle_name,
            'upstyle_mark'  => $request->upstyle_mark,
            'params'        => json_encode($param),
        );
        Upstyle::create($data);

        $logs_data = array(
            'module'    => '添加上游类型',
            'content'   => '添加类型【'.$request->upstyle_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('upstyle.index'));
    }

    public function update(UpstyleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        foreach ($request->paramstitle as $k=>$v)
        {
            $param[] = array(
                'paramstitle' => $v,
                'paramsen'    => $request->paramsen[$k],
                'paramsinput' => $request->paramsinput[$k],
                'paramsvalue' => $request->paramsvalue[$k],
            );
        }

        $model = Upstyle::find($request->id);
        $model->upstyle_name  = $request->upstyle_name;
        $model->upstyle_mark  = $request->upstyle_mark;
        $model->params        = json_encode($param);
        $model->save();

        $logs_data = array(
            'module'    => '更新上游类型',
            'content'   => '更新类型【'.$request->upstyle_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('upstyle.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Upstyle::whereIn('id',$ids)->delete();
        $logs_data = array(
            'module'    => '删除上游类型',
            'content'   => '删除类型ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
