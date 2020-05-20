@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;提款提现
        </div>
    </div>
    <div class="panel-body">
        <form method="post" action="{{ route('member.tixian.store') }}" class="layui-form form-container form-horizontal form-ajax">
            <div class="form-group">
                <label class="col-md-2 control-label">可用余额：</label>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ auth()->user()->balance }}" disabled>
                        <span class="input-group-addon">元</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现@if( $sysconfig->fl_type==0)手续费@else费率@endif：</label>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ floatval($sysconfig->tx_fl)}}" disabled>
                        <span class="input-group-addon">@if( $sysconfig->fl_type==0)元 @else % @endif</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">最低提现金额：</label>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $sysconfig->min_price }}" disabled>
                        <span class="input-group-addon">元</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">选择银行卡：</label>
                <div class="col-md-6">
                    <select name="bankcard_id" class="form-control">
                        <option value="">请选择银行卡</option>
                        @foreach($bankcard as $v)
                            <option value="{{$v->id}}">{{ $v->real_name }}({{$v->card_no}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现金额：</label>
                <div class="col-md-6">
                    <input type="text" name="money" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现密码：</label>
                <div class="col-md-6">
                    <input type="password" name="save_code" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">谷歌验证码：</label>
                <div class="col-md-6">
                    <input type="text" name="auth_code" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-saved"></span>&nbsp;提现&nbsp;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection('content')
