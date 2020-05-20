@extends("Admin.layout")
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">系统配置</div>
        <div class="panel-body">
            <form method="post" action="{{ route('sysconfig.update') }}" class="layui-form form-container form-horizontal form-ajax">
                <div class="form-group">
                    <label class="col-md-2 control-label">最小提现金额</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="min_price" placeholder="请输入用户名" autocomplete="off"   value="{{$sysconfig->min_price}}"  required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">提现费率类型</label>
                    <div class="col-md-4">
                        <select name="fl_type" class="form-control">
                            <option value="0" @if($sysconfig->fl_type == 0) selected @endif>按单笔收费</option>
                            <option value="1" @if($sysconfig->fl_type == 1) selected @endif>按百分比收费</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">提现费率</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="tx_fl" autocomplete="off" value="{{$sysconfig->tx_fl}}"  required>
                    </div>
                    *单笔1.5元则输入1.5，按照百分比1.5%输入1.5 为0则不扣费
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection('content')
