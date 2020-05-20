@extends("Home.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <em class="fa fa-list"></em>&nbsp;充值中心
        </div>
    </div>
    <div class="panel-body">
        <form method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">我的余额</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" disabled value="{{ $user->balance }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">充值金额：</label>
                <div class="col-md-6">
                    <input type="text" class="form-control money" value="100" name="money">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">支付方式</label>
                <div class="col-md-6">
                    <select name="pay_code" class="form-control pay_code">
                        @foreach($apistyle as $v)
                            <option value="{{ $v->api_mark }}">{{$v->api_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="button" class="btn btn-primary paysubmit">
                        <span class="glyphicon glyphicon-saved"></span>&nbsp;充值&nbsp;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection('content')
@section('script')
    <script>
        $(document).ready(function () {
            $('.paysubmit').on('click', function () {
                var pay_mode = $('.pay_code option:selected').val();
                var money = $('.money').val();
                if (typeof (pay_mode) == 'undefined' || pay_mode == '') {
                    layer.alert('请选择支付方式');
                    return false;
                }
                var index = layer.load();
                $.post("{{ route('member.recharge') }}", {'money': money, 'pay_code': pay_mode, 'times': Math.random()}, function (data) {
                    layer.close(index);
                    if (data.code == 1) {
                        location.href = data.url;
                    } else {
                        layer.alert(data.msg);
                    }
                },'json');
            });
        });
    </script>
@endsection
