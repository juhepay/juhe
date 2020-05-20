<!DOCTYPE html>
<!-- saved from url=(0182)http://bajie.lianyuntj.com/scan?ddh=2019157158572967786571&qr=http%3A%2F%2Fbajie.lianyuntj.com%2FcacheImg%3Fkey%3Ddf602efdbf7b7ed4c85b209dd4d9ebb0&md=fd124804d7578257bc45382e30fdaba2 -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>支付宝扫码支付</title>

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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <style>
        html {
            font-family: 'helvetica neue', 'tahoma', 'arial', 'hiragino sans gb', 'microsoft yahei', 'Simsun', sans-serif;
            height: 100%;
        }

        body, div, h1, h2, img {
            margin: 0;
            padding: 0;
        }

        .qrcode_page {
            background: #363636;
            height: 100%;
            margin: 0 auto;
        }

        .container {
            width: 500px;
            height: 95%;
            margin: 0 auto;
            background: url(/index/bg.png) no-repeat center top;
            background-size: 100% auto;
            display: block;
        }

        .container .logo {
            margin: 0 auto;
            display: block;
            width: 268px;
            height: 126px;
            background: url(/index/logo.png) no-repeat center top;
            background-size: 100% auto;
        }

        .container img {
            width: 100%;
            height: auto;
            display: block;

        }

        .container .box {
            margin: 0 auto;
            width: 100%;
            background: url(/index/box.png) center top;
            background-size: 100% auto;
            text-align: center;
        }

        .h300 {
            min-height: 300px;
            padding: 5px 0;
        }
        .container .box h1 {
            font-size: 1.25rem;
            color: #f69;
        }

        .container .box h1 span {
            font-size: 3.2rem;
            line-height: 3.2rem;
        }

        .container .box h2 {
            font-size: 1.1rem;
            line-height: 2.4rem;
            font-weight: normal;
            color: #999;
        }

        #qrcode {
            position: relative;
            margin: 0 auto;
        }

        .box .tip {
            width: 100%;
            font-size: 1.1rem;
            line-height: 2.4rem;
            color: #999999;
        }


        .box strong {
            background: #3ec742;
            color: #fff;
            line-height: 36px;
            font-size: 24px;
            font-family: Arial;
            padding: 0 10px;
            border-radius: 5px;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .time_dot {
            color: #3ec742;
            font-size: 24px;
            margin-right: 10px;
            margin-left: 10px;
        }
        .icon img, icon_core {
            width: 50%;
            height: 50%;
        }

        @media screen and (max-width: 750px) {

            .qrcode_page {
                background: #3b64c2;
            }

            .container, .container_msg {
                width: 100%;
                min-width: 300px;
                height: 100%;

            }

            .container .box h1 span {
                font-size: 2.4rem;
                line-height: 2.4rem;
            }

            .container .logo {
                margin: 0 auto;
                width: 50%;
                height: 92px;
            }
        }

        #loading {
            width: 230px;
            text-align: center;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        #loading .load-text {
            position: relative;
            width: 180px;
            font-size: 14px;
            line-height: 36px;
        }

        .loading {
            position: relative;
            border: 3px solid #01a9f4;
            border-right: 3px solid #fff;
            border-bottom: 3px solid #fff;
            height: 50px;
            width: 50px;
            display: block;
            border-radius: 50%;
            -webkit-animation: loading 2s infinite linear;
            -moz-animation: loading 2s infinite linear;
            -o-animation: loading 2s infinite linear;
            animation: loading 2s infinite linear;
            margin: 10px auto;
        }

        @-webkit-keyframes loading {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-moz-keyframes loading {
            from {
                -moz-transform: rotate(0deg);
            }
            to {
                -moz-transform: rotate(360deg);
            }
        }

        @-o-keyframes loading {
            from {
                -o-transform: rotate(0deg);
            }
            to {
                -o-transform: rotate(360deg);
            }
        }

        @keyframes loading {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script src="/index/jquery-3.3.1.min.js"></script>
    <script src="/index/layer.js"></script>
    <link rel="stylesheet" href="/index/layer.css" id="layuicss-layer">
    <!--<script src="/Public/plugin/html2canvas.min.js?2019-12-01.0000"></script>-->
</head>
<body class="qrcode_page" style="">
<div class="container">
    <div class="logo"></div>
    <div class="box_top"><img src="/index/box_top.png"></div>
    <div class="box">
        <h1><span>￥{{ sprintf('%.2f',$data['amount']) }}</span></h1>
        <h2>{{ $data['order_no'] }}</h2>
    </div>
    <div><img src="/index/box_middle.png"></div>
    <div class="box h300" style="padding:1px 0">
        <div class="tips" style="margin-bottom:10px">
            <p style="margin:0">手机用户请 <b>截屏</b> 保存二维码到手机中</p>
            <p style="margin:0">在支付宝扫一扫中选择“相册”即可</p>
        </div>

        <div class="qrcode_box" style="position:relative">
            <div id="qrcode" style="display: block; width: 230px; height: 230px;"></div>
            <div id="loading" style="display: none;">
                <span class="loading"></span>
                <span class="load-text">正在载入二维码...</span>
            </div>
        </div>
        <span class="tip">请在下面时间内完成付款</span>
        <div class="time">
            <strong id="minute_show" style="background:#01a9f4">00</strong><span class="time_dot" style="color:#01a9f4">:</span><strong
                id="second_show" style="background:#01a9f4">00</strong>
        </div>
        <div class="ico-scan_alipay_qr"></div>
        <div id="tipText" style="width: 100%; height: 56px; margin-top: 15px; display: block;">
            <div style="width:45%;height:56px;float:left;">
                <img src="/index/alipay_qr_scan.png"
                     style="width:40px;height:40px;float:right;margin-right:18px;margin-top:8px;">
            </div>
            <div id="showtext"
                 style="line-height:56px;height:56px;width:48%;float:left;text-align:left;padding-left:2%;font-size:18px;">
                <span
                    style="width:100px;height:40px;line-height:40px;margin-top:8px;border-radius:5px;color:white;background:#01a9f4;display:block;text-align:center">打开支付宝</span>
            </div>
        </div>
    </div>
    <div><img src="/index/box_bottom.png"></div>
</div>


<script>
    $(function () {
        let postflag = 0;

        $('#showtext').click(function () {
            top.location = "alipays://platformapi/startapp";
        });

        //定时检测订单支付情况
        let myTimer = null;
        let timer = function (intDiff) {
            let timeTick = 0;
            myTimer = window.setInterval(function () {
                timeTick++;
                let minute = 0, second = 0;
                if (intDiff > 0) {
                    minute = Math.floor(intDiff / 60);
                    second = Math.floor(intDiff) - (minute * 60);
                }
                if (minute <= 9) {
                    minute = '0' + minute;
                }
                if (second <= 9) {
                    second = '0' + second;
                }
                $('#minute_show').html(minute);
                $('#second_show').html(second);
                if (minute <= 0 && second <= 0) {
                    qrcode_timeout();
                    clearInterval(myTimer);
                }
                intDiff--;
                // 检测订单状态
                if (timeTick % 3 === 0) {
                    checkOrder();
                }
            }, 1000);
        };

        let qrcode_show = function () {
            $("#loading").hide();
            $("#qrcode").html('');
            $("#qrcode").css("background-image", "url('{{ route('pay.cacheImg') }}?ddh={{$data['order_no']}}')");
            $("#qrcode").css("background-size", "100%");
        };

        //二维码超时则停止显示二维码
        let qrcode_timeout = function () {
            $("#qrcode").css("background-image", "url('/index/qrcode_timeout.png')");
            layer.alert('若已支付未到帐，请及时联系客服');
        };

        //获取订单状态
        let checkOrder = function () {
            $.post('/getddhstatus', {'ddh': '{{$data['order_no']}}'}, function (ret) {

                if (ret['status'] === 1) {
                    location.href = '/backurl/' + '{{$data['order_no']}}';
                }
            });
        };

        let ua = navigator.userAgent;
        let isIpad = ua.match(/(iPad).*OS\s([\d_]+)/i);
        let isIphone = !isIpad && ua.match(/(iPhone.+OS)\s([\d_]+)/i);
        let isAndroid = ua.match(/Android.+[\d.]+/i);
        let isWechat = ua.match(/MicroMessenger/i) || typeof (WeixinJSBridge) === 'object';
        if (!isAndroid && !isIphone && !isIpad) {
            $("#tipText").css("display", "none");
        }
        if (isWechat || 'alipay' === 'all') {
            $("#tipText").css("display", "none");
        } else {
            layer.alert('温馨提示', {
                title: "注意事项",
                content: "1、此码一次性使用，无需剪裁<br />2、修改订单金额，<b style='color:red;'>充值不到账</b><br />3、重复扫码付款，不到账、不退款"
            });
            $("#tipText").css("display", "block");
        }
        qrcode_show();
        timer(300);

    });
</script>

<div class="layui-layer-move"></div>
</body>
</html>
