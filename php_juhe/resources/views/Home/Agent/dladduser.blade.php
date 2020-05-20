@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;添加下级商户
        </div>
    </div>
    <div class="panel-body">
        <form method="post" action="{{ route('member.dladduser') }}" class="layui-form form-container form-horizontal form-ajax">
            {{csrf_field()}}
            <div class="form-group">
                <label class="col-md-2 control-label">账户类型：</label>
                <div class="col-md-4">
                    <select name="group_type" class="form-control">
                        <option value="0">商户</option>
                        <option value="1">代理</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">登录账户：</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="username"  autocomplete="off" placeholder="请输入登录账户名" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">登录密码：</label>
                <div class="col-md-4">
                    <input type="password" class="form-control" name="password" autocomplete="off" placeholder="请输入登录密码"  required >
                    *长度6-20位
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">提现密码：</label>
                <div class="col-md-4">
                    <input type="password" class="form-control" name="save_code" placeholder="请输入提现密码" required>
                    *长度6-20位
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">会员备注：</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="remark"  autocomplete="off" placeholder="请输入会员备注">
                    * 可留空
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
