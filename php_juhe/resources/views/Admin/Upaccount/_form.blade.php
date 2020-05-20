{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">上游类型</label>
    <div class="col-md-4">
        <select name="upaccount_mark" class="form-control upstyle">
            @foreach($upstyle as $k=>$v)
                <option @if(isset($upaccount) && $upaccount->upaccount_mark == $v['upstyle_mark'] ) selected @endif tid="{{ $v['id'] }}" value="{{ $v['upstyle_mark'] }}">{{$v['upstyle_name']}}</option>
            @endforeach
        </select>
    </div>
    <a href="{{ route('upstyle.create') }}">添加上游类型</a>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">账户名称</label>
    <div class="col-md-4">
        <input type="text" name="upaccount_name" value="{{ $upaccount->upaccount_name ?? '' }}" required placeholder="请输入账户名称" class="form-control" >
    </div>
</div>
<div class="params"></div>
<div class="form-group">
    <table class="table table-hover">
        <tbody>
        <tr>
            <th>选项</th>
            <th>接口名称</th>
            <th>成本费率(例如10%，这里输入10)</th>
            <th>运营费率(例如10%，这里输入10)</th>
            <th>单笔最小限额(0不限额)</th>
            <th>单笔最大限额(0不限额)</th>
            <th>账户单日限额(0不限额)</th>
            <th>开关</th>
        </tr>
        @foreach($apistyle as $k=>$v)
            <tr>
                <td><input @if( isset($apizj) && isset($apizj[$v['id']]) ) checked @endif type="checkbox"  class="checkbox" name="jkid[]" value="{{ $v['id'] }}"></td>
                <td>{{ $v['api_name'] }}</td>
                <td><input type="text" class="form-control" name="costfl_{{ $v['id'] }}" @if( isset($apizj) && isset($apizj[$v['id']]) ) value="{{ floatval($apizj[$v['id']]['costfl']) }} "@endif></td>
                <td><input type="text" class="form-control" name="runfl_{{ $v['id'] }}" @if( isset($apizj) && isset($apizj[$v['id']]) ) value="{{ floatval($apizj[$v['id']]['runfl']) }} "@endif></td>
                <td><input type="text" class="form-control" name="minje_{{ $v['id']}}" @if( isset($apizj) && isset($apizj[$v['id']]) ) value="{{ $apizj[$v['id']]['minje'] }} "@endif></td>
                <td><input type="text" class="form-control" name="maxje_{{ $v['id'] }}" @if( isset($apizj) && isset($apizj[$v['id']]) ) value="{{ $apizj[$v['id']]['maxje'] }} "@endif></td>
                <td><input type="text" class="form-control" name="todayje_{{ $v['id'] }}" @if( isset($apizj) && isset($apizj[$v['id']]) ) value="{{ $apizj[$v['id']]['todayje'] }} "@endif></td>
                <td>
                    <select name="status_{{ $v['id'] }}" class="form-control">
                        <option value="1" @if( isset($apizj) && isset($apizj[$v['id']]) && $apizj[$v['id']]['status'] == 1 ) selected @endif >开启</option>
                        <option value="0" @if( isset($apizj) && isset($apizj[$v['id']]) && $apizj[$v['id']]['status'] == 0 ) selected @endif>关闭</option>
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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

