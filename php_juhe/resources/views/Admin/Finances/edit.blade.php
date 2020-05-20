@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">结算审核</div>
    <div class="panel-body">
        <form method="post" action="{{ route('finances.update',[1]) }}" class="layui-form form-container form-horizontal form-ajax">
            <div class="form-group">
                <label class="col-md-2 control-label">商户id</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $tixian->uid }}" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现金额</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $tixian->money }}" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现手续费</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $tixian->fee }}" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现信息</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $tixian->real_name }} {{ $tixian->bank_name }} {{ $tixian->card_no }}" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">状态</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="@if($tixian->status == 0) 未支付 @elseif($tixian->status == 1) 已支付 @elseif($tixian->status == 2) 冻结 @elseif($tixian->status == 3) 已取消 @else 状态错误 @endif" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">申请时间</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{$tixian->created_at}}" disabled >
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">代付机构</label>
                <div class="col-md-4">
                    <select name="upaccount_id" class="form-control">
                        <option value="">==请选择==</option>
                        @foreach($upaccount as $v)
                            <option value="{{$v->id}}">{{$v->upaccount_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="id" value="{{ $tixian->id }}">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
                    </button>
                    <button type="submit" class="btn btn-primary jumpbutton">
                        <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;返回&nbsp;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection('content')
