@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">添加上游类型</div>
    <div class="panel-body">
        <form method="post" action="{{ route('upstyle.store') }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Upstyle._form')
        </form>
    </div>
</div>
@endsection('content')
@section('script')
    <script>
        $('.addbutton').on('click', function () {
            var tr = $(this).parents('table').last().find('tr').last().prev();
            tr.after(tr.clone());
        });
        $('.table-hover').on('click','.deletehang', function () {
            var _this = $(this);
            if (_this.parents('table').last().find('tr').length <= 3) {
                layer.msg('最后一行不能删除。',{icon:5});
                return;
            }
            if (confirm('确认删除该行数据？')) {
                _this.parents('tr').last().remove();
            }
        });
    </script>
@endsection
