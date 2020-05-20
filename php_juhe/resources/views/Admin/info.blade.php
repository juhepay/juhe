@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;我的资料
        </div>
    </div>
    <div class="panel-body">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-1" data-toggle="tab">修改登录密码</a>
                </li>
                @if(!$admin->google_key)
                <li>
                    <a href="#tab-2" data-toggle="tab">谷歌验证配置</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" style="background-color:#fff;padding:20px 20px;">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <form action="{{ route('admin.pass') }}" method="post" class="form-ajax form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">原密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="passwordy" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">新密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">重复新密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="style" value="1">
                                <div class="col-md-offset-2 col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;提交
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(!$admin->google_key)
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <form action="{{ route('admin.pass') }}" method="post" class="form-ajax form-horizontal">
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-6">
                                {!! QrCode::size(200)->margin(1)->generate($qrCodeUrl); !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">谷歌验证码</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="google_code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="secret" value="{{ $secret }}">
                                <input type="hidden" name="style" value="3">
                                <div class="col-md-offset-2 col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;提交
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection('content')
