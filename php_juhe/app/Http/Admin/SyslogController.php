<?php

namespace App\Http\Admin;

use App\Model\Syslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyslogController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $model = Syslog::query();
        $where = [];
        if(isset($request->is_admin) && $request->is_admin != '')
        {
            $where['is_admin'] = $request->is_admin;
        }

        if(isset($request->username) && $request->username)
        {
            $where['username'] = $request->username;
        }

        if( (isset($request->start_time) && $request->start_time) &&  (isset($request->end_time) && $request->end_time) )
        {
            $model->whereBetween('created_at',[$request->start_time,$request->end_time]);
        }

        $list = $model->where($where)->orderBy('id','desc')->paginate(30);
        return view('Admin.Syslog.index',compact('list','query'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Syslog::whereIn('id',$ids)->delete();

        $logs_data = array(
            'module'    => '删除系统日志',
            'content'   => '删除系统日志ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
