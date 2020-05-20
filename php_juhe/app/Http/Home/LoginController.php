<?php

namespace App\Http\Home;

use App\Model\Syslog;
use App\Model\User;
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
        $admin = Auth::guard('user')->user();
        if ($admin) return redirect( route('member.index') );
        return view('Home.login');
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

        $user = User::where('username',$request->username)->first();
        if(!$user){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'用户名或密码错误');
        }
        if($user->status != 1){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'该账号未激活');
        }

        if(!password_verify($request->password, $user->password)){
            $this->incrementLoginAttempts($request);
            return ajaxReturn(0,'用户名或密码错误');
        }

        if( $user->google_key ) {
            $google = app(GoogleAuthenticator::class);
            if(!$google->verifyCode($user->google_key, $request->auth_code)){
                $this->incrementLoginAttempts($request);
                return ajaxReturn(0,'谷歌验证失败');
            }
        }
        $this->clearLoginAttempts($request);
        Auth::guard('user')->login($user);
        $user->last_ip = $request->getClientIp();

        $logs_data = array(
            'module'    => '会员登录',
            'content'   => '会员【'.$user->username.'】登录',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'登录成功',route('member.index'));
    }

    public function dropout(Request $request)
    {
        Auth::guard('user')->logout();
        session()->forget(Auth::guard('user')->getName());
        session()->regenerate();
        return redirect()->route('member.login');
    }

}
