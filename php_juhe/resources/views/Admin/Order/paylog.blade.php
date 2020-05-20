@extends("Admin.layout")
@section('content')
<h3><span class="current">接口日志管理</span></h3>
<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline" action="" method="get">
            <div class="form-group">
                <input type="text" class="form-control" name="uid" autocomplete="off" placeholder="商户id" size="10" value="{{ $query['uid'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="order_no" autocomplete="off" placeholder="订单号" value="{{ $query['order_no'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control start_time" name="start_time" autocomplete="off" placeholder="开始时间" value="{{ $query['start_time'] ?? '' }}">
                -
                <input type="text" class="form-control end_time" name="end_time" autocomplete="off" placeholder="结束时间" value="{{ $query['end_time'] ?? '' }}">
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
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">ID</th>
            <th align="center">商户ID</th>
            <th align="center">订单号</th>
            <th align="center">订单金额</th>
            <th align="center">接口类型</th>
            <th align="center">结果</th>
            <th align="center">时间</th>
            <th align="center">IP</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->id }}</td>
                    <td align="center">{{ $v->uid }}</td>
                    <td align="center">{{ $v->order_no }}</td>
                    <td align="center">{{ $v->amount }}</td>
                    <td align="center">{{ $v->pay_code }}</td>
                    <td align="center" style="max-width:200px;overflow:auto;">{{ $v->result }}</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">{{ $v->ip }}</td>
                    <td align="center">
                        <a href="javascript:;" class="btn btn-danger ajax-delete" data-id="{{ $v->id }}" data-url="{{ route('paylog.delete') }}">
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
    @include('_page')
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
