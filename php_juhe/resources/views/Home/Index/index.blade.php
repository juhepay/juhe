@extends("Home.layout")
@section('content')
<div class="row">
    <div class="col-md-3 col-xs-6">
        <div class="panel">
            <div class="panel-body">
                <h4 class="pull-left">帐户总余额</h4>
                <h4 class="pull-right text-danger">￥{{ $user->balance }}&nbsp;元<a href="{{ route('member.recharge') }}">&nbsp;(充值)</a></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6">
        <div class="panel">
            <div class="panel-body">
                <h4 class="pull-left">已提现金额</h4>
                <h4 class="pull-right text-danger">￥{{$money}}&nbsp;元&nbsp;</h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-xs-6">
        <div class="panel">
            <div class="panel-body">
                <h4 class="pull-left">今日收款</h4>
                <h4 class="pull-right text-danger">￥{{ $order_amount }} 元</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6">
        <div class="panel">
            <div class="panel-body">
                <h4 class="pull-left">今日提现</h4>
                <h4 class="pull-right text-danger">￥{{$today_money}}&nbsp;元&nbsp;</h4>
            </div>
        </div>
    </div>
</div>
@endsection('content')
