<meta charset='utf-8'>
<?php
date_default_timezone_set("PRC");
$database  = include 'db.php';

$mysql_dsn=$database['dsn']; //数据库服务器  
$mysql_server_name=$database['mysql_server_name']; //数据库服务器   
$mysql_username=$database['mysql_username']; //数据库用户名  
$mysql_password=$database['mysql_password']; //数据库密码   
$mysql_database=$database['mysql_database']; //数据库名 


//连接
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //默认是PDO::ERRMODE_SILENT, 0, (忽略错误模式)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 默认是PDO::FETCH_BOTH, 4
);

try{
    $pdo = new PDO($mysql_dsn, $mysql_username, $mysql_password, $options);
    print_r("Database connection success!\n\n");
    sleep(1);
}catch(PDOException $e){
    die('数据库连接失败:' . $e->getMessage());
}




$hours = envCollect($pdo,"hours",-1);
if($hours == "hours"){
	print_r("No hours summary data\n\n");
	sleep(1);
}else{
	if($hours){
		print_r("Hours is success\n\n");
		sleep(1);
	}else{
		print_r("Hours is fail\n\n");
		sleep(1);
	}
}



$day  = envCollect($pdo,"day",-2);
if($day == "day"){
	print_r("No day summary data\n\n");
	sleep(1);
}else{
	if($day){
		print_r("Day is success\n\n");
		sleep(1);
	}else{
		print_r("Day is fail\n\n");
		sleep(1);
	}
}



$week = envCollect($pdo,"week",-3);
if($week == "week"){
	print_r("No week summary data\n\n");
	sleep(1);
}else{
	if($week){
		print_r("Week is success\n\n");
		sleep(1);
	}else{
		print_r("Week is fail\n\n");
		sleep(1);
	}
}



$month = envCollect($pdo,"month",-4);
if($month == "month"){
	print_r("No month summary data\n\n");
	sleep(1);
}else{
	if($month){
		print_r("Month is success\n\n");
		sleep(1);
	}else{
		print_r("Month is fail\n\n");
		sleep(1);
	}
}



$year = envCollect($pdo,"year",-5);
if($year == "year"){
	print_r("No year summary data\n\n");
	sleep(1);
}else{
	if($year){
		print_r("Year is success\n\n");
		sleep(1);
	}else{
		print_r("Year is fail\n\n");
		sleep(1);
	}
}


/**
 * 环境数据汇总（按小时、天、星期、月、年）
 * @param  [obj] $pdo [数据库连接对象]
 * @param  [string] $type     [hours,day,week,month,year分别代表小时、天、星期、月、年]
 * @param  [int] $min_time [数据中最小的时间戳]
 * @param  [int] $max_time [数据中最大的时间戳]
 * @param  [string] $flag [标识符]
 * @return [type]           [description]
 */
