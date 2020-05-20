@extends("Admin.layout")
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">配置接口轮询
        <p><font color="red">注意：1、轮询状态必须启用并且有选着账户才生效</font></p>
        <p><font color="red">注意：2、为商户指定通道账户，轮询不生效</font></p>
    </div>
    <form method="post" action="{{ route('apistyle.roundstore',[$apistyle->id]) }}" class="layui-form form-container form-horizontal form-ajax">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">接口名称</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{$apistyle->api_name ?? ''}}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">接口类型</label>
                <div class="col-md-4">
                    <input type="text" class="form-control"  value="{{$apistyle->api_mark ?? ''}}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">轮询状态</label>
                <div class="col-md-4">
                    <select name="is_polling" class="form-control flselect">
                        <option value="1" @if(isset($apistyle->is_polling) && $apistyle->is_polling == 1) selected @endif>启用</option>
                        <option value="0" @if(isset($apistyle->is_polling) && $apistyle->is_polling == 0) selected @endif>关闭</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>选着</th>
                        <th>账户</th>
                        <th>权重 (权重1-10数值越高几率越大)</th>
                    </tr>
                    @if($upaccount)
                        @foreach($upaccount as $k=>$v)
                            <tr>
                                <td><input  type="checkbox"  class="checkbox" name="jkid[]" value="{{ $apizj[$v['id']]['id'] }}" @if( isset($apistyle->polling_ids[$apizj[$v['id']]['id']]) ) checked @endif></td>
                                <td>{{ $v['upaccount_name'] }}</td>
                                <td><input type="text" class="form-control" name="power_{{ $apizj[$v['id']]['id'] }}" @if( isset($apistyle->polling_ids[$apizj[$v['id']]['id']]) ) value="{{ $apistyle->polling_ids[$apizj[$v['id']]['id']]['power'] }}" @endif></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" align="center">
                                暂无接口数据，请配置<a href="{{ route('upaccount.index') }}">上游账户</a>。
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
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
        </div>
    </form>
</div>
@endsection('content')
