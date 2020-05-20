{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">会员账号</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="username"  autocomplete="off" placeholder="请输入会员账号"  value="{{$user->username??''}}"  @if($act == 'add') required @else disabled @endif>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">登录密码</label>
    <div class="col-md-4">
        <input type="password" class="form-control" name="password" placeholder="请输入登录密码"   @if($act == 'add') required @endif>
        *长度6-20位 @if($act == 'edit')，不修改请留空@endif
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">提现密码</label>
    <div class="col-md-4">
        <input type="password" class="form-control" name="save_code" placeholder="请输入提现密码"   @if($act == 'add') required @endif>
        *长度6-20位 @if($act == 'edit')，不修改请留空@endif
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">会员类型</label>
    <div class="col-md-4">
        <select name="group_type" class="form-control">
            <option value="0" @if(isset($user->group_type) && $user->group_type == 0) selected @endif>商户</option>
            <option value="1" @if(isset($user->group_type) && $user->group_type == 1) selected @endif>代理</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">代理id</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="pid" autocomplete="off" placeholder="请输入代理ID"  value="{{$user->pid??''}}">
        * 可留空，如填写代理会员id 例如2020100
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">会员备注</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="remark"  autocomplete="off" placeholder="请输入会员备注"  value="{{$user->remark??''}}">
        * 可留空
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">登录状态</label>
    <div class="col-md-4">
        <select name="status" class="form-control">
            <option value="1" @if(isset($user->status) && $user->status == 1) selected @endif>启用</option>
            <option value="0" @if(isset($user->status) && $user->status == 0) selected @endif>禁用</option>
        </select>
        * 会员禁用登录，一切操作都禁止
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">接单状态</label>
    <div class="col-md-4">
        <select name="is_jd" class="form-control">
            <option value="1" @if(isset($user->is_jd) && $user->is_jd == 1) selected @endif>启用</option>
            <option value="0" @if(isset($user->is_jd) && $user->is_jd == 0) selected @endif>禁用</option>
        </select>
        * 商户禁用接单后，可以正常操作后台，但是不能发起支付
    </div>
</div>
@if($act == 'edit')
<div class="form-group">
    <label class="col-md-2 control-label">Google验证码</label>
    <div class="col-md-4">
        <input type="checkbox" name="reset_code">&nbsp;勾选表示重置Google验证。
    </div>
</div>
@endif
<div class="form-group">
    <div class="col-md-offset-2 col-md-4">
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
        </button>
        <button type="button" class="btn btn-primary jumpbutton">
            <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;返回
        </button>
    </div>
</div>

