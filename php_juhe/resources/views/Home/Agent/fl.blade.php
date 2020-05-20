@extends('Home.layout')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">商户【 {{$user->username}} 】费率配置</div>
        <div class="panel-body">
            <div class="form-group">
                <form method="post" action="{{ route('member.dlfl',[$user->id]) }}" class="layui-form form-container form-horizontal form-ajax">
                    {{csrf_field()}}
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>接口名称</th>
                            <th>费率(例如10%，这里输入10)</th>
                            <th>开关</th>
                        </tr>
                        @foreach($apistyle as $k=>$v)
                            <tr @if($my_fl[$v['id']]['status'] == 0) style="background-color:#ccc;" @endif>
                                <td><input type="hidden" name="apistyle[]" value="{{ $v['id'] }}">{{ $v['api_name'] }}</td>
                                <td>
                                    @if( isset($user_fl[$v['id']]) && floatval($user_fl[$v['id']]['rate']) != 0 )
                                        <select name="flselect_{{ $v['id'] }}" class="form-control flselect">
                                            <option value="0">系统默认</option>
                                            <option value="1" selected>自定义</option>
                                        </select>
                                        <span style="display:block;">
                                              <input type="text" autocomplete="off" class="form-control" name="fl_{{ $v['id'] }}" @if( isset($user_fl[$v['id']]) && floatval($user_fl[$v['id']]['rate']) != 0 ) value="{{ floatval($user_fl[$v['id']]['rate']) }}"@endif>
                                        </span>
                                    @else
                                        <select name="flselect_{{ $v['id'] }}" class="form-control flselect">
                                            <option value="0" selected>系统默认</option>
                                            <option value="1">自定义</option>
                                        </select>
                                        <span style="display:none;">
                                          <input type="text" autocomplete="off" class="form-control" name="fl_{{ $v['id'] }}">
                                    </span>
                                    @endif
                                    @if($my_fl[$v['id']]['rate'] > 0)
                                        我的费率：{{floatval($my_fl[$v['id']]['rate'])}}%，设定费率不得低于此费率；
                                    @else
                                        没有配置费率，无法为下级商户单独设置费率
                                    @endif
                                </td>
                                <td>
                                    <select name="status_{{ $v['id'] }}" class="form-control">
                                        <option value="1" @if( isset($user_fl[$v['id']]) && $user_fl[$v['id']]['status'] == 1 ) selected @endif >开启</option>
                                        <option value="0" @if( isset($user_fl[$v['id']]) && $user_fl[$v['id']]['status'] == 0 ) selected @endif>关闭</option>
                                    </select>
                                    @if($my_fl[$v['id']]['status'] == 0)
                                        <font color="#ff0000">您没有操作权限；</font>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
                            </button>
                            <button type="button" class="btn btn-primary jumpbutton">
                                <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;返回
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.flselect').on('change', function () {
                var t = $(this).find('option:selected').val();
                if (t == 1) {
                    $(this).next().css({'display': 'block'});
                } else {
                    $(this).next().css({'display': 'none'});
                }
            });
        });
    </script>
@endsection
