<?php
/**
 * Created by ls.
 * User: ls
 * Date: 2018/5/17
 * Time: 18:03
 */

namespace utils;


class DateTimeUtils
{
    /**时间戳转日期
     * @param $timestamp 时间戳，空则为当前时间
     * @return false|string 日期
     */
    public static function fromTimestamp($timestamp)
    {
        if (isset($timestamp))
        {
            return date('Y-m-d H:i:s', $timestamp);
        }
        else
        {
            return date('Y-m-d H:i:s', time());
        }
    }

    /**秒转时分秒格式
     * @param $second 总秒数
     * @return string h:m:s格式字符串
     */
    public static function s2his($second)
    {
        $s = $second % 60;
        $i_tmp = $second / 60;
        $i = $i_tmp % 60;
        $h = $i_tmp / 60;
        return sprintf("%02d:%02d:%02d", $h, $i, $s);
    }
}