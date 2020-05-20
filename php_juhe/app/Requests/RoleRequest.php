<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RoleRequest extends FormRequest
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
        return [
            'power_ids'   => 'required',
            'role_name'   => 'required|unique:roles,role_name,'.$request->id,
        ];
    }
    public function messages(){
        return [
            'role_name.required'  => '角色名称不能为空',
            'role_name.unique'    => '角色名称已存在',
            'power_ids.required'  => '角色权限必选'
        ];
    }
}
