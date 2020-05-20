@extends("Admin.layout")
@section('content')
<h3><span class="current">提现卡号</span></h3>
<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline" action="" method="get">
            <div class="form-group">
                <input type="text" class="form-control" name="uid" placeholder="会员id" value="{{ $query['uid'] ?? '' }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="card_no" placeholder="银行卡号" value="{{ $query['card_no'] ?? '' }}">
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
                <button class="btn btn-primary delbtn" type="button" data-url="{{ route('bankcard.delete') }}">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除
                </button>
                <button class="btn btn-primary flushbtn" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>&nbsp;刷新
                </button>
            </td>
        </tr>
        <tr class="info">
            <th style="width: 15px;"><input type="checkbox" name="mmAll" class="selectAllCheckbox"></th>
            <th align="center">会员ID</th>
            <th align="center">收款人</th>
            <th align="center">银行卡号</th>
            <th align="center">开户银行</th>
            <th align="center">添加时间</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                <tr id="tr{{$v->id}}">
                    <td><input type="checkbox" class="checkbox_ids checkbox" name="ids[]" value="{{ $v->id }}"></td>
                    <td align="center">{{ $v->uid }}</td>
                    <td align="center">{{ $v->real_name }}</td>
                    <td align="center">{{ $v->card_no }}</td>
                    <td align="center">{{ $v->bank_name }}</td>
                    <td align="center">{{ $v->created_at }}</td>
                    <td align="center">
                        <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('bankcard.delete') }}" class="btn btn-primary ajax-delete">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;删除</a>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="7">
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