function envCollect($pdo,$type,$flag){
	$table = "iot_env";
	$insert_row_flag = 0;
	$lastInsertId = 0;
	switch ($type) {
		case 'hours':	
			$num = "+1 hours";
			$tableName = "iot_env_collect_hours";
			$flagName = "flag_hours";
		break;
		case 'day':
			
			$num = "+1 day";
			$tableName = "iot_env_collect_day";
			$flagName = "flag_day";
		break;
		case 'week':
			
			$num = "+1 week";
			$tableName = "iot_env_collect_week";
			$flagName = "flag_week";
		break;
		case 'month':
			
			$num = "+1 month";
			$tableName = "iot_env_collect_month";
			$flagName = "flag_month";
		break;
		case 'year':
			
			$num = "+1 year";
			$tableName = "iot_env_collect_year";
			$flagName = "flag_year";
		break;
		
		default:
			# code...
			break;
	}
	
	$collect_select = $pdo->prepare("
			select uid,device_code,
			TRUNCATE(avg(avg_temp),2) as avg_temp,
			TRUNCATE(avg(avg_hum),2) as avg_hum,
			TRUNCATE(avg(avg_beam),2)  as avg_beam ,
			TRUNCATE(avg(avg_wind_speed),2)  as avg_wind_speed,
			region,province,city,county,address 
			from (
			select uid,device_code,
			TRUNCATE(avg(temp),2) as avg_temp,
			TRUNCATE(avg(humidity),2) as avg_hum,
			TRUNCATE(avg(beam),2)  as avg_beam ,
			TRUNCATE(avg(wind_speed),2)  as avg_wind_speed,
			region,province,city,county,address
			from ".$table." 
			where  unix_createdate between ? and ? and temp<>'' and humidity<>'' and beam<>'' and wind_speed<>'' and device_code = ? and unix_createdate>?
			group by device_code
			union 
			select uid,device_code,
			temp as avg_temp,
			humidity as avg_hum,
			beam  as avg_beam,
			wind_speed as avg_wind_speed,
			region,province,city,county,address
			from ".$tableName."
			where unix_starttime= ? and unix_endtime= ? and device_code = ?
			group by device_code
			)".$tableName."
			group by device_code;




			");
	$select_collect = $pdo->prepare("select * from ".$tableName." where unix_starttime = ? and unix_endtime = ? and device_code = ?
		");
	$select_env_collect = $pdo->prepare("select * from ".$tableName."");
	$collect_save = $pdo->prepare("update ".$tableName." set temp=?,humidity=?,beam=?,wind_speed=?,unix_createdate=? where unix_starttime = ? and unix_endtime =? and device_code=?");
	$select = $pdo->prepare(
		"select 
		uid, 
		device_code, 
		TRUNCATE(avg(temp),2) as avg_temp,
		TRUNCATE(avg(humidity),2) as avg_hum,
		TRUNCATE(avg(beam),2)  as avg_beam ,
		TRUNCATE(avg(wind_speed),2)  as avg_wind_speed,
		region,province,city,county,address 
		from ".$table."  
		where unix_createdate between ? and ? 
				and temp<>'' and humidity<>'' and beam<>'' and wind_speed<>'' and unix_createdate >?
       group by device_code
       order by env_id asc"
   	);
   	$startSave = $pdo->prepare("update iot_env set ".$flagName." = 0 where ".$flagName." = ".$flag." ");
	$endSave = $pdo->prepare("update iot_env set ".$flagName." = ".$flag." where unix_createdate = ?");
	$insert = $pdo->prepare("insert into ".$tableName." (uid,device_code,temp,humidity,beam,wind_speed,region,province,city,county,address,unix_starttime,unix_endtime,unix_createdate) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$arr = getMaxMinTime($pdo,$flagName,$flag);

	$min_time = $arr['min_time'];
	$max_time = $arr['max_time'];
	$time = getTime($min_time,$type);
	if($min_time == $max_time){
		// print_r(1111);
		return $type;
	}

$beginTime = $time['beginTime'];
	$endTime = $time['endTime'];
	//for循环开始
for($all=$beginTime;$all<$max_time;){

	$select->bindValue(1,$all);
	$select->bindValue(2,$endTime);
	$select->bindValue(3,$min_time);
	$select->execute();
	$result = $select->fetchAll();
	//判断数据是否为空结束
	if(!empty($result)){
		//foreach循环开始
		foreach ($result as $key => $value) {
			// print_r($value);

			$select_collect->bindValue(1,$all);
			$select_collect->bindValue(2,$endTime);
			$select_collect->bindValue(3,$value['device_code']);
			$select_collect->execute();
			$item = $select_collect->fetch();
			if($item){/*汇总表中有这条信息，就进行更新操作*/
				$device_code = $item['device_code'];
				$collect_select->bindValue(1,$all);
				$collect_select->bindValue(2,$endTime);
				$collect_select->bindValue(3,$device_code);
				$collect_select->bindValue(4,$min_time);
				$collect_select->bindValue(5,$all);
				$collect_select->bindValue(6,$endTime);
				$collect_select->bindValue(7,$device_code);
				$collect_select->execute();
				$find = $collect_select->fetch();  //单条数据
				
				//更新存在的数据
				$temp = $find['avg_temp'];
				$avg_hum = $find['avg_hum'];
				$beam = $find['avg_beam'];
				$wind_speed = $find['avg_wind_speed'];
				$collect_save->bindValue(1,$temp);
				$collect_save->bindValue(2,$avg_hum);
				$collect_save->bindValue(3,$beam);
				$collect_save->bindValue(4,$wind_speed);
                                $collect_save->bindValue(5, time());
				$collect_save->bindValue(6,$all);
				$collect_save->bindValue(7,$endTime);
				$collect_save->bindValue(8,$device_code);
				$bool = $collect_save->execute();
				if($bool){
					print_r("".$type." summary data update success\n\n");
					sleep(1);
				}else{
					print_r("".$type." summary data update failure\n\n");
					sleep(1);
					return false;
				}

			}else{/*汇总表中没有这条信息，就进行添加操作*/
				$select->bindValue(1,$all);

				$select->bindValue(2,$endTime);
				$select->bindValue(3,$min_time);
				$select->execute();
				$result = $select->fetchAll();

				foreach ($result as $key => $value) {
						$value['starttime'] = $all;
					$value['endtime'] = $endTime;
					if( 0 == $value['avg_temp']){
						$value['avg_temp'] = "";
					}
					if( 0 == $value['avg_hum']){
						$value['avg_hum'] = "";
					}
					if( 0 == $value['avg_beam']){
						$value['avg_beam'] = "";
					}
					if( 0 == $value['avg_wind_speed']){
						$value['avg_wind_speed'] = "";
					}
					$insert->bindValue(1,$value['uid']);
					$insert->bindValue(2,$value['device_code']);
					$insert->bindValue(3,$value['avg_temp']);
					$insert->bindValue(4,$value['avg_hum']);
					$insert->bindValue(5,$value['avg_beam']);
					$insert->bindValue(6,$value['avg_wind_speed']);
					$insert->bindValue(7,$value['region']);
					$insert->bindValue(8,$value['province']);
					$insert->bindValue(9,$value['city']);
					$insert->bindValue(10,$value['county']);
					$insert->bindValue(11,$value['address']);
					$insert->bindValue(12,$value['starttime']);
					$insert->bindValue(13,$value['endtime']);
					$insert->bindValue(14,time());
					$insert_row = $insert->execute();
						if(!$insert_row){
							print_r("".$type." summary data  inserrtion failuer\n\n");
							sleep(0.5);
							return false;
						}else{
							print_r("".$type." summary data insertion success\n\n");
							sleep(0.5);
						}				
				}



			}
		}//foreach循环结束
	}//判断数据是否为空结束
	
//给开始和结束时间，增加相应汇总时间
	$endTime = strtotime($num,$endTime);
	$all = strtotime($num,$all);
}//for循环结束
	// print_r($arr);

	$startSave->execute();
	$endSave->bindValue(1,$max_time);
	$boolean = $endSave->execute();
	if($boolean){
		return true;
	}else{
		return false;
	}
	exit;
}
	
	


/**
 *	获取数据中最大和最小时间戳（根据已经操作到那条数据的标识符）
 * @param  [object] $pdo      [pdo对象]
 * @param  [string] $flagName [标识符对应的字段名称]
 * @param  [int] $flag     [标识符内容]
 * @return [array]           [返回最大和最小时间戳的数组]
 */
function getMaxMinTime($pdo,$flagName,$flag){
	$table = "iot_env";
	$flagA = $pdo->prepare("select * from ".$table." 
							where ".$flagName." = ".$flag."
					       ");
	$flagA->execute();
	$item = $flagA->fetch();
	if($item){
		$min_time = $item['unix_createdate'];
	}else{
		$minStmt = $pdo->prepare("select * from ".$table." 
					       order by unix_createdate asc limit 1");
		$minStmt->execute();
		$min = $minStmt->fetch();
		if(!$min){
			return array();
		}
		//最小时间戳
		$min_time = $min['unix_createdate'];
	}

	$maxStmt = $pdo->prepare("select * from ".$table."  
						
					       order by unix_createdate desc limit 1");
	$maxStmt->execute();
	$max = $maxStmt->fetch();
	//最大时间戳
	$max_time = $max['unix_createdate'];
	return array(
		"min_time" => $min_time,
		"max_time" => $max_time
		);
}



	


/**
 * php根据时间戳获取整点时间、一天开始和结束时间、一周开始和结束时间、一月开始和结束时间、一年开始和结束时间。
 * auth:635465650@qq.com
 * date:2017-10-24 11:40:12
 * $time:时间戳
 * 
 */
function getTime($now,$q){
	date_default_timezone_set('PRC');
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
            // $time = strtotime('last Monday', $now);
            $beginTime = strtotime(date('Y-m-d 00:00:00', $time)); 
            // $beginTime = date('Y-m-d 00:00:00', $time);   
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