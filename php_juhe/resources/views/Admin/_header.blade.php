<div id="top-nav">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-15 toptitle">
                <ul>
                    <li style="float: left">
                        <a href="/">
                            <span class="glyphicon glyphicon-home"></span>
                            {{ config('app.name') }}
                            <span class="label label-primary">管理面板</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-15 clearfix topfix">
                <a href="" class="hidden-md hidden-lg hidden-xs" style="font-size:2em;float:right;margin-top:15px;margin-left:20px">
                    <span class="glyphicon glyphicon-off"></span>
                </a>
                <a href="" class="hidden-md hidden-lg" style="font-size:2em;float:right;margin-top:15px;margin-left:20px">
                    <span class="glyphicon glyphicon-cog"></span>
                </a>
                <a href="javascript:;" class="hidden-md hidden-lg" id="dropdownMenu" style="font-size:2em;float:right;margin-top:15px">
                    <span class="glyphicon glyphicon-th-large"></span>
                </a>
                <div class="nav hidden-xs hidden-sm">
                    <ul>
                        <li>
                            <a href="{{ route('admin.dropout') }}">
                                <span class="glyphicon glyphicon-off"></span>&nbsp;退出
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.info') }}"><span class="glyphicon glyphicon-user"></span>&nbsp;{{ auth()->user()->nickname }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
