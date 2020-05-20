@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;资金变动
        </div>
    </div>
    <div class="panel-body">
        <form action="" class="form-inline m-b-xs" method="get">
            <div class="form-group">
                <select name="type" class="form-control">
                    <option value="" @if(isset($query['type']) && $query['type'] == '') selected @endif>所有状态</option>
                    <option value="1" @if(isset($query['type']) && $query['type'] == 1) selected @endif>充值</option>
                    <option value="2" @if(isset($query['type']) && $query['type'] == 2) selected @endif>提现</option>
                </select>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control start_time" name="start_time" placeholder="开始时间" value="{{ $query['start_time'] ?? '' }}" autocomplete="off">
                </div>
                -
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control end_time" name="end_time" placeholder="结束时间" value="{{ $query['end_time'] ?? '' }}" autocomplete="off">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;立即查询</button>
        </form>
        <div class="table-responsive mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th align="center">类型</th>
                        <th align="center">变动前余额</th>
                        <th align="center">变动金额</th>
                        <th align="center">变动后余额</th>
                        <th align="center">内容</th>
                        <th align="center">时间</th>
                    </tr>
                </thead>
                <tbody>
                @if($list)
                    @foreach($list as $k=>$v)
                        <tr>
                            <td align="center">@if($v->type == 1) 充值 @elseif($v->type == 2) 提现 @else 错误 @endif</td>
                            <td align="center">{{ $v->before_balance }}</td>
                            <td align="center">{{ $v->amount }}</td>
                            <td align="center">{{ $v->after_balance }}</td>
                            <td align="center">{{ $v->content }}</td>
                            <td align="center">{{ $v->created_at }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            no data.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            @include('_page')
        </div>
    </div>
</div>
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
