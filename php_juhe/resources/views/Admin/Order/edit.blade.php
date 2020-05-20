@extends("Admin.layout")
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <em class="fa fa-list"></em>&nbsp;订单详情
            </div>
        </div>
        <div class="panel-body">
            <form method="post" action="" class="layui-form form-container form-horizontal form-ajax">
                <div class="form-group">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                                <tr class="info">
                                    <td>商户ID</td>
                                    <td>{{$order->uid}}</td>
                                </tr>
                                <tr class="info">
                                    <td>订单号</td>
                                    <td>{{$order->order_no}}</td>
                                </tr>
                                <tr class="info">
                                    <td>订单金额</td>
                                    <td>{{$order->amount}}</td>
                                </tr>
                                <tr class="info">
                                    <td>手续费</td>
                                    <td>{{$order->fee}}</td>
                                </tr>
                                <tr class="info">
                                    <td>代理佣金</td>
                                    <td>{{$order->agent_amount}}</td>
                                </tr>
                                <tr class="info">
                                    <td>通道编码</td>
                                    <td>{{$order->api_style}}</td>
                                </tr>
                                <tr class="info">
                                    <td>同步通知地址</td>
                                    <td>{{$order->fj['return_url']}}</td>
                                </tr>
                                <tr class="info">
                                    <td>异步通知地址</td>
                                    <td>{{$order->fj['notify_url']}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-body">
            <div class="panel-heading">代理佣金</div>
            <div class="form-group">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tbody>
                        <tr class="info">
                            <td>ID</td>
                            <td>代理ID</td>
                            <td>分销等级</td>
                            <td>佣金收益</td>
                            <td>代理费率</td>
                            <td>添加时间</td>
                        </tr>
                        @foreach($order_agent as $v)
                            <tr class="info">
                                <td>{{$v->id}}</td>
                                <td>{{$v->agent}}</td>
                                <td>{{$v->level}}</td>
                                <td>{{$v->money}}</td>
                                <td>{{floatval($v->rate)}}%</td>
                                <td>{{$v->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection('content')
@section('script')
    <script>

    </script>
@endsection
