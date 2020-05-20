@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;提现记录
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 col-xs-6">
                        <div class="panel">
                            <div class="panel-body" style="background:#eee;">
                                <h4 class="pull-left">今日提现(元)</h4>
                                <h4 class="pull-right text-danger">￥{{$data['today']}}元</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div class="panel">
                            <div class="panel-body" style="background:#eee;">
                                <h4 class="pull-left">昨日提现(元)</h4>
                                <h4 class="pull-right text-danger">￥{{$data['yesterday']}}元</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div class="panel">
                            <div class="panel-body" style="background:#eee;">
                                <h4 class="pull-left">总提现金额(元)</h4>
                                <h4 class="pull-right text-danger">￥{{$data['count']}}元</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="" class="form-inline m-b-xs" method="get">
            <div class="form-group">
                <input type="text" autocomplete="off" placeholder="订单号" name="order_no" class="form-control" value="{{ $query['order_no'] ?? '' }}">
            </div>
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="" @if(isset($query['status']) && $query['status'] == '') selected @endif>所有状态</option>
                    <option value="0" @if(isset($query['status']) && $query['status'] == 0) selected @endif>未支付</option>
                    <option value="1" @if(isset($query['status']) && $query['status'] == 1) selected @endif>已支付</option>
                    <option value="3" @if(isset($query['status']) && $query['status'] == 3) selected @endif>已取消</option>
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
            <button type="submit" class="explode btn btn-primary"><i class="fa fa-download"></i>&nbsp;导出</button>
        </form>
        <div class="table-responsive mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th align="center">订单号</th>
                        <th align="center">提现金额</th>
                        <th align="center">手续费</th>
                        <th align="center">状态</th>
                        <th align="center">提现信息</th>
                        <th align="center">申请时间</th>
                    </tr>
                </thead>
                <tbody>
                @if($list)
                    @foreach($list as $k=>$v)
                        <tr id="tr{{$v->id}}">
                            <td align="center">{{ $v->order_no }}</td>
                            <td align="center">{{ $v->money }}</td>
                            <td align="center">{{ $v->fee }}</td>
                            <td align="center">@if($v->status == 1) 已支付 @elseif($v->status == 0) 未支付 @elseif($v->status == 3) 已取消  @endif</td>
                            <td align="center">{{ $v->real_name }} {{ $v->bank_name }} {{ $v->card_no }}</td>
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
