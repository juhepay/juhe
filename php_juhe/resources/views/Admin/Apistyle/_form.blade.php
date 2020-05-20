{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">名称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="api_name" placeholder="请输入名称"  value="{{$apistyle->api_name ?? ''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">标识</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="api_mark" placeholder="请输入标识" value="{{$apistyle->api_mark ?? ''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">状态</label>
    <div class="col-md-4">
        <select name="status" class="form-control flselect">
            <option value="1" @if(isset($apistyle->status) && $apistyle->status == 1) selected @endif>启用</option>
            <option value="0" @if(isset($apistyle->status) && $apistyle->status == 0) selected @endif>禁用</option>
        </select>
    </div>
</div>
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

