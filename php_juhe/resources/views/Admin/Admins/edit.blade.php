@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑管理员</div>
    <div class="panel-body">
        <form method="post" action="{{ route('admins.update',[$admins->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Admins._form')
        </form>
    </div>
</div>
@endsection('content')
