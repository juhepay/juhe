<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WithdrawRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'bankcard'      => 'required',
            'money'         => 'required|integer',
            'password'      => 'required',
            'google_code'   => 'required',
        ];
    }

    public function messages(){
        return [
            'bankcard.required'     => '银行卡必选',
            'money.required'        => '请填写提现金额',
            'money.integer'         => '提现金额请填写整数',
            'password.required'     => '请填写提现密码',
            'google_code.required'  => '请填写谷歌验证码',
        ];
    }
}
