<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdminsRequest extends FormRequest
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
                'username'     => 'nullable|unique:admins,username,'.$request->id,
                'password'     => 'nullable|between:6,20',
                'nickname'     => 'required|between:3,10',
                'role_id'      => 'required'
            ];
        }else{
            return [
                'username'     => 'required|unique:admins,username,'.$request->id,
                'password'     => 'required|between:6,20',
                'nickname'     => 'required|between:3,10',
                'role_id'      => 'required'
            ];
        }

    }
    public function messages(){
        return [
            'username.required'    => '登录名不能为空',
            'password.required'    => '登录密码不能为空',
            'nickname.required'    => '昵称不能为空',
            'role_id.required'     => '用户所属角色不能为空',
            'username.unique'      => '用户名已存在',
            'password.between'     => '登录密码长度6-20位',
            'nickname.between'     => '昵称长度3-10位',
        ];
    }
}
