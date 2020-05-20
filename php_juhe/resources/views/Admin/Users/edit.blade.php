@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑会员</div>
    <div class="panel-body">
        <form method="post" action="{{ route('users.update',[$user->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Users._form')
        </form>
    </div>
</div>
@endsection('content')
