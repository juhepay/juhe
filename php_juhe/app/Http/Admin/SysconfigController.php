<?php

namespace App\Http\Admin;

use App\Model\Sysconfig;
use App\Model\Syslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SysconfigController extends Controller
{
    public function index(Request $request)
    {
        $sysconfig = Sysconfig::first();
        return view('Admin.Sysconfig.index',compact('sysconfig'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $data = array(
            'min_price' => $request->min_price,
            'fl_type'   => $request->fl_type,
            'tx_fl'     => $request->tx_fl
        );
        Sysconfig::where('id',1)->update($data);

        $logs_data = array(
            'module'    => '更新系统配置',
            'content'   => '更新系统配置数据',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
