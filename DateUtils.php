<?php
/**
 * Created by ls.
 * User: ls
 * Date: 2018/5/17
 * Time: 18:02
 */

namespace utils;


class DateUtils
{
    /**时间戳转日期
     * @param $timestamp 时间戳，空则为当前时间
     * @return false|string 日期
     */
    public function fromTimestamp($timestamp)
    {
        if (isset($timestamp))
        {
            return date('Y-m-d', $timestamp);
        }
        else
        {
            return date('Y-m-d', time());
        }
    }
}