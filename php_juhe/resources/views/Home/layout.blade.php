<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}--会员中心</title>
    <link href="/static/common/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/static/home/style.css" type="text/css" rel="stylesheet">
    <link href="/static/home/font-awesome.css" type="text/css" rel="stylesheet">
    <script src="/static/common/jquery-1.12.1.min.js" type="text/javascript"></script>
    <script src="/static/common/bootstrap.min.js" type="text/javascript"></script>
    <script src="/static/layer/layer.js" type="text/javascript"></script>
    <script src="/static/app.js" type="text/javascript"></script>
    @yield('css')
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
<div class="pace pace-inactive">
    <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>
{{--主体内容--}}
<div id="wrapper">
    {{-- 菜单 --}}
    <div class="navbar-default navbar-static-side">
        <div class="sidebar-collapse">
            @include('Home._left')
        </div>
    </div>
    {{-- 右侧内容 --}}
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <div class="navbar navbar-static-top white-bg" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a href="#" class="navbar-minimalize minimalize-styl-2 btn btn-primary ">
                        <i class="fa fa-bars"></i>
                    </a>
                    <span class="thiscookie none"></span>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a href="javascript:;" ><i class="fa fa-user"></i>{{ auth()->user()->username }}</a>
                    </li>
                    <li>
                        <a href="{{ route('member.dropout') }}"><i class="fa fa-sign-out"></i>推出</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row wrapper wrapper-content">
            <div class="row">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
</body>
@yield('script')
</html>
