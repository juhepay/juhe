<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Loading...</title>
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="/static/pay/loading.css" rel="stylesheet">
</head>
<body>
<div class="loader">
    <div class="l_main">
        <div class="l_square"><span></span><span></span><span></span></div>
        <div class="l_square"><span></span><span></span><span></span></div>
        <div class="l_square"><span></span><span></span><span></span></div>
        <div class="l_square"><span></span><span></span><span></span></div>
    </div>
</div>

<script src="/static/pay/jquery-3.3.1.min.js" type="text/javascript"></script>
<script src="/static/pay/fingerprint2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        Fingerprint2.getV18(function (hash) {
            $.post('{{ route('pay.client') }}', {'hash': '{{$hash}}', 'client': hash}, function (ret) {
                if (ret.code == 1) {
                    setTimeout(function () {
                        location.href = ret.url;
                    }, 50);
                }else{
                    alert(ret.msg);
                }
            },'json');
        });
    })
</script>
</body>
</html>
