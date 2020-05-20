<?php

namespace App\Http\Admin;

use App\Model\BankCard;
use App\Model\Syslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankcardController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $model = BankCard::query();
        $where = [];
        if(isset($query['uid'])){
            $where['uid'] = $query['uid'];
        }

        if(isset($query['card_no'])){
            $where['card_no'] = $query['card_no'];
        }
        $list = $model->where($where)->orderBy('id','asc')->paginate(30);
        return view('Admin.Bankcard.index',compact('list','query'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        BankCard::whereIn('id',$ids)->delete();
        $logs_data = array(
            'module'    => '删除提现卡号',
            'content'   => '删除提现卡号员ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }
}
