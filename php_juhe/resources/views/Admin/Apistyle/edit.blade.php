@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑接口类型</div>
    <div class="panel-body">
        <form method="post" action="{{ route('apistyle.update',[$apistyle->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Apistyle._form')
        </form>
    </div>
</div>
@endsection('content')
