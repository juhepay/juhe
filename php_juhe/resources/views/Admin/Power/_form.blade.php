{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">权限名称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="powers_name" placeholder="请输入权限名称" value="{{$power->powers_name??''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">权限标识</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="powers_mark" placeholder="请输入权限标识" value="{{$power->powers_mark??''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">排序</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="powers_sort" placeholder="请输入排序" value="{{$power->powers_sort??''}}" required>
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-2 col-md-4">
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
        </button>
    </div>
</div>

