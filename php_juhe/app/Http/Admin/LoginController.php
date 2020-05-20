<?php

namespace App\Http\Admin;

use App\Model\Admin;
use App\Model\Syslog;
use App\Requests\LoginRequest;
use App\Tool\GoogleAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use ThrottlesLogins;
    protected $username = 'username';
    protected $decayMinutes = '5';
    protected $maxAttempts  = 3;

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) return redirect( route('admin.index') );
        return view('Admin.login');
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username)).'|'.$request->ip();
    }

    public function login(LoginRequest $request)
    {
        if($this->hasTooManyLoginAttempts($request)){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'错误次数过多，请稍后再来！');
        }

        $admin = Admin::where('username',$request->username)->first();
        if(!$admin){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'用户名或密码错误');
        }
        if($admin->status != 1){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'该账号未激活');
        }

        if(!password_verify($request->password, $admin->password)){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'用户名或密码错误');
        }

        if( $admin->google_key ) {
            $google = app(GoogleAuthenticator::class);
            if(!$google->verifyCode($admin->google_key, $request->auth_code)){
                $this->incrementLoginAttempts($request);
                return ajaxReturn(0,'谷歌验证失败');
            }
        }
        $this->clearLoginAttempts($request);
        Auth::guard('admin')->login($admin);
        $admin->last_ip = $request->getClientIp();

        $logs_data = array(
            'module'    => '管理员登录',
            'content'   => '管理员【'.$admin->username.'】登录',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'登录成功',route('admin.index'));
    }

    public function dropout(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->forget(Auth::guard('admin')->getName());
        session()->regenerate();
        return redirect()->route('admin.login');
    }

}
