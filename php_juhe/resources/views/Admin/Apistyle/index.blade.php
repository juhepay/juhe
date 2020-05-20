@extends("Admin.layout")
@section('content')
<h3><span class="current">接口类型</span></h3>
<br>
<form action="" method="post" class="ajax-form">
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <td colspan="4">
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('apistyle.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary addbtn" type="button" data-url="{{ route('apistyle.create') }}">
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
            <th align="center">状态</th>
            <th align="center">轮询</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->id }}</td>
                    <td align="center">{{ $v->api_name }}</td>
                    <td align="center">{{ $v->api_mark }}</td>
                    <td align="center">@if($v->status == 1) 开启 @else 关闭 @endif</td>
                    <td align="center">@if($v->is_polling == 1) <font color="red">开启</font> @else 关闭 @endif</td>
                    <td align="center">
                        <a href="{{ route('apistyle.edit',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;编辑</a>
                        <a href="{{ route('apistyle.round',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-cog"></span>&nbsp;轮询配置</a>
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('apistyle.delete') }}" class="btn btn-primary ajax-delete">
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
