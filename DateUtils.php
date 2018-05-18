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
    public static function fromTimestamp($timestamp)
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

    /**
     * 获取2个日期之间的所有日期 Ymd日期以数组形式返回
     * @param $start  开始时间
     * @param $end    结束时间
     * @return array 日期数组
     */
    public static function  datesBetween($start,$end){
        $data_arr = array();
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end){
            $data_arr[] = date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $data_arr;
    }

    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    public static function twodaysDiffBetween($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }
}