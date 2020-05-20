@extends("Admin.layout")
@section('content')
<h3><span class="current">{{ $title }}</span></h3>
<br>
<div class="row tagtopdiv">
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">当日</span>
                    <h5>未支付</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['today_n'] }}元</h4>
                <h4 class="pull-right">当日未支付</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">当日</span>
                    <h5>已支付</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{$data['today_y']}}元</h4>
                <h4 class="pull-right">当日已支付</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>总额</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['count_m'] }}元</h4>
                <h4 class="pull-right">总额</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>已支付</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['count_y'] }}元</h4>
                <h4 class="pull-right">总支付</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>手续费</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{$data['count_f']}}元</h4>
                <h4 class="pull-right">总手续费</h4>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline" action="" method="get">
            <div class="form-group">
                <input type="text" class="form-control" name="uid" autocomplete="off" placeholder="会员id" value="{{ $query['uid'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="order_no" autocomplete="off" placeholder="订单号" value="{{ $query['order_no'] ?? '' }}">
            </div>
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="0" @if(isset($query['status']) && $query['status'] == 0) selected @endif>未支付</option>
                    <option value="1" @if(isset($query['status']) && $query['status'] == 1) selected @endif>已支付</option>
                    <option value="3" @if(isset($query['status']) && $query['status'] == 3) selected @endif>已取消</option>
                </select>
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
            <button class="btn btn-primary flushbtn" type="button">
                <span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新
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
            <th align="center">会员ID</th>
            <th align="center">备注</th>
            <th align="center">订单号</th>
            <th align="center">提现金额</th>
            <th align="center">状态</th>
            <th align="center">时间</th>
            <th align="center">机构</th>
            <th align="center">提现信息</th>
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
                    <td align="center" class="J_tx_memo" data-url="{{ route('finances.memo',[$v->id]) }}" style="cursor:pointer;">@if($v->remark) <span class="label label-danger">{{$v->remark}}</span> @else [ 添加备注 ] @endif</td>
                    <td align="center">{{ $v->order_no }}</td>
                    <td align="center">{{ $v->money }}</td>
                    <td align="center">@if($v->status == 0) 未支付 @elseif($v->status == 1) 已支付 @elseif($v->status == 2) 冻结 @elseif($v->status == 3) 已取消 @else 状态错误 @endif</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">{{ $upaccount[$v->upaccount_id]['upaccount_name'] ?? '' }}</td>
                    <td align="center">{{ $v->real_name }} {{ $v->bank_name }} {{ $v->card_no }}</td>
                    <td align="center">
                        @if($v->status == 0)
                        <a href="{{ route('finances.edit',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-transfer"></span>&nbsp;审核</a>
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('finances.update',[3]) }}" class="btn btn-primary ajax-delete">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;取消</a>
                        @endif
{{--                        @if($v->notify)--}}
                            <a href="javascript:;"  data-url="{{ route('finances.notify',[$v->id]) }}" class="btn btn-info notice">
                                <span class="glyphicon glyphicon-envelope"></span>&nbsp;通知</a>
{{--                        @endif--}}
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

        $('.J_tx_memo').on('click', function () {
            let $this = $(this);
            layer.prompt(function(val, index){
                $.post($this.data('url'), {memo:val.trim()}, function (ret) {
                    if(ret.code==1) {
                        $this.html('<span class="label label-danger">' + val + '</span>');
                    }
                    layer.close(index);
                },'json');
            });
        });

        $('.notice').click(function () {
            let url = $(this).attr('data-url');
                $.get(url,function (result) {
                    if (result.code==1){
                        layer.msg(result.msg,{icon:6})
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
        });
    </script>
@endsection
