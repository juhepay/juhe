<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpstyleRequest extends FormRequest
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
            'upstyle_mark'     => 'required|unique:upstyles,upstyle_mark,'.$request->id,
            'upstyle_name'     => 'required',
        ];
    }
    public function messages(){
        return [
            'upstyle_mark.required'    => '类型标识不能为空',
            'upstyle_name.required'    => '类型名称不能为空',
            'upstyle_mark.unique'      => '类型标识已存在',
        ];
    }
}
