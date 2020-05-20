<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OtherPayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'merchant'  => 'required',
            'amount'    => 'required|numeric',
            'card_no'   => 'required',
            'order_no'  => 'required|max:30',
            'notify_url'=> ['required','regex:/(https?|http?):\/\/?/i'],
        ];
    }

    public function messages(){
        return [
            'merchant.required'     => '请填写APPID',
            'amount.required'       => '请填写金额',
            'amount.numeric'        => '请正确填写金额',
            'card_no.required'      => '请填写银行卡号',
            'order_no.required'     => '请填写订单号',
            'notify_url.required'   => '请填写服务器通知地址',
            'notify_url.regex'      => '请正确填写服务器通知地址',
            'order_no.max'          => '订单号最大30位'
        ];
    }
}
