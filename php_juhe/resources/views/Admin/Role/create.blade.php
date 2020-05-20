@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">添加角色</div>
    <div class="panel-body">
        <form method="post" action="{{ route('roles.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Role._form')
        </form>
    </div>
</div>
@endsection('content')
