<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required',
            'captcha'  => 'required|captcha',
        ];
    }
    public function messages(){
        return [
            'username.required'  =>  '用户名不能为空',
            'captcha.required'   =>  '验证码不能为空',
            'captcha.captcha'    =>  '请输入正确的验证码',
        ];
    }
}
