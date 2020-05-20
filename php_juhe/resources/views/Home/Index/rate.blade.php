@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;我的费率
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th align="center">接口名称</th>
                        <th align="center">接口编码</th>
                        <th align="center">接口费率【百分比】</th>
                        <th align="center">状态</th>
                    </tr>
                </thead>
                <tbody>
                @if($list)
                    @foreach($list as $k=>$v)
                        @if(isset($apistyle[$v->apistyle_id]))
                        <tr>
                            <td align="center">{{ $apistyle[$v->apistyle_id]['api_name'] }}</td>
                            <td align="center">{{ $apistyle[$v->apistyle_id]['api_mark'] }}</td>
                            <td align="center">
                                @if($v->rate > 0)
                                    {{ floatval($v->rate) }}%
                                @else
                                    @if($v->upaccount_id == 0)
                                        @if($apistyle[$v->apistyle_id]['polling_id'])
                                            {{ floatval($apizj_account_id[$apistyle[$v->apistyle_id]['polling_id']]['runfl']) }}%
                                        @else
                                            {{ floatval( $apizj_apistyle_id[$v->apistyle_id] ?? 0 )}}%
                                        @endif
                                    @else
                                        {{ floatval($apizj_account_id[$v->upaccount_id]['runfl']) }}%
                                    @endif
                                @endif

                            </td>
                            <td align="center">
                                @if($v->status == 1) 开启 @else <font color="red">关闭</font> @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">
                            no data.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection('content')
