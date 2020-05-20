<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ApistyleRequest extends FormRequest
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
            'api_mark'     => 'required|unique:apistyles,api_mark,'.$request->id,
            'api_name'     => 'required',
        ];
    }
    public function messages(){
        return [
            'api_mark.required'    => '标识不能为空',
            'api_name.required'    => '名称不能为空',
            'api_mark.unique'      => '标识已存在',
        ];
    }
}
