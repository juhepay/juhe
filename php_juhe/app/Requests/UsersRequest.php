<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UsersRequest extends FormRequest
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


    public function rules(Request $request)
    {
        if(isset($request->id) && $request->id ){
            return [
                'username'     => 'nullable|unique:users,username,'.$request->id,
                'password'     => 'nullable|between:6,20',
                'save_code'    => 'nullable|between:6,20',
                'group_type'   => 'required'
            ];
        }else{
            return [
                'username'     => 'required|unique:admins,username,'.$request->id,
                'password'     => 'required|between:6,20',
                'save_code'    => 'required|between:6,20',
                'group_type'   => 'required'
            ];
        }

    }
    public function messages(){
        return [
            'username.required'    => '登录名不能为空',
            'password.required'    => '登录密码不能为空',
            'save_code.required'   => '提现密码不能为空',
            'group_type.required'  => '会员类型不能为空',
            'username.unique'      => '用户名已存在',
            'password.between'     => '登录密码长度6-20位',
            'group_type.between'   => '提现密码长度6-20位',
        ];
    }
}
