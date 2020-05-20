<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'appid'     => 'required',
            'amount'    => 'required|numeric',
            'pay_code'  => 'required',
            'order_no'  => 'required|max:50',
            'notify_url'=> ['required','regex:/(https?|http?):\/\/?/i'],
            'return_url'=> ['nullable','regex:/(https?|http?):\/\/?/i'],
        ];
    }

    public function messages(){
        return [
            'appid.required'        => '请填写APPID',
            'amount.required'       => '请填写金额',
            'amount.numeric'        => '请正确填写金额',
            'pay_code.required'     => '请填支付编码',
            'order_no.required'     => '请填写订单号',
            'notify_url.required'   => '请填写服务器通知地址',
            'notify_url.regex'      => '请正确填写服务器通知地址',
            'return_url.regex'      => '请正确填写同步跳转地址',
            'order_no.max'          => '订单号最大50位'
        ];
    }
}
