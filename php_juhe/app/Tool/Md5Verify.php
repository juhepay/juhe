<?php

namespace App\Tool;

class Md5Verify
{
    /**
     * 数据验签
     * @param array $data
     * @return string
     */
    public function getSign(array $data)
    {
        $para_filter = $this->paraFilter($data);
        $para_sort   = $this->argSort($para_filter);
        return $this->createLinkString($para_sort);
    }

    /**
     * 除去数组中的签名参数
     * @param array $data
     * @return array
     */
    public function paraFilter(array $data)
    {
        $para_filter = array();
        foreach ($data as $key=>$val)
        {
            if($val != '' && $val != 'null' && $val != null && $key != 'sign'){
                $para_filter[$key] = $data[$key];
            }
        }
        return $para_filter;
    }

    /**
     * 对待签名参数数组排序
     * @param array $para
     * @return array
     */
    public function argSort(array $para)
    {
        ksort($para);
        reset($para);
        return $para;

    }

    /**
     *把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para
     * @return bool|string
     */
    public function createLinkString($para) {
        $arg  = "";
        foreach ($para as $key=>$val)
        {
            $arg.= $key."=".$val."&";
        }
        return $arg;
    }

    /**
     * MD5加密验证
     * @param $prestr
     * @param $key
     * @return string
     */
    public function md5Encrypt($prestr, $key) {
        $prestr = $prestr . 'key='.$key;
        return md5($prestr);
    }
}
