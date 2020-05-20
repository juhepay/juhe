@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;添加提现银行卡
        </div>
    </div>
    <div class="panel-body">
        <form method="post" action="{{ route('member.bankcard.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Home.BankCard._form')
        </form>
    </div>
</div>
@endsection('content')
