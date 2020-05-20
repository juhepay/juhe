@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑角色</div>
    <div class="panel-body">
        <form method="post" action="{{ route('roles.update',[$role->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Role._form')
        </form>
    </div>
</div>
@endsection('content')
