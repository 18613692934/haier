<?php
//每天早上备份一次 MySQL 数据库并自动打包,同时删除 5 天前的备份文件  建议在本地运行没有任何问题再传到服务器上 以后就可以添加计划任务
////////////////////////*/
//保存目录,路径要用反斜杠.您需要手动建立它.
//WinRAR.exe  要把压缩包的exe文件跟当前运行页面放在同一个目录，当然你也可以自己配置，这样才能压缩
ini_set("max_execution_time", "0");//代码运行时间不限制  防止备份失败
ini_set('memory_limit', '128M');//设置内存 根据需求可以修改
date_default_timezone_set("PRC");
$store_folder = 'F:\haier\databse_backup';
if(!file_exists($store_folder)) 
{
   mkdir($store_folder);
}
//用户名和密码
//该帐号须有操作[所有]的数据库及FILE的权限

//否则有些数据库不能备份.
$db_username = "root";
$db_password = "root";
$time=time();
$nowdir = $store_folder."\\".date("Ymd",$time)."";
$con = mysqli_connect("localhost", "root", "root");
if(!file_exists($nowdir)) 
{
   mkdir($nowdir);
}
if(!$con) 
{
   die('Could not connect: ' . mysqli_connect_error());
}
print_r("Do not close the page for backup....\n\n") ;
// if(ob_get_level()>0) ob_flush();  
// flush();  
sleep(1); 
//数据库执行文件地址 
$mysqladdres="F:\haier\PhpStudy\MySQL\bin\mysqldump.exe";//我的服务器是 phpmystudy  根据自己的情况设置
$res = mysqli_query($con,'show databases');
$data = array();
echo str_repeat(" ", 4096);   //防止浏览器的缓存
while ($row = mysqli_fetch_assoc($res))
{
    $data[] = $row['Database'];
    system("\"".$mysqladdres."\"".' --opt --routines '."$row[Database] -u{$db_username} ".($db_password?"-p{$db_password}":"")."  --skip-lock-tables > $nowdir\\$row[Database].sql");
    print_r("dumping database `$row[Database]`...\n\n");
    if(ob_get_level()>0) ob_flush() ; 
    flush();  
    sleep(1); 
}


if(ob_get_level()>0) ob_flush();  
flush();  
sleep(1); 
echo "\nOK!</br>";
mysqli_close($con);
if(ob_get_level()>0)ob_end_flush(); 
?>