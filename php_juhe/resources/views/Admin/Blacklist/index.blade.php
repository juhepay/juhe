@extends("Admin.layout")
@section('content')
<h3><span class="current">黑名单管理</span></h3>
<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-inline form-container form-horizontal form-ajax" action="{{ route('blacklist.store') }}" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="client_sign" autocomplete="off" placeholder="客户端标识">
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-saved"></span>
                &nbsp;添 加
            </button>
        </form>
    </div>
</div>
<form action="" method="post" class="ajax-form">
<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
        @if($list)
            @foreach($list as $k=>$v)
                @if( $k/5 == 0)
                    <tr>
                @elseif($k/5 == 1)
                    <tr>
                @endif
                    <td onclick='del("{{$v }}")'>{{$v}}</td>
            @endforeach
        @else
        <tr>
            <td colspan="9">
                no data.
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
</form>
@endsection('content')
@section('script')
<script>
    function del(client_sign) {
        var index = layer.load();
        $.post('{{ route('blacklist.delete') }}', {'client_sign':client_sign, _method:'delete'},function (data) {
            layer.close(index);
            if (data.code == '0') {
                layer.alert(data.msg);
            } else {
                window.location.reload();
            }
        }, 'json');
    }
</script>
@endsection
