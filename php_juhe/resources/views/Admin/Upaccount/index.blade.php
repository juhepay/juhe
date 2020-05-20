@extends("Admin.layout")
@section('content')
<h3><span class="current">上游账户</span></h3>
<br>
<form action="" method="post" class="ajax-form">
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <td colspan="4">
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('upaccount.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary addbtn" type="button" data-url="{{ route('upaccount.create') }}">
                    <span class="glyphicon glyphicon-edit"></span>&nbsp;添加
                </button>
                <button class="btn btn-primary flushbtn" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新
                </button>
            </td>
        </tr>
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">ID</th>
            <th align="center">名称</th>
            <th align="center">标识</th>
            <th align="center">接口</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->id }}</td>
                    <td align="center">{{ $v->upaccount_name }}</td>
                    <td align="center">{{ $v->upaccount_mark }}</td>
                    <td align="center">
                        @foreach($v['apistyle'] as $kk=>$vv)
                            <p>
                                <b>{{ $vv->api_name }}</b>【@if($vv->pivot->status == 1)<span style="color:red">开启</span>@else关闭@endif】
                                【费率:{{ $vv->pivot->runfl }}】
                                【单笔限额:{{ $vv->pivot->minje }}-{{ $vv->pivot->maxje  ? $vv->pivot->maxje : '不限' }}】 <br>
                                【单日限额:{{ $vv->pivot->todayje ? $vv->pivot->todayje : '不限'  }} 】
                                 @if($vv['is_polling'] == 1)
                                     @if(isset($vv['polling_ids'][$vv->pivot->id])) 【轮询：选中】 @else 【轮询：未选中】 @endif
                                 @else
                                    【当前: @if($vv->pivot->ifchoose == 0)<a href="javascript:;"  class="ifchoose" tid="{{ $vv->pivot->id }}">未应用</a>@else<span style="color:red">应用</span>@endif】
                                 @endif
                            </p>
                        @endforeach
                    </td>
                    <td align="center">
                        <a href="{{ route('upaccount.edit',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;编辑</a>
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('upaccount.delete') }}" class="btn btn-primary ajax-delete">
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
</div>
</form>
@endsection('content')
@section('script')
    <script>
        $(document).ready(function () {
            $('.ifchoose').on('click', function () {
                var zjid = $(this).attr('tid');
                layer.confirm('确认切换为当前账户。', function (index) {
                    var index2 = layer.load();
                    $.post('{{ route('upaccount.changechoose') }}', {'id': zjid}, function (result) {
                        console.log(result.code);
                        layer.close(index2);
                        if (result.code == 1) {
                            layer.msg('切换成功', function () {
                                location.reload();
                            });
                        } else {
                            layer.msg(result.msg);
                        }
                    },'json');
                });
            });
        });
    </script>
@endsection
