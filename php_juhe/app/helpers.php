<?php
/**
 * 自定义函数
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/10/31
 * Time: 10:59
 */
/**
 * redis加锁
 * @param $key
 * @param $token
 * @param $expire
 * @return bool
 */
function redisLock($key, $token, $expire)
{
    while (true){
        $result = Illuminate\Support\Facades\Redis::set($key, $token, "ex", $expire, "nx");
        if($result)
        {
            return true;
            break;
        }
    }
}

/**
 * redis解锁
 * @param $key
 * @param $token
 * @return mixed
 */
function unlock($key,$token)
{
    $script =
        "if redis.call('get',KEYS[1]) == ARGV[1]
                then return redis.call('del',KEYS[1])
            else return 0
            end";
    return Illuminate\Support\Facades\Redis::eval($script,1,$key,$token);
}


function ajaxReturn($code,$msg)
{
    return json_encode(array('code' => $code, 'msg' => $msg));
}

function ajaxReturnUrl($code,$msg,$url)
{
    return json_encode(array('code' => $code, 'msg' => $msg, 'url' => $url));
}

function CURL($url,$data,$style='',$header=[])
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_URL, $url);

    $header[] = 'Expect:';
    if($style == 'json')
    {
        $header[] = 'Content-Type:application/json';
        $data = json_encode($data);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
        curl_setopt($ch, CURLOPT_POST, false);
    }
    if (stripos($url, 'https://') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);   // 从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    if ( $res === false) {

        return  sprintf('Curl error (code %s): %s', curl_errno($ch), curl_error($ch));
    }
    curl_close($ch);
    return $res;
}

/**
 * 毫秒时间戳
 * @return float
 */
function TimeMicroTime()
{
    list($msec, $sec) = explode(' ', microtime());
    return sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}

/**
 * 轮询权重
 * @param $proArr
 * @return array|mixed
 */
function get_rand($proArr) {
    $result = array();
    foreach ($proArr as $key => $val) {
        $arr[$key] = $val['power'] ? $val['power'] : 1;
    }
    // 概率数组的总概率
    $proSum = array_sum($arr);
    asort($arr);
    // 概率数组循环
    foreach ($arr as $k => $v) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $v) {
            $result = $proArr[$k];
            break;
        } else {
            $proSum -= $v;
        }
    }
    return $result;
}

function getScanUrl($order_no,$url,$amount)
{
    $str = CURL($url,[]);
    cache(['scan'.$order_no => $str], now()->addMinutes(5));
    $arr['amount'] = $amount;
    $arr['status'] = 0;
    $arr['order_no'] = $order_no;
    cache([$order_no => json_encode($arr)], now()->addMinutes(5));
    return route('pay.scan').'?ddh='.$order_no;
}
