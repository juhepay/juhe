<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EkofapyRequest extends FormRequest
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
            'order_no'  => 'required|max:50',
            'notify_url'=> ['required','regex:/(https?|http?):\/\/?/i'],
            'body'      => 'required',
            'name'      => 'required',
            'bank_name' => 'required',
        ];
    }

    public function messages(){
        return [
            'appid.required'        => '请填写商户ID',
            'amount.required'       => '请填写金额',
            'amount.numeric'        => '请正确填写金额',
            'notify_url.required'   => '请填写服务器通知地址',
            'notify_url.regex'      => '请正确填写服务器通知地址',
            'order_no.required'     => '请填写订单号',
            'order_no.max'          => '订单号最大50位',
            'body.required'         => '请填收款人的账户',
            'name'                  => '请填写收款人的开户名',
            'bank_name'             => '请填写收款人的开户行'
        ];
    }
}
