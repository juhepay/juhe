@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">添加权限</div>
    <div class="panel-body">
        <form method="post" action="{{ route('power.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Power._form')
        </form>
    </div>
</div>
@endsection('content')
