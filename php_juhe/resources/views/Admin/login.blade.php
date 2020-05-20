<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{config('app.name') }}-管理后台登录</title>
    <link href="/static/common/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/static/app.css" type="text/css" rel="stylesheet">
    <script src="/static/common/jquery-1.12.1.min.js" type="text/javascript"></script>
    <script src="/static/common/bootstrap.min.js" type="text/javascript"></script>
    <script src="/static/layer/layer.js" type="text/javascript"></script>
    <script src="/static/app.js" type="text/javascript"></script>
</head>
<body>
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="box">
                    <div class="logo">
                        <span class="glyphicon glyphicon-user"></span>
                    </div>
                    <form class="form-ajax form-horizontal" action="{{ route('admin.login') }}" method="post" autocomplete="off">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" id="username" placeholder="管理账号" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="登录密码" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-7 col-xs-8" style="margin-left:0px;padding-left:0px;">
                                <input type="text" class="form-control" name="captcha" id="captcha" placeholder="验证码" maxlength="5"  required autocomplete="off">
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <img src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" class="imgcode" style="cursor:pointer;">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="auth_code" id="auth_code"  placeholder="谷歌验证码" autocomplete="off">
                        </div>
                        <div class="form-group">
                            {{ csrf_field() }}
                            <button class="btn btn-primary btn-lg btn-block" type="submit">立即登录</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
