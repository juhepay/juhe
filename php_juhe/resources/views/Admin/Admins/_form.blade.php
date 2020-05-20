{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">角色</label>
    <div class="col-md-4">
        <select name="role_id" class="form-control flselect">
            @foreach($role_list as $v)
                <option value="{{$v->id}}" @if(isset($admins->role_id) && $admins->role_id == $v->id) selected @endif >{{$v->role_name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">登录名</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="username" placeholder="请输入用户名"  value="{{$admins->username??''}}"  @if($act == 'add') required @else disabled @endif>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">昵称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="nickname" placeholder="请输入昵称" value="{{$admins->nickname??''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">密码</label>
    <div class="col-md-4">
        <input type="password" class="form-control" name="password" placeholder="请输入密码" @if($act == 'add') required @endif>
        @if($act == 'edit')*不修改请留空@endif
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">状态</label>
    <div class="col-md-4">
        <select name="status" class="form-control flselect">
            <option value="1" @if(isset($admins->status) && $admins->status == 1) selected @endif>启用</option>
            <option value="0" @if(isset($admins->status) && $admins->status == 0) selected @endif>禁用</option>
        </select>
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
    </div>
</div>

