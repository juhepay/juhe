@extends("Admin.layout")
@section('content')
    <h3>
        <span class="layui-anim layui-anim-loop layui-anim-">@if(isset($msg)) {{ $msg }} @else 系统错误,请查看日志 @endif</span>
    </h3>
@endsection
