<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PowerRequest extends FormRequest
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
            'powers_name'   => 'required|unique:powers,powers_name,'.$request->id,
            'powers_mark'   => 'required|unique:powers,powers_mark,'.$request->id,
            'powers_sort'   => 'required|numeric'
        ];
    }
    public function messages(){
        return [
            'powers_name.required'  => '权限名称不能为空',
            'powers_name.unique'    => '权限名称已存在',
            'powers_mark.required'  => '权限标识不能为空',
            'powers_mark.unique'    => '权限标识已存在',
            'powers_sort.required'  => '排序不能为空',
            'powers_sort.numeric'   => '排序只能填写整数',
        ];
    }
}
