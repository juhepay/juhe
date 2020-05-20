@extends("Home.layout")
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <em class="fa fa-list"></em>&nbsp;商户列表
            </div>
        </div>
        <div class="panel-body">
            <form action="" class="form-inline m-b-xs" method="get">
                <div class="form-group">
                    <input type="text" autocomplete="off" placeholder="商户ID" name="uid" class="form-control" value="{{ $query['uid'] ?? '' }}">
                </div>
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="" @if(isset($query['status']) && $query['status'] == '') selected @endif>所有状态</option>
                        <option value="0" @if(isset($query['status']) && $query['status'] == 0) selected @endif>锁定</option>
                        <option value="1" @if(isset($query['status']) && $query['status'] == 1) selected @endif>正常</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;查询</button>
                <a href="{{route('member.dladduser')}}" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i>&nbsp;自助开户</a>
            </form>
            <div class="table-responsive mt10">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th align="center">商户号</th>
                        <th align="center">商户账号</th>
                        <th align="center">备注</th>
                        <th align="center">会员类型</th>
                        <th align="center">今日收款</th>
                        <th align="center">余额</th>
                        <th align="center">提现</th>
                        <th align="center">注册时间</th>
                        <th align="center">登录权限</th>
                        <th align="center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($list)
                        @foreach($list as $k=>$v)
                            <tr>
                                <td align="center">
                                    @if($v['uid'] == $user->uid)
                                        {{$v['uid']}}<span class="label label-primary">自己</span>
                                        @else
                                        {{$v['uid']}}
                                    @endif
                                </td>
                                <td align="center">{{ $v['username'] }}</td>
                                <td align="center">@if($v['remark']) <span class="label label-danger">{{ $v['remark'] }} @endif</span></td>
                                <td align="center">@if($v['group_type'] == 0) <span class="label label-default">商户</span> @else <span class="label label-primary">代理</span> @endif</td>
                                <td align="center">{{ $v['amount'] }}</td>
                                <td align="center">{{ $v['balance'] }}</td>
                                <td align="center">{{ $v['tx_amount'] }}</td>
                                <td align="center">{{ $v['created_at'] }}</td>
                                <td align="center">@if($v['status'] == 1) 正常 @elseif($v['status'] == 0) 锁定 @else 错误  @endif</td>
                                @if($v['uid'] != $user->uid)
                                <td align="center"><a href="{{ route('member.dlfl',[$v['id']]) }}" class="layui-btn layui-btn-normal layui-btn-mini btn btn-primary"><span class="glyphicon glyphicon-edit"></span>配置</a></td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                no data.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                @include('_page')
            </div>
        </div>
    </div>
@endsection('content')
