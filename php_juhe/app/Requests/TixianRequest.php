<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TixianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'bankcard_id'   => 'required',
            'money'         => 'required',
            'save_code'     => 'required',
            'auth_code'     => 'required'
        ];
    }
    public function messages(){
        return [
            'bankcard_id.required'  => '银行卡不能为空',
            'money.required'        => '提现金额不能为空',
            'save_code.required'    => '提现密码不能为空',
            'auth_code.required'    => '谷歌验证码不能为空',
        ];
    }
}
