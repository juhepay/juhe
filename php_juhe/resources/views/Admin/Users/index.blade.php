@extends("Admin.layout")
@section('content')
<h3><span class="current">会员管理</span></h3>
<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline" action="" method="get">
            <div class="form-group">
                <input type="text" class="form-control" name="uid" placeholder="会员ID" value="{{ $query['uid'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="会员账号" value="{{ $query['username'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="agent" placeholder="代理ID" value="{{ $query['agent'] ?? '' }}">
            </div>
            <div class="form-group">
                <select name="is_admin" class="form-control">
                    <option value="">所有状态</option>
                    <option value="0" @if(isset($query['status']) && $query['status'] == 0) selected @endif>禁用</option>
                    <option value="1" @if(isset($query['status']) && $query['status'] == 1) selected @endif>启用</option>
                </select>
            </div>
            <div class="form-group">
                <select name="group_type" class="form-control">
                    <option value="">会员类型</option>
                    <option value="0" @if(isset($query['group_type']) && $query['group_type'] == 0) selected @endif>商户</option>
                    <option value="1" @if(isset($query['group_type']) && $query['group_type'] == 1) selected @endif>代理</option>
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
        <tr>
            <td colspan="10">
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('users.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary addbtn" type="button" data-url="{{ route('users.create') }}">
                    <span class="glyphicon glyphicon-edit"></span>&nbsp;添加
                </button>
                <button class="btn btn-primary flushbtn" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新
                </button>
            </td>
        </tr>
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">会员ID</th>
            <th align="center">会员账号</th>
            <th align="center">备注</th>
            <th align="center">会员类型</th>
            <th align="center">注册时间</th>
            <th align="center">余额</th>
            <th align="center">冻结金额</th>
            <th align="center">所属代理</th>
            <th align="center">登录状态</th>
            <th align="center">接单状态</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->uid}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->uid }}"></td>
                    <td align="center">{{ $v->uid }}</td>
                    <td align="center">{{ $v->username }}</td>
                    <td align="center">{{ $v->remark }}</td>
                    <td align="center">@if($v->group_type == 0) <span class="label label-default">商户</span> @else <span class="label label-primary">代理</span> @endif</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">{{ $v->balance }}</td>
                    <td align="center">{{ $v->djmoney }}</td>
                    <td align="center">{{ $v->pid ?? '无' }}</td>
                    <td align="center">@if($v->status == 1) 正常 @else 禁用 @endif</td>
                    <td align="center">@if($v->is_jd == 1) 开启 @else 关闭 @endif</td>
                    <td align="left">
                        <a href="{{ route('users.edit',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;编辑</a>
                        <a href="javascript:;" data-id="{{$v->uid}}" data-url="{{ route('users.delete') }}" class="btn btn-primary ajax-delete">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;删除</a>
                        <a href="{{ route('users.rates',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-tags"></span>&nbsp;费率</a>
                        <a href="{{ route('users.freeze',[$v->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-bitcoin">冻结</span>
                        </a>
                        @if($v->group_type == 1) <a href="{{ route('users.index')}}?agent={{$v->uid}}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-user">下级列表</span>
                        </a> @endif
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
