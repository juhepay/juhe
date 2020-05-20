<div class="left-nav">
    <dl>
        <dt>
            <span class="glyphicon glyphicon-user"></span>&nbsp;会员管理
        </dt>
        <dd>
            <a href="{{ route('users.index') }}">会员列表</a>
        </dd>
        <dd>
            <a href="{{ route('bankcard.index') }}">提现卡号</a>
        </dd>
    </dl>
    <dl>
        <dt>
            <span class="glyphicon glyphicon-th-list"></span>&nbsp;订单管理
        </dt>
        <dd><a href="{{ route('order.index') }}">所有订单</a></dd>
        <dd><a href="{{ route('order.index').'?status=1' }}">已付订单</a></dd>
        <dd><a href="{{ route('order.index').'?status=0' }}">未付订单</a></dd>
        <dd><a href="{{ route('paylog.index') }}">接口日志</a></dd>
        <dd><a href="{{ route('blacklist.index') }}">黑名单管理</a></dd>
    </dl>
    <dl>
        <dt>
            <span class="glyphicon glyphicon-road"></span>&nbsp;接口管理
        </dt>
        <dd><a href="{{ route('apistyle.index') }}">接口类型</a></dd>
        <dd><a href="{{ route('upstyle.index') }}">上游类型</a></dd>
        <dd><a href="{{ route('upaccount.index') }}">上游账户</a></dd>
    </dl>
    <dl>
        <dt>
            <span class="glyphicon glyphicon-usd"></span>&nbsp;财务管理
        </dt>
        <dd><a href="{{ route('finances.index') }}">未提现订单</a></dd>
        <dd><a href="{{ route('finances.index',[1]) }}">已提现订单</a></dd>
        <dd><a href="{{ route('finances.tj') }}">通道统计</a></dd>
        <dd><a href="{{ route('finances.moneylog') }}">资金变动</a></dd>
    </dl>
    <dl>
        <dt>
            <span class="glyphicon glyphicon-cog"></span>&nbsp;系统设置
        </dt>
        <dd><a href="{{ route('power.index') }}">权限管理</a></dd>
        <dd><a href="{{ route('roles.index') }}">角色管理</a></dd>
        <dd><a href="{{ route('admins.index') }}">管理员管理</a></dd>
        <dd><a href="{{ route('sysconfig.index') }}">系统配置</a></dd>
        <dd><a href="{{ route('syslog.index') }}">系统日志</a></dd>
    </dl>
</div>
