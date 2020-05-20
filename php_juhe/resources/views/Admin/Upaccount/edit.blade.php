@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑上游账户</div>
    <div class="panel-body">
        <form method="post" action="{{ route('upaccount.update',[$upaccount->id]) }}" class="layui-form form-container form-horizontal form-ajax">
            @include('Admin.Upaccount._form')
        </form>
    </div>
</div>
@endsection('content')
@section('script')
    <script>
        $(document).ready(function () {
            $('.upstyle').on('change', function(data){
                let stylelist = {!! $upstyleList !!};
                let edit = [{!! $upaccount !!}];
                let t = $(this).find('option:selected').val();
                let tid = $(this).find('option:selected').attr('tid');
                let str = '';
                if (stylelist != [] && typeof (stylelist[0])) {
                    stylelist = stylelist[0];
                }
                if (edit != [] && typeof (edit[0]) != 'undefined') {
                    edit = edit[0];
                }

                if (typeof (stylelist[tid]) != 'undefined') {
                    let params = stylelist[tid]['params'];
                    let tmpstr = '';
                    let selected = '';
                    for (i in params) {
                        if (typeof (edit['upaccount_params'][t + '_' + params[i]['paramsen']]) != 'undefined') {
                            tmpstr = edit['upaccount_params'][t + '_' + params[i]['paramsen']];
                        } else{console.log(33);
                            tmpstr = '';
                        }


                        if (params[i]['paramsinput'] == 'text') {
                            str += '<div class="form-group ' + stylelist[tid]['paramsen'] + '">' +
                                '        <label class="col-md-2 control-label">' +
                                '            ' + params[i]['paramstitle'] + '：' +
                                '        </label>' +
                                '        <div class="col-md-4">' +
                                '            <input type="text" class="form-control"  name="' + t + '_' + params[i]['paramsen'] + '" value="' + tmpstr + '" placeholder="请输入' + params[i]['paramstitle'] + '" />' +
                                '        </div>' +
                                '    </div>';
                        } else if (params[i]['paramsinput'] == 'textarea') {
                            str += '<div class="form-group ' + stylelist[tid]['paramsen'] + '">' +
                                '        <label class="col-md-2 control-label">' +
                                '            ' + params[i]['paramstitle'] + '：' +
                                '        </label>' +
                                '        <div class="col-md-4">' +
                                '           <textarea cols="50" rows="5" class="form-control" name="' + t + '_' + params[i]['paramsen'] + '">' + tmpstr + '</textarea>' +
                                '        </div>' +
                                '    </div>';
                        } else if (params[i]['paramsinput'] == 'select') {
                            var options = params[i]['paramsvalue'].split(',');
                            str += '<div class="form-group ' + stylelist[tid]['paramsen'] + '">' +
                                '        <label class="col-md-2 control-label">' +
                                '            ' + params[i]['paramstitle'] + '：' +
                                '        </label>' +
                                '        <div class="col-md-4">' +
                                '        <select name="' + t + '_' + params[i]['paramsen'] + '" class="form-control">';
                            for (var j in options) {
                                if (tmpstr == options[j])
                                    selected = "selected='selected'";
                                else
                                    selected = '';
                                str += '<option value="' + options[j] + '" ' + selected + '>' + options[j] + '</option>';
                            }
                            str += '        </select>' +
                                '        </div>' +
                                '    </div>';
                        }
                    }
                }
                $('.params').html(str);
            });

            $('.upstyle').change();
        });
    </script>
@endsection
