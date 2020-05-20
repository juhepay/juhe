@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">余额冻结</div>
    <div class="panel-body">
        <form method="post" action="{{ route('users.addfreezes',[$user->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            {{csrf_field()}}
            <div class="form-group">
                <label class="col-md-2 control-label">会员ID</label>
                <div class="col-md-4">
                    <input type="text" class="form-control"  autocomplete="off" value="{{$user->uid}}"  disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">会员余额</label>
                <div class="col-md-4">
                    <input type="text" class="form-control"  autocomplete="off" value="{{$user->balance}}"  disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">已冻结金额</label>
                <div class="col-md-4">
                    <input type="text" class="form-control"  autocomplete="off" value="{{$user->djmoney}}"  disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">操作类型</label>
                <div class="col-md-4">
                    <select name="act" class="form-control">
                        <option value="1">冻结</option>
                        <option value="2">解冻</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">冻结金额</label>
                <div class="col-md-4">
                    <input type="text" class="form-control"  autocomplete="off" name="money" placeholder="请输入冻结金额" required>
                    *用户余额均可冻结
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
                    </button>
                    <button type="button" class="btn btn-primary jumpbutton">
                        <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;返回
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection('content')
