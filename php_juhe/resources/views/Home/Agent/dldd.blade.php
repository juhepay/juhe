@extends("Home.layout")
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <em class="fa fa-list"></em>&nbsp;商户交易记录
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4 col-xs-6">
                            <div class="panel">
                                <div class="panel-body" style="background:#eee;">
                                    <h4 class="pull-left">订单总金额(元)</h4>
                                    <h4 class="pull-right text-danger">￥{{$data['amount'] ?? 0}} 元</h4>
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
                    </select>
                </div>
                <div class="form-group">
                    <select name="api_style" class="form-control">
                        <option value=""  @if(!isset($query['api_style']) || (isset($query['api_style']) && $query['api_style'] == '')) selected @endif>支付类型</option>
                        @foreach($apistyle as $v)
                            <option value="{{ $v['api_mark'] }}"  @if(isset($query['api_style']) && $query['api_style'] == $v['api_mark']) selected @endif>{{ $v['api_name'] }}</option>
                        @endforeach
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
                        <th align="center">商户号</th>
                        <th align="center">订单号</th>
                        <th align="center">实付金额</th>
                        <th align="center">收益金额</th>
                        <th align="center">状态</th>
                        <th align="center">添加时间</th>
                        <th align="center">支付时间</th>
                        <th align="center">通道</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($list)
                        @foreach($list as $k=>$v)
                            <tr id="tr{{$v->id}}">
                                <td align="center">{{$v->uid}}</td>
                                <td align="center">{{substr_replace($v->order_no,"",strpos($v->order_no,(string)$v->uid),strlen($v->uid)) }}</td>
                                <td align="center">{{ $v->amount }}</td>
                                <td align="center">{{ $v->user_amount }}</td>
                                <td align="center">@if($v->status == 1) <span style="color:green;">已支付</span> @elseif($v->status == 0) 未支付 @else 错误  @endif</td>
                                <td align="center">{{ $v->created_at }}</td>
                                <td align="center">{{ $v->paytime }}</td>
                                <td align="center">{{ $apistyle[$v->apistyle_id]['api_name'] ?? '' }}</td>
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
