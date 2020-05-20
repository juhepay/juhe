@extends("Admin.layout")
@section('content')
<h3><span class="current">管理员管理</span></h3>
<br>
<form action="" method="post" class="ajax-form">
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <td colspan="4">
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('admins.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary addbtn" type="button" data-url="{{ route('admins.create') }}">
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
            <th align="center">用户名</th>
            <th align="center">昵称</th>
            <th align="center">角色</th>
            <th align="center">注册时间</th>
            <th align="center">状态</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->id }}</td>
                    <td align="center">{{ $v->username }}</td>
                    <td align="center">{{ $v->nickname }}</td>
                    <td align="center">{{ $role_array[$v->role_id]['role_name'] }}</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">@if($v->status == 1) 正常 @else 禁用 @endif</td>
                    <td align="center">
                        <a href="{{ route('admins.edit',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;编辑</a>
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('admins.delete') }}" class="btn btn-primary ajax-delete">
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
