@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">添加会员</div>
    <div class="panel-body">
        <form method="post" action="{{ route('users.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Users._form')
        </form>
    </div>
</div>
@endsection('content')
