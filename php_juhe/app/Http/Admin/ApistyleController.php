<?php

namespace App\Http\Admin;

use App\Model\Apistyle;
use App\Model\Apizj;
use App\Model\Syslog;
use App\Model\Upaccount;
use App\Model\User;
use App\Model\UserRate;
use App\Requests\ApistyleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApistyleController extends Controller
{
    public function index()
    {
        $list = Apistyle::orderBy('id')->get();
        return view('Admin.Apistyle.index',compact('list'));
    }

    public function create()
    {
        return view('Admin.Apistyle.create');
    }

    public function edit(Apistyle $apistyle)
    {
        return view('Admin.Apistyle.edit',compact('apistyle'));
    }

    public function store(ApistyleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');
        $data = array(
            'api_name'  => $request->api_name,
            'api_mark'  => $request->api_mark,
            'status'    => $request->status,
        );
        $apistyle = Apistyle::create($data);
        //默认添加会员费率信息
        $users = User::select('uid')->get();
        foreach ($users as $v)
        {
            $params[] = array(
                'uid'         => $v->uid,
                'apistyle_id' => $apistyle->id,
                'rate'        => 0,
                'upaccount_id'=> 0,
                'status'      => 1,
            );
        }
        if(!empty($params)) UserRate::insert($params);

        $logs_data = array(
            'module'    => '添加接口类型',
            'content'   => '添加接口【'.$request->api_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('apistyle.index'));
    }

    public function update(ApistyleRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $model = Apistyle::findOrFail($request->id);
        $model->api_name  = $request->api_name;
        $model->status    = $request->status;
        $model->api_mark  = $request->api_mark;
        $model->save();
        $logs_data = array(
            'module'    => '更新接口类型',
            'content'   => '更新接口【'.$request->api_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('apistyle.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Apistyle::whereIn('id',$ids)->delete();
        Apizj::whereIn('apistyle_id',$ids)->delete();
        UserRate::whereIn('apistyle_id',$ids)->delete();
        $logs_data = array(
            'module'    => '删除接口类型',
            'content'   => '删除接口ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }

    //接口轮询
    public function round(Apistyle $apistyle)
    {
        //查询配置了相应接口类型的，上游账户
        $apizj = Apizj::where('apistyle_id',$apistyle->id)->select('id','upaccount_id')->get();
        $apizj = $apizj->keyBy('upaccount_id')->toArray();
        $upaccount_ids = array_column($apizj, 'upaccount_id');
        //获取上游账户
        $upaccount = Upaccount::whereIn('id',$upaccount_ids)->select('id','upaccount_name')->get()->toArray();
        return view('Admin.Apistyle.rount',compact('apistyle','upaccount','apizj'));
    }
    //接口轮询更新
    public function roundStore(Request $request)
    {
        $apistyle = Apistyle::findOrFail($request->id);
        $apistyle->is_polling = $request->is_polling;
        if(isset($request->jkid))
        {
            foreach ($request->jkid as $v)
            {
                $power = 'power_'.$v;
                $data[$v] = array(
                    'id'    => $v,
                    'power' => $request->$power ? $request->$power : ''
                );
            }
            $apistyle->polling_ids = serialize($data);
        }else{
            $apistyle->polling_ids = null;
        }
        $apistyle->save();
        //接口类型启用，去掉接口账户的应用
        if($request->is_polling == 1)
        {
            Apizj::where(['apistyle_id'=>$request->id,'ifchoose'=>1])->update(['changetime'=>0,'ifchoose'=>0]);
        }
        return ajaxReturnUrl(1,'更新成功',route('apistyle.index'));
    }
}
