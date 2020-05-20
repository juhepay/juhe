@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;提现银行卡管理
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td colspan="5">
                            <button class="btn btn-primary anniu addbtn" type="button" data-url="{{route('member.bankcard.create')}}">
                                <span class="glyphicon glyphicon-edit"></span> 添加银行卡
                            </button>
                        </td>
                    </tr>
                    <tr>
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
                            <td align="center">{{ $v->real_name }}</td>
                            <td align="center">{{ $v->card_no }}</td>
                            <td align="center">{{ $v->bank_name }}</td>
                            <td align="center">{{ $v->created_at }}</td>
                            <td align="center">
                                <a href="{{ route('member.bankcard.edit',[$v->id]) }}" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-edit"></span>&nbsp;编辑</a>
                                <a href="javascript:;" data-id="{{$v->id}}" data-url="{{ route('member.bankcard.delete') }}" class="btn btn-primary ajax-delete">
                                    <span class="glyphicon glyphicon-remove"></span>&nbsp;删除</a>
                            </td>
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
        </div>
    </div>
</div>
@endsection('content')
