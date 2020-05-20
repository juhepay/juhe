<?php

namespace App\Http\Home;

use App\Model\BankCard;
use App\Model\Syslog;
use App\Requests\BankCardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankcardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $list = BankCard::where('uid',$user->uid)->orderBy('id','desc')->get();
        return view('Home.BankCard.index',compact('list'));
    }

    public function create()
    {
        return view('Home.BankCard.create');
    }

    public function edit(BankCard $bankcard)
    {
        $user = Auth::user();
        if($user->uid != $bankcard->uid) return ajaxReturn(0,'账户不存在');
        return view('Home.Bankcard.edit',compact('bankcard'));
    }

    public function store(BankCardRequest $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $params['real_name']= $request->real_name;
        $params['card_no']  = $request->card_no;
        $params['bank_name']= $request->bank_name;
        $params['uid']      = $user->uid;
        BankCard::create($params);

        $logs_data = array(
            'module'    => '添加提现账户',
            'content'   => '添加银行卡【'.$request->card_no.'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('member.bankcard.index'));
    }

    public function update(BankCardRequest $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $model = BankCard::where(['id'=>$request->id,'uid'=>$user->uid])->first();
        if(!$model) return ajaxReturn(0,'账户不存在');
        $model->real_name = $request->real_name;
        $model->card_no   = $request->card_no;
        $model->bank_name = $request->bank_name;
        $model->save();

        $logs_data = array(
            'module'    => '更新提现账户',
            'content'   => '更新银行卡【'.$request->card_no.'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('member.bankcard.index'));
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        if($user->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系客服');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        BankCard::whereIn('id',$ids)->where('uid',$user->uid)->delete();
        $logs_data = array(
            'module'    => '删除提现账户',
            'content'   => '删除提现账户ID【'.implode(',',$ids).'】',
            'username'  => $user->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 0
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
