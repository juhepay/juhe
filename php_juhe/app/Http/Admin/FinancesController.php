<?php

namespace App\Http\Admin;

use App\Exceptions\CustomServiceException;
use App\Model\FundsTurnoverLog;
use App\Model\Syslog;
use App\Model\Tixian;
use App\Model\Upaccount;
use App\Model\User;
use App\Tool\Md5Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancesController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $query = $request->query();
        $model = Tixian::query();
        $where['status'] = 0;
        $title = '未提现订单';
        if(isset($request->status) && $request->status == 1 ){
            $where['status'] = 1;
            $query['status'] = 1;
            $title = '已提现订单';
        }

        if(isset($query['status'])){
            $where['status'] = $query['status'];
        }

        if(isset($request->uid) && $request->uid)
        {
            $where['uid'] = $request->uid;
        }

        if(isset($request->order_no) && $request->order_no)
        {
            $where['order_no'] = $request->order_no;
        }

        if( (isset($request->start_time) && $request->start_time) &&  (isset($request->end_time) && $request->end_time) )
        {
            $model->whereBetween('created_at',[$request->start_time,$request->end_time]);
        }

        $list = $model->where($where)->orderBy('id','desc')->paginate(30);

        $time1 = date('Y-m-d 00:00:00', time());
        $time2 = date('Y-m-d 00:00:00', strtotime("+1 day"));

        $data['today_y'] = Tixian::where('status',1)->whereBetween('created_at',[$time1,$time2])->sum('money');
        $data['today_n'] = Tixian::where('status',0)->whereBetween('created_at',[$time1,$time2])->sum('money');

        $data['count_m'] = Tixian::sum('money');
        $data['count_y'] = Tixian::where('status',1)->sum('money');
        $data['count_f'] = Tixian::where('status',1)->sum('fee');

        $upaccount = Upaccount::select('id','upaccount_name')->get();
        $upaccount = $upaccount->keyBy('id')->toArray();
        return view('Admin.Finances.index',compact('list','query','upaccount','title','data'));
    }

    public function edit(Tixian $tixian)
    {
        $upaccount = Upaccount::all();
        return view('Admin.Finances.edit',compact('tixian','upaccount'));
    }

    public function memo(Tixian $tixian, Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $tixian->remark = $request->memo;
        $tixian->save();
        return ajaxReturn(1,'操作成功');
    }

    public function update(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        if($request->act == 3)//取消提现
        {
            $ids = $request->post('ids');
            if(empty($ids)) return ajaxReturn(0,'请选择需要操作的订单');
            $id = $ids[0];
            $model = Tixian::findOrFail($id);
            $this->cancel($model);
        }elseif($request->act == 1){ //确认提现
            $id = $request->id;
            $model = Tixian::findOrFail($id);
            $upaccount = Upaccount::find($request->upaccount_id);
            if(!$upaccount) return ajaxReturn(0,'请选择代付机构');
            $model->status = 1;
            $model->upaccount_id = $upaccount->id;
            $model->save();
        }

        $logs_data = array(
            'module'    => '审核提现订单',
            'content'   => '提现订单ID【'.$id.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('finances.index'));
    }

    public function notify(Tixian $tixian)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        if(empty($tixian->notify_url)) return ajaxReturn(0,'没有回调地址');

        $user = User::where(['uid'=>$tixian->uid])->first();
        $data = array(
            'appid' => $tixian->uid,
            'order_no'  => $tixian->order_no,
            'amount'    => $tixian->money,
            'fee'       => $tixian->fee,
            'status'    => $tixian->status,
            'body'      => $tixian->card_no,
            'name'      => $tixian->real_name,
            'bank_name' => $tixian->bank_name,
        );
        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($data);
        $data['sign'] = $md5Verify->md5Encrypt($prestr,$user->api_key);
        $ret = CURL($tixian->notify_url,$data);
        if(strtolower($ret) == 'success'){
            return ajaxReturn(1,'通知成功');
        }else{
            return ajaxReturn(0,'通知返回数据：'.htmlentities($ret));
        }
    }

    private function cancel(Tixian $tixian)
    {
        DB::connection('mysql')->transaction(function () use ($tixian) {
            $token = TimeMicroTime();
            redisLock("user:lock:".$tixian->uid,$token,10);
            //查询用户原金额
            $user_info = User::where('uid',$tixian->uid)->select('balance')->first();
            //账户余额增加
            $result = User::where('uid',$tixian->uid)->increment('balance',$tixian->money);
            unlock("user:lock:".$tixian->uid,$token);
            //添加结算信息
            $tixian->status = 3;
            $result && $result = $tixian->save();
            if($result)
            {
                // 资金流水
                $data = array(
                    'uid'           => $tixian->uid,
                    'amount'        => $tixian->money,
                    'before_balance'=> $user_info->balance,
                    'after_balance' => bcadd($user_info->balance,$tixian->money,2),
                    'type'          => 2,
                    'content'       => '取消提现返还：【'.$tixian->money.'元】',
                );
                $result = FundsTurnoverLog::create($data);
            }

            if (!$result) {
                throw new CustomServiceException('取消提现失败');
            }
        }, 1);
        return true;
    }
}
