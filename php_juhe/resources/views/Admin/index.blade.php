@extends("Admin.layout")
@section('content')
    <div class="wrapper wrapper-content">
        <div class="container" style="width:100%">
            <div class="row">
                <div class="col-md-2">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">全部</span>
                            <h5>商户数</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{$data['user_count']}}</h1>
                            <div class="stat-percent font-bold text-success">
                                {{$data['agent_count']}}<i class="fa fa-bolt"></i>
                            </div>
                            <small>代理数</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">全部</span>
                            <h5>会员余额</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">¥ <b>{{ $data['user_balance'] }}</b> 元</h1>
                            <div class="stat-percent font-bold text-success">
                                {{$data['user_gt_balance']}}<i class="fa fa-bolt"></i>
                            </div>
                            <small>余额大于1万</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">总账</span>
                            <h5>总成功资金流水</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1 class="no-margins">¥ {{$data['order_sum']}} 元</h1>
                                </div>
                                <div class="col-md-6">
                                    <h1 class="no-margins">{{$data['order_success_sum']}} 笔</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">总账</span>
                            <h5>总提现金额</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1 class="no-margins">¥ {{$data['tx_sum']}} 元</h1>
                                </div>
                                <div class="col-md-6">
                                    <h1 class="no-margins">¥ {{$data['tx_no_sum']}} 元</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div>
                                <span class="pull-right text-right"></span>
                                <h3 class="font-bold no-margins">最近30天流水</h3>
                            </div>
                            <div class="m-t-sm">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="lineChart" style="width: 100%; height: 260px;">

                                        </div>
                                    </div>
                                    <div class="col-md-4" style="padding-top:2em;">
                                        <ul class="stat-list m-t-lg">
                                            <li>
                                                <h2 class="no-margins" style="font-size:22px;">
                                                    支付成功<b style="color: #e36;">{{ $data['month_success_count'] }}</b>笔
                                                </h2>
                                                <small>近30天订单平均支付率（{{ $data['month_ratio'] }}%）</small>
                                                <div class="progress progress-mini">
                                                    <div class="progress-bar" style="width: {{$data['month_ratio']}}%;"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <h2 class="no-margins" style="font-size:22px;">
                                                    利润<b style="color:#e36;">￥{{$data['sys_amount']}}</b>
                                                </h2>
                                                <small>近30天给代理创造收益￥{{ $data['month_agent_amount'] }}</small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="m-t-md">
                                <small class="pull-right">
                                    <i class="glyphicon glyphicon-refresh"> </i> {{ date('Y-m-d H:i:s') }}更新
                                </small>
                                <small>
                                    <strong>说明：</strong> 仅显示最近30天的流水信息，不包括今天的数据。
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>今日资金统计</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">订单数量</small>
                                    <h4>{{$data['today_order_total_count']}}笔</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">成功订单</small>
                                    <h4>{{$data['today_order_success_count']}}笔</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">今日收款</small>
                                    <h4>￥{{$data['today_order_success_amount']}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">成本金额</small>
                                    <h4>￥{{$data['today_order_cost_amount']}}</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">代理利润</small>
                                    <h4>￥{{$data['today_order_agent_amount']}}</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">商户实收</small>
                                    <h4>￥{{$data['today_order_user_amount']}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">申请提现</small>
                                    <h4>￥{{$data['today_tx_no_sum']}}</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">已经提现</small>
                                    <h4>￥{{$data['today_tx_sum']}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection('content')
@section('script')
    <script src="/static/echarts.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            var myChart = echarts.init(document.getElementById('lineChart'));
            var buffer = JSON.parse('{!!  $order_time_array !!}'); //解析JSON数据
            var dataAxis = [];
            var data = [];
            if (buffer.length > 0) {
                for (i in buffer) {
                    dataAxis.push(buffer[i]['date']);
                    data.push(buffer[i]['totalmoney']);
                }
            }
            var option = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'line'
                    }
                },
                xAxis: [
                    {
                        type: 'category',
                        data: dataAxis,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '金额',
                        type: 'line',
                        smooth: true,
                        data: data,
                    }
                ]
            };
            myChart.setOption(option);
        });
    </script>
@endsection
