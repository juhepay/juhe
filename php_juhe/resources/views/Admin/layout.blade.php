<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="applicable-device" content="pc">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}--管理后台</title>
    <link href="/static/common/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/static/app.css" type="text/css" rel="stylesheet">
    <script src="/static/common/jquery-1.12.1.min.js" type="text/javascript"></script>
    <script src="/static/common/bootstrap.min.js" type="text/javascript"></script>
    <script src="/static/layer/layer.js" type="text/javascript"></script>
    <script src="/static/app.js" type="text/javascript"></script>
    @yield('css')
</head>
<body>
@include('Admin._header')
{{--主体内容--}}
<div id="main">
    <div class="container-fluid">
        <div class="row">
            {{-- 菜单 --}}
            <div class="navbar-default navbar-static-side">
                @include('Admin._left')
            </div>
            {{-- 右侧内容 --}}
            <div class="right-content">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
@yield('script')
<script>
    function checknewdingdan() {
        $.get("{{ route('admin.checkneworder') }}", function (data) {
            if (data.c == 1) {
                play_ding_sound();
                layer.msg('有新的代付订单');
            } else {
                setTimeout(function () {
                    checknewdingdan();
                }, 10000);
            }
        },'json');
    }
    setTimeout(function () {
        checknewdingdan();
    }, 2000);
</script>
</html>
