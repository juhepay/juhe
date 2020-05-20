<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpaccountRequest extends FormRequest
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
        return [
            'upaccount_name'     => 'required|unique:upaccounts,upaccount_name,'.$request->id,
            'upaccount_mark'     => 'required',
        ];
    }
    public function messages(){
        return [
            'upaccount_name.required'    => '账户名称不能为空',
            'upaccount_mark.required'    => '上游类型不能为空',
            'upaccount_name.unique'      => '账户名称已存在',
        ];
    }
}
