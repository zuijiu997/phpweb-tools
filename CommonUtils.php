<?php
/**
 * Created by ls.
 * User: ls
 * Date: 2018/5/18
 * Time: 10:44
 */

namespace utils;


class CommonUtils
{

    /**
     * 统一密码加密方式，如需变动直接修改此处
     * @param $password
     * @return string
     */
    function  encrypt_pass($password,$encrypt='')
    {
        return hash("md5", trim($password).$encrypt);
    }

    /**
     * 获得真实IP地址
     * @return string
     * 自学php博客www.zixuephp.cn整理
     */
    function get_client_ip() {
        static $realip = NULL;
        if ($realip !== NULL) return $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($arr AS $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match('/[\d\.]{7,15}/', $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }


    function isMobile()
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        if (isset($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 手机号格式检查
     * @param string $mobile
     * @return bool
     */
    function check_mobile_number($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        $reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

        return preg_match($reg, $mobile) ? true : false;
    }

    //获取客户端真实IP
    function getClientIP()
    {
        global $ip;
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "Unknow";
        }

        return $ip;
    }

    /**
     * 友好显示内容，多用于bug测试
     * @param string $data 要输出的类容
     */
    function p($data){
        if(isset($data[0]) && is_object($data[0])){
            $dump = [];
            foreach ($data as $k=> $v) {
                if($v != null){
                    $dump[] = $v->toArray();
                }
            }
            $data = $dump;
            unset($dump);
        }

        // 定义样式
        $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data=$data ? 'true' : 'false';
        }elseif (is_null($data)) {
            $show_data='null';
        }else{
            $show_data=print_r($data,true);
        }
        $str.=$show_data;
        $str.='</pre>';
        echo $str;
    }

    //请确保项目文件有可写权限，不然打印不了日志。
    function write_log($text,$fileName = null,$level='info',$dataType = "json") {
        $filePath = ROOT_PATH."log/".date("Ym")."/".date("Ymd")."/";
        $fileName = $fileName != null ? $filePath.$fileName : "log.log";

        if($dataType == "json"){
            $text      = ( is_object($text) || is_array($text)) ? json_encode($text) : $text;
            $contents  = "[--".$level."--]";
            $contents .= "[".date("Y-m-d H:i:s",time())."]";
            $contents .= "  ".$text;
            $contents .= "\r\n";
            $path = dirname($fileName);
            !is_dir($path) && mkdir($path, 0755, true);

            $filesize=abs(filesize($path));
            if($filesize >= (1024*1024*300)){
                // 删除之前的文件
                exit();
            }

            if (is_file($fileName) && floor(2097152*10) <= filesize($fileName)) {
                rename($fileName, dirname($fileName) . DS . basename($fileName) . '-' .time() );
                // 删除源文件
                unlink($fileName);
            }
            file_put_contents ( $fileName,$contents, FILE_APPEND );
            unset($text);
            unset($path);
            unset($contents);
        }else{
            file_put_contents ( $fileName, date ("Y-m-d H:i:s",time()) . "  " . var_export($text,true) . "\r\n", FILE_APPEND );
        }


    }

    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    function diff_between_twodays($day1, $day2)
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

    function get_day_start_time($t=null){
        $t = $t==null?time():$t;
        return  mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));  //当天开始时间
    }

    /**
     * 获取2个日期之间的所有日期 Ymd日期以数组形式返回
     * @param $start  开始时间
     * @param $end    结束时间
     */
    function get_dates($start,$end){
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
     * 带timeout的curl的GET请求和POST请求
     */
    function http_get($url, $param,$timeout=120)
    {
        $ch = curl_init();
        $timeout = 120;
        $paramStr = "";
        foreach ($param as $k => $v) {
            $paramStr .= "$k=$v&";
        }
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $paramStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    function http_post($url, $param,$timeout=120)
    {
        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    function do_explode($src,$condition = ','){
        $data = explode($condition,$src);
        foreach ($data as $k=> $v) {
            if($v == null || $v == '')
                unset($data[$k]);
        }
        return $data;
    }

    //读取文件内容,大文件另作考虑
    function readlog($file_path){
        $fp = fopen($file_path, "r");
        $logs=array();
        $i=0;
        while(! feof($fp))
        {
            $logs[$i]= fgets($fp);//fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($fp);
        $user=array_filter($logs);
        return $user;
    }

    //获取文件列表
    function dir_file_list($dirpath){
        $dr = [];
        if(is_dir($dirpath)){
            $dp = opendir($dirpath);
            while(false !== ($file=readdir($dp))){
                if($file=='.' || $file=='..'){
                    continue;
                }
                $rfile = rtrim($dirpath,'/') .'/'. $file;
                if(is_dir($rfile)){
                    $dr = array_merge($dr,dir_file_list($rfile));
                }else{
                    $dr[] = $rfile;
                }
            }
            closedir ( $dp );
            return $dr;
        }else{
            return $dr;
        }
    }

    //返回excel文件内容(数组格式)
    function excel_reader($val){
        $Reader = new writethesky\PHPExcelReader\PHPExcelReader($val);
        $total = $Reader->count();
        $data = [];
        // 逐行获取数据
        foreach($Reader as $key => $row){
            $data[] = $row;
        }
        // 总行数,数据数组
        return array($total,$data);
    }
}