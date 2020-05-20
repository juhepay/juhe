{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">角色名称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="role_name" placeholder="请输入角色名称" value="{{$role->role_name??''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">权限列表</label>
    <div class="col-md-4">
        <label class="label label-primary">
            <input type="checkbox" class="allpower" name="power_ids[]" value="all" @if(isset($role->power_ids) && $role->power_ids == 'all') checked @endif>全部权限（all）
        </label>
        <hr>
        @foreach($data as $k=>$v)
            @foreach($v as $kk=>$vv)
                <label class="label label-primary">
                    <input type="checkbox" class="listp" name="power_ids[]" @if(isset($role->power_ids) && ($role->power_ids == 'all' || in_array($vv['id'],json_decode($role->power_ids,true)))) checked @endif value="{{ $vv['id'] }}">{{ $vv['powers_name'] }}（{{ $vv['powers_mark'] }}）
                </label>
            @endforeach
            <hr>
        @endforeach
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-2 col-md-4">
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
        </button>
    </div>
</div>

