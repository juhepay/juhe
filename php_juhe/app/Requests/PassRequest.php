<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PassRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        if($request->style == 1 || $request->style == 2)
        {
            return [
                'password'              => 'required|confirmed|between:6,20',
                'passwordy'             => 'required',
            ];
        }else{
            return [];
        }
    }
    public function messages(){
        return [
            'passwordy.required'    => '原密码不能为空',
            'password.required'     => '新密码不能为空',
            'password.confirmed'    => '两次密码不相同',
            'password.between'      => '密码长度6-20位',
        ];
    }
}
