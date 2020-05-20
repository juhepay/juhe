@extends("Home.layout")
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
                    <a href="#tab-1" data-toggle="tab">我的资料</a>
                </li>
                <li>
                    <a href="#tab-2" data-toggle="tab">修改登录密码</a>
                </li>
                <li>
                    <a href="#tab-3" data-toggle="tab">修改提现密码</a>
                </li>
                @if(!$user->google_key)
                <li>
                    <a href="#tab-4" data-toggle="tab">谷歌验证配置</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" style="background-color:#fff;padding:20px 20px;">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <form action="" class="form-ajax form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">登录账号</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">账户余额</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $user->balance }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">注册时间</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $user->created_at }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">商户ID</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $user->uid }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">商户密钥</label>
                                <div class="col-md-6">
                                    <em class="miyaocon" style="margin-right:5px;color:blue;">******</em>
                                    <a class="btn btn-primary" id="btn_show_key">查看</a>
                                    <a class="btn btn-primary" id="btn_reset_key">重置</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">支付网关</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ route('pay.index') }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">查询网关</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ route('pay.query') }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">代付网关</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ route('pay.ekofapy') }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">代付查询网关</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ route('pay.ekofapy.query') }}" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <form action="{{ route('member.pass') }}" method="post" class="form-ajax form-horizontal">
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
                <div id="tab-3" class="tab-pane">
                    <div class="panel-body">
                        <form action="{{ route('member.pass') }}" method="post" class="form-ajax form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">提现原密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="passwordy">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">提现新密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">重复提现新密码</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="style" value="2">
                                <div class="col-md-offset-2 col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;提交
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(!$user->google_key)
                <div id="tab-4" class="tab-pane">
                    <div class="panel-body">
                        <form action="{{ route('member.pass') }}" method="post" class="form-ajax form-horizontal">
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
@section('script')
    <script>
        $('#btn_show_key').click(function () {
            layer.prompt({title: '请输入谷歌验证码'}, function(code, index){
                $.post("{{ route('member.getuserkey') }}",{google_code:code},function (result) {
                    layer.close(index);
                    if (result.code==1){
                       layer.alert(result.key);
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
            });
        });
        $('#btn_reset_key').click(function () {
            layer.prompt({title: '请输入谷歌验证码'}, function(code, index){
                $.post("{{ route('member.resetuserkey') }}",{google_code:code},function (result) {
                    layer.close(index);
                    if (result.code==1){
                        layer.msg(result.msg,{icon:1})
                    }else{
                        layer.msg(result.msg,{icon:5})
                    }
                },'json');
            });
        });
    </script>
@endsection
