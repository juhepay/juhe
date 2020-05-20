@extends("Admin.layout")
@section('content')
<h3><span class="current">系统日志</span></h3>
<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline" action="" method="get">
            <div class="form-group">
                <input type="text" class="form-control" name="username" autocomplete="off" placeholder="用户名" value="{{ $query['username'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control start_time" name="start_time" autocomplete="off" placeholder="开始时间" value="{{ $query['start_time'] ?? '' }}">
                -
                <input type="text" class="form-control end_time" name="end_time" autocomplete="off" placeholder="结束时间" value="{{ $query['end_time'] ?? '' }}">
            </div>
            <div class="form-group">
                <select name="is_admin" class="form-control">
                    <option value="">所有状态</option>
                    <option value="0" @if(isset($query['is_admin']) && $query['is_admin'] == 0) selected @endif>用户</option>
                    <option value="1" @if(isset($query['is_admin']) && $query['is_admin'] == 1) selected @endif>管理员</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span>
                &nbsp;立即查询
            </button>
        </form>
    </div>
</div>
<form action="" method="post" class="ajax-form">
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <td colspan="8">
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('syslog.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary flushbtn" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新
                </button>
            </td>
        </tr>
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">ID</th>
            <th align="center">用户名</th>
            <th align="center">操作模块</th>
            <th align="center">内容</th>
            <th align="center">时间</th>
            <th align="center">ip</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->id }}</td>
                    <td align="center">{{ $v->username }}</td>
                    <td align="center">{{ $v->module }}</td>
                    <td align="center">{{ $v->content }}</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">{{ $v->ip }}</td>
                    <td align="center">
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('syslog.delete') }}" class="btn btn-primary ajax-delete">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;删除</a>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="9">
                no data.
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
</form>
@endsection('content')
@section('script')
    <script src="/static/laydate/laydate.js" type="text/javascript"></script>
    <script>
        //时间选择器
        laydate.render({
            elem: '.start_time'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '.end_time'
            ,type: 'datetime'
        });
    </script>
@endsection
