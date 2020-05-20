<?php

namespace App\Http\Admin;

use App\Model\Apistyle;
use App\Model\Apizj;
use App\Model\Syslog;
use App\Model\Upaccount;
use App\Model\Upstyle;
use App\Requests\UpaccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpaccountController extends Controller
{
    public function index()
    {
        $list = Upaccount::with('apistyle')->get();
        return view('Admin.Upaccount.index',compact('list'));
    }

    public function create()
    {
        $upstyle = Upstyle::all();
        $upstyleList[] = $upstyle->keyBy('id')->toArray();
        $upstyleList = json_encode($upstyleList);
        $apistyle = Apistyle::where('status',1)->get();
        return view('Admin.Upaccount.create',compact('upstyle','upstyleList','apistyle'));
    }

    public function edit(Upaccount $upaccount)
    {
        //上游类型
        $upstyle = Upstyle::all();
        $upstyleList[] = $upstyle->keyBy('id')->toArray();
        $upstyleList = json_encode($upstyleList);
        //所有接口类型
        $apistyle = Apistyle::where('status',1)->get();
        //当前接口账户配置的接口类型
        $apizj = Apizj::where('upaccount_id',$upaccount->id)->get();
        $apizj = $apizj->keyBy('apistyle_id')->toArray();

        return view('Admin.Upaccount.edit',compact('upstyle','upstyleList','apistyle','upaccount','apizj'));
    }

    public function store(UpaccountRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $param = [];
        foreach ($request->all() as $k=>$v)
        {
            if (strpos($k, $request->upaccount_mark) !== false) {
                $param[$k] = $v;
            }
        }
        $params['upaccount_mark']  = $request->upaccount_mark;
        $params['upaccount_name']  = $request->upaccount_name;
        $params['upaccount_params']= json_encode($param);

        $ret = Upaccount::create($params);
        //添加上游账户费率配置
        $ret && $this->apizj($request,$ret->id);

        $logs_data = array(
            'module'    => '添加上游账户',
            'content'   => '添加账户【'.$request->upaccount_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('upaccount.index'));
    }

    //上游账户费率配置
    protected function apizj($request, $id)
    {
        $ids = $request->get('jkid'); //接口类型id
        if(!$ids) // 接口类型id为空的时候，删除当前上游账户费率配置
        {
            $result = Apizj::where('upaccount_id',$id)->delete();
        }else{
            // 删除没有选中的接口类型
            Apizj::whereNotIn('apistyle_id',$ids)->where('upaccount_id',$id)->delete();
            foreach ($ids as $v)
            {
                $costfl = 'costfl_'.$v;
                $runfl  = 'runfl_'.$v;
                $minje  = 'minje_'.$v;
                $maxje  = 'maxje_'.$v;
                $todayje= 'todayje_'.$v;
                $status = 'status_'.$v;

                $apizj = Apizj::where('upaccount_id',$id)->where('apistyle_id',$v)->first();
                if($apizj)
                {
                    $apizj->upaccount_id = $id;
                    $apizj->apistyle_id  = $v;
                    $apizj->costfl = $request->$costfl ?? 0;
                    $apizj->runfl  = $request->$runfl ?? 0;
                    $apizj->minje  = $request->$minje ?? 0;
                    $apizj->maxje  = $request->$maxje ?? 0;
                    $apizj->todayje= $request->$todayje ?? 0;
                    $apizj->status  = $request->$status ?? 0;
                    if( $apizj->status == 0)
                    {
                        $apizj->changetime = 0;
                        $apizj->ifchoose = 0;
                    }
                    $result = $apizj->save();
                }else{
                    $data['upaccount_id'] = $id;
                    $data['apistyle_id']  = $v;
                    $data['costfl'] = $request->$costfl ?? 0;
                    $data['runfl']  = $request->$runfl ?? 0;
                    $data['minje']  = $request->$minje ?? 0;
                    $data['maxje']  = $request->$maxje ?? 0;
                    $data['todayje']= $request->$todayje ?? 0;
                    $data['status'] = $request->$status ?? 0;
                    if( $data['status'] == 0)
                    {
                        $data['changetime'] = 0;
                        $data['ifchoose'] = 0;
                    }
                    $result = Apizj::create($data);
                }
            }
        }

        return $result;
    }

    public function update(UpaccountRequest $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $model = Upaccount::findOrFail($request->id);
        $param = [];
        foreach ($request->all() as $k=>$v)
        {
            if (strpos($k, $request->upaccount_mark) !== false) {
                $param[$k] = $v;
            }
        }
        $model->upaccount_mark  = $request->upaccount_mark;
        $model->upaccount_name  = $request->upaccount_name;
        $model->upaccount_params= json_encode($param);
        $ret = $model->save();
        //更新上游账户费率配置
        $ret && $this->apizj($request,$model->id);

        $logs_data = array(
            'module'    => '更新上游账户',
            'content'   => '更新账户【'.$request->upstyle_name.'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturnUrl(1,'操作成功',route('upaccount.index'));
    }

    public function delete(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $ids = $request->ids;
        if (empty($ids)){
            return ajaxReturn(0,'请选择要删除的数据');
        }
        Upaccount::whereIn('id',$ids)->delete();
        Apizj::whereIn('upaccount_id',$ids)->delete();

        $logs_data = array(
            'module'    => '删除上游账户',
            'content'   => '删除商户ID【'.implode(',',$ids).'】',
            'username'  => $admin->username,
            'ip'        => $request->getClientIp(),
            'is_admin'  => 1
        );
        Syslog::create($logs_data);
        return ajaxReturn(1,'操作成功');
    }

    /*
     * 上游账户接口应用
     */
    public function changechoose(Request $request)
    {
        $admin = Auth::user();
        if($admin->status != 1) return ajaxReturn(0,'操作权限已被禁用，请联系超管');

        $id = $request->get('id');
        if (empty($id)) return ajaxReturn(0,'请选择需要切换的选项');
        $apizj = Apizj::findOrFail($id);
        if(!$apizj) return ajaxReturn(0,'请正确选择需要切换的选项');

        $apizj->status = 1;
        $apizj->changetime = time();
        $apizj->ifchoose = 1;
        if($apizj->save())
        {
            Apizj::where('apistyle_id',$apizj->apistyle_id)->where('id', '<>', $apizj->id)->update(['changetime'=>0,'ifchoose'=>0]);
            return ajaxReturn(1,'切换成功');
        }
        return ajaxReturn(0,'切换失败');
    }
}
