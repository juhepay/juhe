<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class ManyErrorLockService
{
    use ThrottlesLogins;
    protected $username = 'username';
    protected $decayMinutes = '5';
    protected $maxAttempts  = 3;

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username)).'|'.$request->ip();
    }

    /**
     * 判断是否超过限制次数
     * @param Request $request
     * @return bool
     */
    public function hasTooManyActionAttempts(Request $request)
    {
        return $this->hasTooManyLoginAttempts($request);
    }

    /**
     * 错误自增次数
     * @param Request $request
     */
    public function incrementActionAttempts(Request $request)
    {
         $this->incrementLoginAttempts($request);
    }

    public function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }
}
