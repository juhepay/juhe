@extends("Admin.layout")
@section('content')
<h3><span class="current">通道统计</span></h3>
<br>
<div class="row tagtopdiv">
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>交易成功</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['totla_amount'] ?? 0 }}元</h4>
                <h4 class="pull-right">收入</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>代付金额</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{$data['tx_amount'] ?? 0}}元</h4>
                <h4 class="pull-right">支出</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>代付手续费</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['tx_fee'] ?? 0 }}元</h4>
                <h4 class="pull-right">代付手续费</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>通道成本</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['totla_cost_amount'] ?? 0 }}元</h4>
                <h4 class="pull-right">支出</h4>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="" class="form-inline m-b-xs" method="get">
            <div class="form-group">
                <input type="text"  class="form-control" name="uid" placeholder="商户id" autocomplete="off" value="{{ $query['uid'] ?? '' }}" >
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
            <button class="btn btn-primary flushbtn" type="button"><span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新</button>
        </form>
    </div>
</div>
<form action="" method="post" class="ajax-form">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr class="info">
                <th align="center">通道名称</th>
                <th align="center">收款总额</th>
                <th align="center">通道成本</th>
                <th align="center">代理佣金</th>
                <th align="center">平台利润</th>
                <th align="center">发起笔数</th>
                <th align="center">成功笔数</th>
                <th align="center">成功率</th>
                <th align="center">代付笔数</th>
                <th align="center">代付手续费</th>
                <th align="center">代付总金额</th>
                <th align="center">平台余额</th>
            </tr>
            </thead>
            <tbody>
            @if($orders)
                @foreach($orders as $k=>$v)
                    <tr>
                        <td align="center">@if( isset($upaccount[$v['upaccount_id']])) {{$upaccount[$v['upaccount_id']]['upaccount_name']}} @else 接口账户不存在 @endif</td>
                        <td align="center">{{ $v['amount'] ?? 0 }}</td>
                        <td align="center">{{ $v['cost_amount'] ?? 0 }}</td>
                        <td align="center">{{ $v['commission'] ?? 0 }}</td>
                        <td align="center">{{ $v['fee']-$v['cost_amount']-$v['agent_amount'] ?? 0 }}</td>
                        <td align="center">{{ $v['total_count'] ?? 0}}</td>
                        <td align="center">{{ $v['success_count'] ?? 0 }}</td>
                        <td align="center">@if($v['success_count'] == 0 ) 0 @else {{ sprintf('%.2f',$v['success_count']/$v['total_count']*100) }}%  @endif</td>
                        @if( isset($tixian[$v['upaccount_id']]) )
                            <td align="center">{{$tixian[$v['upaccount_id']]['tx_count']}}</td>
                            <td align="center">{{$tixian[$v['upaccount_id']]['tx_fee']}}</td>
                            <td align="center">{{$tixian[$v['upaccount_id']]['tx_money']}}</td>
                        @else
                            <td align="center">0</td>
                            <td align="center">0</td>
                            <td align="center">0</td>
                        @endif
                        <td align="center">
                            @php
                                $tx_money = $tixian[$v['upaccount_id']]['tx_money'] ?? 0;
                                echo $v['amount']-$v['cost_amount']-$tx_money;
                            @endphp

                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="12" align="center">
                        没有数据
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
