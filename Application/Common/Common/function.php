<?php

use Org\Util\String;
/**
 * 二维转字符串
 * @param type $arr
 * @return type
 */
function arr2str ($arr)
{
    foreach ($arr as $v)
    {
        $v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
        $temp[] = $v;
    }
    $t="";
    foreach($temp as $v){
        $t.=$v.",";
    }
    $t=substr($t,0,-1);
    return $t;
}


function upload($file){
    $upload = new \Think\Upload();
    $upload->maxSize = 3145728;
                        
    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
    $upload->rootPath  =      '.'; // 设置附件上传根目录
    $upload->savePath  =      '/Upload/images/web/logo/';
    $info = $upload->uploadOne($file);
    return $info;
    
    
    
}


/**
 * 气象汇总周期通用方法
 * @param string $tableName  汇总查询的数据表名称（不带前缀）
 * @param int $startTime  时间区间查询  开始时间
 * @param int $endTime    时间区间查询  结束时间
 * @param int $start      分页开始位置
 * @param int $length     分页长度
 * @param string $device_code  设备编号
 * @return array          datatables插件需要使用的数据包括 总数据，总数据条数，过滤数据条数
 */
    function getEnvCollect($listSumData,$startTime,$endTime,$start,$length,$device_code){
        $map = array();
        if($startTime&&$endTime){
            $map['unix_starttime'] = array(array("gt", $startTime), array("lt", $endTime));
            $map['unix_endtime'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        switch ($listSumData) {
                    /* 当汇总周期为小时时 */
                    case "hours":
                        $tableName = "env_collect_hours";
                        break;
                    case "day":
                        $tableName = "env_collect_day";
                        break;
                    case "week":
                        $tableName = "env_collect_week";
                        break;
                    case 'month':
                        $tableName = "env_collect_month";
                        break;
                    case "year":
                        $tableName = "env_collect_year";
                        break;
                    default :
                        return "汇总周期为空";
                        break;
                };

        $where['device_code'] = $device_code;
        if(($start==0||$start)&&$length){
             $result = D($tableName)->where($where)
                    ->where($map)
                    ->limit($start, $length)
                    ->order("unix_starttime desc")
                    ->select();
        }else{
             $result = D($tableName)->where($where)
                    ->where($map)
                    ->order("unix_starttime desc")
                    ->select();
        }
        $recordsTotal =   D($tableName)->where($where)->count();
        $recordsFiltered  = D($tableName)->where($where)->where($map)->count();
      /* 获取插件必须返回的数据值 */
        //进行数组分页
        $data = array(
            "data" => $result,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered
        );
        return $data;
    }

    
/**
 *信息写入日志文件 
 *
 * @param string $url   文件的路径和文件名及后缀
 * @param string $array 要保存的日志信息，数组形式
 * @param $logType      要保存的日志类型，如'警告','错误','正常信息' 可输入汉字 
 * @return $dd          返回是否保存成功
 */
function WriterLog($url,$string,$logType){

	$time = date("Y-m-d H:i:s");
	
	if(!empty($string)){
		$fp = fopen($url, "a");
	
		$dd = fwrite($fp, $time."\r\n".$logType.": ".$error."\r\n**************************************\r\n");
		
		fclose($fp);
		return  $dd;
}
}





/**
 * 传递数据以易于阅读的样式格式化后输出
 * @param type $data    要输出的数据
 */
function p($data) {
    // 定义样式
    $str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data = $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        $show_data = 'null';
    } else {
        $show_data = print_r($data, true);
    }
    $str .= $show_data;
    $str .= '</pre>';
    echo $str;
}
/**
 * 生成tree树状结构
 * @param array $items   数据库得到的数组
 * @param string $id     数据的唯一标识
 * @param string $pid    父类元素   
 * @param string $son    子类元素
 */
function getTree($items, $id = 'role_id', $pid = 'role_parent_id', $son = 'children') {
    $tree = array(); //格式化的树
    $tmpMap = array();  //临时扁平数据

    foreach ($items as $item) {
        $tmpMap[$item[$id]] = $item;
    }

    foreach ($items as $item) {
        if (isset($tmpMap[$item[$pid]])) {
            $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
        } else {
            $tree[] = &$tmpMap[$item[$id]];
        }
    }
    unset($tmpMap);
    return $tree;
}

/* * **************************分隔符************************************** */

/**
 * 验证码检查 
 */
function check_verify($code, $id = "") {
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 数组key加前缀
 * @param string $prefix   //前缀
 * @param array $arr       //需要给key加前缀的数组
 * @return array           //加完前缀以后的数组
 */
function addPrefix($prefix = '', $arr = array()) {
    foreach ($arr as $key => $value) {
        $key = $prefix . $key;
        $array[$key] = $value;
    }
    return $array;
    //p($array);
}

/**
 * 数组key减前缀
 * @param string $prefix   //前缀
 * @param array $arr       //需要给key减前缀的数组
 * @return array           减完前缀以后的数组
 */
function delPrefix($prefix = '', $arr = array()) {
    foreach ($arr as $key => $value) {
        $key = str_replace($prefix, "", $key);
        $array[$key] = $value;
    }
    return $array;
}

/**
 * 字符串截取，支持中文和其他编码
 * @param unknown $str
 * @param number $start
 * @param unknown $length
 * @param string $charset
 * @param string $suffix
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

/**
 * 角度转换风向
 */
function getWindDirection($angle) {
	if($angle == "-"){
		return "-";
	}
    if ($angle > 0 && $angle < 90) {
        return "东北";
    }
    if ($angle == 90) {
        return "东";
    }
    if ($angle > 90 && $angle < 180) {
        return "东南";
    }
    if ($angle == 180) {
        return "南";
    }
    if ($angle > 180 && $angle < 270) {
        return "西南";
    }
    if ($angle == 270) {
        return "西";
    }
    if ($angle > 270 && $angle < 359) {
        return "西北";
    }
    if ($angle == 359) {
        return "北";
    }
}

/**
 * 虫害汇总周期通用方法
 * @param array $pestRes //需要进行汇总的数组
 * @param int $startTime //限制时间的时间戳，若没有  可留空
 * @param int $endTime      //限制时间的时间戳，若没有  可留空
 * @param int $sumTime  //汇总的方式时间戳  按 小时3600  天64800  周  月 年
 * @param int $start       //分页开始
 * @param int $length      //分页长度
 * @return  array       //返回一个数组  ，给插件使用
 */
function pestSumFunction($pestRes, $startTime = "", $endTime = "", $sumTime, $name,$start, $length) {
    $sumData = array();   //汇总周期新数组
    $newSumData = array();
    if ($startTime == "" || $endTime == "") {
        /* 对pestRes数组进行相关操作，获取第一个和最后一个元素 */
        //获取数组第一个元素
//        p($envRes);
        $arrStartTime = $pestRes[0]['createdate'];
//        p($arrStartTime);
//        p(date('Y-m-d H:i:s',$arrStartTime));
//        exit;
//        p($arrStartTime);
        //获取数组最后一个元素
        $arrEndTime = end($pestRes)['createdate'];
        $beginUnix = getTime($arrStartTime,$name)['beginTime'];
        $endUnix = getTime($arrEndTime,$name)['endTime'];
    } else {
        $startUnix = $startTime;
        $endUnix = $endTime;
        $beginUnix = getTime($startUnix,$name)['beginTime'];
        $endUnix = getTime($endUnix,$name)['endTime'];
//       p(date('Y-m-d H:i:s',$beginUnix));
//       p($endUnix);
//         p(date('Y-m-d H:i:s',$endUnix));
//       exit;
    }

    /*
     * 简述这个while循环的作用
     * 1.判断开始时间是否小于结束时间
     * 2.foreach循环遍历$pestRes数组，如果数组当中的createdate小于开始时间+1小时且大于开始时间，进行第三步
     * 3.将在这个时间的所有数据，存入到一个以$hoursStartTime+3600为key值得二级数组中，并对$i加1
     * 4.对$hoursStartTime进行加3600操作，即加一个小时
     */
    while ($beginUnix < $endUnix) {
        $i = 0;
        foreach ($pestRes as $key => $value) {
            
            if ($value['createdate'] <= $beginUnix + $sumTime && $value['createdate'] > $beginUnix) {
                $sumData[getTime($beginUnix,$name)['endTime']][$i] = $value;
                $i += 1;
            }
        }
        $beginUnix += $sumTime;
    }
   
    
    $j = 0;
    foreach ($sumData as $ke => $val) {
        foreach ($val as $v) {							
            $arr[$j]['pd_id'] = $j;
            $arr[$j]['baie_num'] = $v['baie_num'] + $arr[$j]['baie_num'];
            $arr[$j]['yxz_num'] = $v['yxz_num'] + $arr[$j]['yxz_num'];
            $arr[$j]['wde_num'] = $v['wde_num'] + $arr[$j]['wde_num'];
            $arr[$j]['clj_num'] = $v['clj_num'] + $arr[$j]['clj_num'];
            $arr[$j]['cch_num'] = $v['cch_num'] + $arr[$j]['cch_num'];
             $arr[$j]['other_num'] = $v['other_num'] + $arr[$j]['other_num'];
            $arr[$j]['temp'] = $v['temp'] + $arr[$j]['temp'];
            $arr[$j]['humidity'] = $v['humidity'] + $arr[$j]['humidity'];
            $arr[$j]['device_code'] = $v['device_code'];
            $arr[$j]['endtime'] = $ke;
            $arr[$j]['starttime'] =getTime($ke,$name)['beginTime'];
            $arr[$j]['createdate'] = 0;
        }
        $arr[$j]['temp'] = $arr[$j]['temp'] / sizeof($val);
        $arr[$j]['humidity'] = $arr[$j]['humidity'] / sizeof($val);
        $j++;
    }
    $pest_res = array_slice($arr, $start, $length);

    /* 获取插件必须返回的数据值 */
    $recordsTotal = count($arr);
    $recordsFiltered = count($sumData);
    //进行数组分页
    $data = array(
        "data" => $pest_res,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered
    );
    return $data;
}


function envSumFunction1($envRes, $startTime = "", $endTime = "", $sumTime, $start, $length) {
    $sumData = array();   //汇总周期新数组
    $newSumData = array();
    if ($startTime == "" || $endTime == "") {
        /* 对pestRes数组进行相关操作，获取第一个和最后一个元素 */
        //获取数组第一个元素
//        p($envRes);
        $arrEndTime = $envRes[0]['unix_addtime'];
        
//        p($arrStartTime);
        //获取数组最后一个元素
        $arrStartTime = end($envRes)['unix_addtime'];
//        p($arrEndTime);
        
        //获取按小时汇总时，最大和最小时间   0000-00-00 00:00:00格式
        
        $hoursStartTime = strtotime(date("Y-m-d ", $arrStartTime) . "  00:00:00");
//	                        p($hoursStartTime);
        $hoursEndTime = strtotime(date("Y-m-d  ", $arrEndTime + $sumTime) . "  00:00:00");
        
//	                        p($hoursEndTime);
    } else {
        $hoursStartTime = strtotime(date("Y-m-d  ", $startTime) . "  00:00:00");
//                        p($hoursStartTime);
        $hoursEndTime = strtotime(date("Y-m-d  ", $endTime + $sumTime) . "  00:00:00");
//                        p($hoursEndTime);
    }


    /*
     * 简述这个while循环的作用
     * 1.判断开始时间是否小于结束时间
     * 2.foreach循环遍历$envRes数组，如果数组当中的addtime小于开始时间+1小时且大于开始时间，进行第三步
     * 3.将在这个时间的所有数据，存入到一个以$hoursStartTime+3600为key值得二级数组中，并对$i加1
     * 4.对$hoursStartTime进行加3600操作，即加一个小时
     */
    while ($hoursStartTime < $hoursEndTime) {
        $i = 0;
        foreach ($envRes as $key => $value) {
                
            if ($value['unix_addtime'] <= $hoursStartTime + $sumTime && $value['unix_addtime'] > $hoursStartTime) {
                $sumData[$hoursStartTime + $sumTime][$i] = $value;
                $i += 1;
            }
        }
        $hoursStartTime += $sumTime;
    }
    $j = 0;
    foreach ($sumData as $ke => $val) {
//							p($val);

        foreach ($val as $v) {
//								p($v);
            $arr[$j]['env_id'] = $j;
            $arr[$j]['temp'] = $v['temp'] + $arr[$j]['temp'];
            $arr[$j]['humidity'] = $v['humidity'] + $arr[$j]['humidity'];
            $arr[$j]['wind_speed'] = $v['wind_speed'] + $arr[$j]['wind_speed'];
            $arr[$j]['wind_direction'] = $v['wind_direction'];
            $arr[$j]['beam'] = $v['beam'] + $arr[$j]['beam'];
			$arr[$j]['current'] = $v['current'] + $arr[$j]['current'];
			$arr[$j]['voltage'] = $v['voltage'] + $arr[$j]['voltage'];
            
            

            $arr[$j]['device_code'] = $v['device_code'];
            $arr[$j]['endtime'] = $ke;
            $arr[$j]['starttime'] = $ke - $sumTime;
            $arr[$j]['unix_addtime'] = 0;
             $arr[$j]['createdate'] = 0;
        }
        $arr[$j]['temp'] = round($arr[$j]['temp'] / sizeof($val),2);
        $arr[$j]['humidity'] = round($arr[$j]['humidity'] / sizeof($val),2);
        $arr[$j]['wind_speed'] = round($arr[$j]['wind_speed'] / sizeof($val),2); 
        $arr[$j]['beam'] = round($arr[$j]['beam'] / sizeof($val),2);
		$arr[$j]['current'] = round($arr[$j]['current'] / sizeof($val),2);
		$arr[$j]['voltage'] = round($arr[$j]['voltage'] / sizeof($val),2);
        $j++;
    }
    $env_res = array_slice($arr, $start, $length);

    /* 获取插件必须返回的数据值 */
    $recordsTotal = count($arr);
    $recordsFiltered = count($sumData);
    //进行数组分页
    $data = array(
        "data" => $env_res,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered
    );
    return $data;
}
/**
 * php根据时间戳获取整点时间、一天开始和结束时间、一周开始和结束时间、一月开始和结束时间、一年开始和结束时间。
 * auth:635465650@qq.com
 * date:2017-10-24 11:40:12
 * $time:时间戳
 * 
 */
function getTime($now,$q){
    switch ($q) {
        case "hours":
            $beginTime = strtotime(date('Y-m-d H:00:00', $now)); 
            $endTime = strtotime(date('Y-m-d H:59:59',$now));
            break;
        case "day":
            $beginTime = strtotime(date('Y-m-d 00:00:00', $now));    
            $endTime = strtotime(date('Y-m-d 23:59:59', $now));
        break;
        case "week":
            $time = '1' == date('w') ? strtotime('Monday', $now) : strtotime('last Monday', $now);    
            $beginTime = strtotime(date('Y-m-d 00:00:00', $time));    
            $endTime = strtotime(date('Y-m-d 23:59:59', strtotime('Sunday', $now))); 
        break;
        case "month":
            $beginTime = strtotime(date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m', $now), '1', date('Y', $now))));    
            $endTime = strtotime(date('Y-m-d 23:59:59', mktime(0, 0, 0, date('m', $now), date('t', $now), date('Y', $now))));
        break;
        case "year":
            $beginTime = strtotime(date('Y-m-d 00:00:00', mktime(0, 0,0, 1, 1, date('Y', $now))));    
            $endTime = strtotime(date('Y-m-d 23:59:59', mktime(0, 0, 0, 12, 31, date('Y', $now))));  
        break;
        
    }
    return array('beginTime'=>$beginTime,'endTime'=>$endTime);
}

 /**
 * 客户端向服务器发送数据
 * @param string $ip 服务器ip地址
 * @param int $port  端口号
 * @param string $data 需要发送的数据（数组）
 * @return boolean 操作是否成功
 */
function client($ip,$port,$data){  
    error_reporting(E_ALL);
    set_time_limit(0);
    $temp = "";
    $out = '';
    $str_msg = toStr($data);
    $strLen = strlen($str_msg);
    $packet = pack("a{$strLen}", $str_msg);
    $pckLen = strlen($packet);

    /*
     +-------------------------------
     *    @socket连接整个过程
     +-------------------------------
     *    @socket_create
     *    @socket_connect
     *    @socket_write
     *    @socket_read
     *    @socket_close
     +--------------------------------
    */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>10, "usec"=>0 ) )){
            return false;
    }
    if ($socket < 0) {
        return false;
    } 
    $result = socket_connect($socket, $ip, $port);
     if ($result < 0) {
        return FALSE;
    }
        socket_write($socket, $packet,$pckLen);
        while($out = socket_read($socket, 8192)){  
            $temp.=$out;
            if(strpos($out, "a")){
                break;
            }
        }
        if($temp == "1a"){
                socket_close($socket);
            return TRUE;
        }else{
                socket_close($socket);
                return false;
        }
        
}
 
 /**
     * 将字节数组转化为String类型的数据
     * @param $bytes 字节数组
     * @param $str 目标字符串
     * @return 一个String类型的数据
     */
    
function toStr($bytes) {
        $str = '';
        foreach($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
}

