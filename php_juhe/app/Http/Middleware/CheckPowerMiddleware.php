<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomServiceException;
use App\Exceptions\PowerException;
use App\Model\Power;
use App\Model\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPowerMiddleware
{
    /**
     * 权限验证
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin_role = Auth::user()->role_id;
        $role = Role::find($admin_role);
        if($role->power_ids == 'all')
        {
            return $next($request);
        }
        $power_ids  = json_decode($role->power_ids,true);
        $powers     = Power::whereIn('id',$power_ids)->select('powers_mark')->get()->toArray();
        $powers_mark= array_column($powers,'powers_mark');
        $url_name   =  request()->route()->getAction()['as'];
        if(!in_array($url_name,$powers_mark))
        {
            if( $request->expectsJson() )
            {
                return response()->json(['code'=>0,'msg'=>'无权访问'],200,[],256);
            }else{
                return response()->view('500', ['code'=>0,'msg'=>'无权访问'], 400);
            }
        }
        return $next($request);
    }
}
