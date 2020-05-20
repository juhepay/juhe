<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BankCardRequest extends FormRequest
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
            'real_name'   => 'required',
            'card_no'     => 'required',
            'bank_name'   => 'required'
        ];
    }
    public function messages(){
        return [
            'real_name.required'  => '账户实名不能为空',
            'card_no.required'    => '银行卡号不能为空',
            'bank_name.required'  => '银行名称不能为空',
        ];
    }
}
