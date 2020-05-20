@extends("Admin.layout")
@section('content')
<h3><span class="current">{{ $title }}</span></h3>
<br>
<div class="row tagtopdiv">
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>订单总额</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['amount'] ?? 0 }}元</h4>
                <h4 class="pull-right">收入</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>代理收益</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{$data['agent_amount'] ?? 0}}元</h4>
                <h4 class="pull-right">收入</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>平台收益</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['sys_amount'] ?? 0 }}元</h4>
                <h4 class="pull-right">收入</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>通道成本</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['cost_amount'] ?? 0 }}元</h4>
                <h4 class="pull-right">支出</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>手续费</h5>
                </div>
                <h4 class="pull-left text-danger">￥{{ $data['fee'] ?? 0 }}元</h4>
                <h4 class="pull-right">收入</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6 ">
        <div class="panel">
            <div class="panel-body">
                <div class="ibox-title">
                    <span class="label label-success pull-right">全部</span>
                    <h5>成功率</h5>
                </div>
                <h4 class="pull-left text-danger">{{$data['radio'] ?? 0}}%</h4>
                <h4 class="pull-right">成功{{$data['success_count']}}笔/发起{{$data['count']}}笔</h4>
            </div>
        </div>
    </div>
</div>
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
                <input type="text" class="form-control" name="client_sign" autocomplete="off" placeholder="客户端标识" value="{{ $query['client_sign'] ?? '' }}">
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
                <select name="upaccount_id" class="form-control">
                    <option value=""  @if(!isset($query['upaccount_id']) || (isset($query['upaccount_id']) && $query['upaccount_id'] == '')) selected @endif>通道账号</option>
                    @foreach($upaccount as $v)
                        <option value="{{ $v['id'] }}"  @if(isset($query['upaccount_id']) && $query['upaccount_id'] == $v['id']) selected @endif>{{ $v['upaccount_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value=""  @if(!isset($query['status']) || $query['status'] == '') selected @endif>支付状态</option>
                    <option value="0" @if(isset($query['status']) && $query['status'] == 0) selected @endif>未支付</option>
                    <option value="1" @if(isset($query['status']) && $query['status'] == 1) selected @endif>已支付</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control start_time" name="start_time" autocomplete="off" placeholder="开始时间" value="{{ $query['start_time'] ?? '' }}">
                -
                <input type="text" class="form-control end_time" name="end_time" autocomplete="off" placeholder="结束时间" value="{{ $query['end_time'] ?? '' }}">
            </div>
            <div class="form-group">
                <select name="export" class="form-control">
                    <option value="0"  @if(!isset($query['export']) || $query['export'] == 0) selected @endif>是否导出</option>
                    <option value="1" @if(isset($query['export']) && $query['export'] == 1) selected @endif>导出</option>
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
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">ID</th>
            <th align="center">商户ID</th>
            <th align="center">订单号</th>
            <th align="center">客户端标识</th>
            <th align="center">订单金额</th>
            <th align="center">商户收入</th>
            <th align="center">代理收益</th>
            <th align="center">上游扣费</th>
            <th align="center">状态</th>
            <th align="center">添加时间</th>
            <th align="center">支付时间</th>
            <th align="center">通道</th>
            <th align="center">通知</th>
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
                    <td align="center"><a href="{{ route('order.edit',[$v->id]) }}">{{ $v->order_no }}</a></td>
                    <td align="center">{{ $v->client_sign }}</td>
                    <td align="center">{{ $v->amount }}</td>
                    <td align="center">{{ $v->user_amount }}</td>
                    <td align="center">{{ $v->agent_amount }}</td>
                    <td align="center">{{ $v->cost_amount }}</td>
                    <td align="center">@if($v->status == 0) 未支付 @elseif($v->status == 1) <span style="color:green;">已支付</span>  @else 状态错误 @endif</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">{{ $v->paytime }}</td>
                    <td align="center">{{ $apistyle[$v->apistyle_id]['api_name'] ?? '' }}({{ $upaccount[$v->upaccount_id]['upaccount_name'] ?? '' }})</td>
                    <td align="center" style="max-width:100px;overflow:auto;">
                        @if($v->tz == 2)
                            <span style="color:green;">成功通知 </span>
                        @elseif($v->tz == 1)
                            通知失败<br>{{$v->errorstr }}
                        @else
                            <span style="color:#ccc;">未通知</span>
                        @endif
                    </td>
                    <td align="left">
                        @if($v->status == 0)
                        <a href="javascript:;" data-url="{{ route('order.budan',[$v->id]) }}" class="btn btn-primary budan"><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;补单</a>
                        @elseif($v->status == 1)
                            <a href="javascript:;"  data-url="{{ route('order.notice',[$v->id]) }}" class="btn btn-info notice">
                                <span class="glyphicon glyphicon-envelope"></span>&nbsp;通知</a>
                            <a href="javascript:;"  data-url="{{ route('order.back',[$v->id]) }}" class="btn btn-primary back">
                                <span class="glyphicon glyphicon-log-out"></span>&nbsp;退单</a>
                        @endif
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

        $('.budan').click(function () {
            let url = $(this).attr('data-url');
            layer.prompt({title: '请输入谷歌验证码'}, function(code, index){
                let google_code = code.trim('');
                if(google_code.length <= 0)
                {
                    layer.msg('请输入谷歌验证码',{icon:5});
                    return;
                }
                layer.close(index);
                $.post(url,{google_code:google_code},function (result) {
                    if (result.code==1){
                        layer.msg(result.msg,{icon:6});
                        window.location.reload();
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
            });
        });

        $('.notice').click(function () {
            let url = $(this).attr('data-url');
            layer.prompt({title: '请输入谷歌验证码'}, function(code, index){
                let google_code = code.trim('');
                if(google_code.length <= 0)
                {
                    layer.msg('请输入谷歌验证码',{icon:5});
                    return;
                }
                layer.close(index);
                $.post(url,{google_code:google_code},function (result) {
                    if (result.code==1){
                        layer.msg(result.msg,{icon:6})
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
            });
        });

        $('.back').click(function () {
            let _this = $(this);
            let url = $(this).attr('data-url');
            layer.prompt({title: '请输入谷歌验证码'}, function(code, index){
                let google_code = code.trim('');
                if(google_code.length <= 0)
                {
                    layer.msg('请输入谷歌验证码',{icon:5});
                    return;
                }
                layer.close(index);
                $.post(url,{google_code:google_code},function (result) {
                    if (result.code==1){
                        _this.parent().parent().fadeOut();
                        layer.msg(result.msg,{icon:6})
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
            });
        });
    </script>
@endsection
