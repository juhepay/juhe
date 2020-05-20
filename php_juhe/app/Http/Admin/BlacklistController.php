<?php

namespace App\Http\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class BlacklistController extends Controller
{
    public function index()
    {
        $list = Redis::zrange('blacklist',0,-1);
        return view('Admin.Blacklist.index',compact('list'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        if(!isset($request->client_sign)) return ajaxReturn(0,'参数错误');
        if( !Redis::zAdd('blacklist','NX',time(),$request->client_sign) ) return ajaxReturn(0,'客户端标识已存在');
        return ajaxReturnUrl(1,'操作成功',route('blacklist.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        Redis::zRem('blacklist',$request->client_sign);
        return ajaxReturn(1,'操作成功');
    }
}
