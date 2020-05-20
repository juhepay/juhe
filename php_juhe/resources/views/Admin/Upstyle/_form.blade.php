{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">类型名称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="upstyle_name" placeholder="请输入类型名称"  value="{{$upstyle->upstyle_name ?? ''}}" required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">英文标识</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="upstyle_mark" placeholder="请输入英文标识" value="{{$upstyle->upstyle_mark ?? ''}}" required>
        *唯一标识，对接方法及命名需要一致
    </div>
</div>
<table class="table table-hover">
    <tbody>
    <tr>
        <th>字段名称</th>
        <th>英文标识</th>
        <th>类型</th>
        <th>默认值</th>
        <th>操作</th>
    </tr>
    @if(isset($upstyle->params))
        @foreach($upstyle->params as $v)
            <tr>
                <td><input type="text" class="form-control" name="paramstitle[]" value="{{ $v['paramstitle']}}" ></td>
                <td><input type="text" class="form-control" name="paramsen[]" value="{{ $v['paramsen']}}"></td>
                <td>
                    <select name="paramsinput[]" id="" class="form-control">
                        <option value="text" @if($v['paramsinput'] == 'text') selected @endif>文本text</option>
                        <option value="select" @if($v['paramsinput'] == 'select') selected @endif>选择框select</option>
                        <option value="textarea" @if($v['paramsinput'] == 'textarea') selected @endif>多行文本textarea</option>
                    </select>
                </td>
                <td><input type="text" class="form-control" name="paramsvalue[]" value="{{ $v['paramsvalue']}}" ></td>
                <td><a href="javascript:;" class="deletehang">删除</a></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td><input type="text" class="form-control" name="paramstitle[]" value="{{ $apistyle->stylename ?? old('stylename') }}" ></td>
            <td><input type="text" class="form-control" name="paramsen[]" value="{{ $apistyle->style_make ?? old('style_make') }}"></td>
            <td>
                <select name="paramsinput[]" id="" class="form-control">
                    <option value="text">文本text</option>
                    <option value="select">选择框select</option>
                    <option value="textarea">多行文本textarea</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="paramsvalue[]" ></td>
            <td><a href="javascript:;" class="deletehang">删除</a></td>
        </tr>
    @endif
    <tr>
        <td colspan="5" align="center">
            <input type="button" class="btn addbutton" name="addbutton" value="新增一行">
        </td>
    </tr>
    </tbody>
</table>
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

