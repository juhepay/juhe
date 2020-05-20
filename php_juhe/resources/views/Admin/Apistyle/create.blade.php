@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">添加接口类型</div>
    <div class="panel-body">
        <form method="post" action="{{ route('apistyle.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Apistyle._form')
        </form>
    </div>
</div>
@endsection('content')
