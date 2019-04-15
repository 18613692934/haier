<meta charset='utf-8'>
<?php
date_default_timezone_set("PRC");
$basedir = dirname(dirname(__FILE__))."\\"; 

    $pestPathArr = array();
    //虫害图片路径段 
    $pestPath = "ftp\pest";
    //病害图片路径段
    $diseasePath = "ftp\disease";
    //虫害图片新路径段
    $newPestPath =  "Upload\images\pest";
    //病害图片新路径段
    $newDisPath = "Upload\images\disease";
    //固定路径段
    $fixed = "01\pic";
    //虫害图片绝对路径
    $pestAbsPath = $basedir.$pestPath;
    //病害图片绝对路径
    $diseaseAbsPath = $basedir.$diseasePath;

    //虫害图片新绝对路径
    $newPestAbsPath = $basedir.$newPestPath;
//     echo $newPestAbsPath;
// exit;
    //病害图片绝对路径
    $newDisAbsPath = $basedir.$newDisPath;

    $pestPathArr['basedir'] = $basedir;
    $pestPathArr['pestPath'] = $pestPath;
    $pestPathArr['newPestPath'] = $newPestPath;
    $pestPathArr['fixed'] = $fixed;
    //获取虫害图片路径下的所有文件夹名称及大小（设备文件夹）
    $device_folders = scandir($pestAbsPath);

        foreach ($device_folders as $key => $device_folder) {    //循环遍历文件名数组
            if ($device_folder != "." && $device_folder != "..") {  //去除 .和 ..的文件名
                if (!file_exists($newPestAbsPath."\\".$device_folder)) {  //判断文件夹中是否有设备文件夹
                    $bool = mkdir($newPestAbsPath."\\".$device_folder);    //创建文件夹              
                }
                $pestPathArr['device_folder'] = $device_folder;
                    $newDevicePath = $newPestAbsPath."\\".$device_folder;
                        
                    $size = getDirSize($pestAbsPath."\\".$device_folder);
                    if($size > 10){

                        // getTime($pestAbsPath."\\".$device_folder,$pestPathArr);
                        getTime($pestAbsPath."\\".$device_folder,$pestPathArr);
                    }               
            }
        }


        /*
        跳过设备文件夹下，多余的一个文件夹目录
         */
        function getFixed($fixesPath,$pestPathArr){
        	$fixeds = scandir($fixesPath);
        	// print_r($fixeds);
        	// exit;
        	foreach ($fixeds as $key => $data_fixed) {    //循环遍历文件名数组
	            if ($data_fixed != "." && $data_fixed != "..") {  //去除 .和 ..的文件名
	               
	                $pestPathArr['first_fixed'] = $data_fixed;
	                
                    getTime($fixesPath."\\".$pestPathArr['first_fixed'],$pestPathArr);  
	                
	       		}
        	}



        }

        
    function getTime($folderPath,$pestPathArr){

        $date_folders = scandir($folderPath);
        $newDevicePath = $pestPathArr['basedir'].$pestPathArr['newPestPath']."\\".$pestPathArr['device_folder'];

        foreach ($date_folders as $key => $date_folder) {    //循环遍历文件名数组
            if ($date_folder != "." && $date_folder != "..") {  //去除 .和 ..的文件名
                    $boolean  = strtotime($date_folder);
                if($boolean){
                    if (!file_exists($newDevicePath."\\".$date_folder)) {  //判断文件夹中是否有设备文件夹
                        $bool = mkdir($newDevicePath."\\".$date_folder);    //创建文件夹
                    }
                    $pestPathArr['date_folder'] = $date_folder;
                    $size = getDirSize($folderPath."\\".$date_folder);
                    if($size > 10){
                        getImg($folderPath."\\".$date_folder."\\".$pestPathArr['fixed'],$pestPathArr);
                    }
                }else{
                    getFixed($folderPath,$pestPathArr);
                }
   
                
            }
        }
    }     
       
    
    function getImg($folderPath,$pestPathArr){
       $img_files =  scandir($folderPath);

       foreach ($img_files as $key => $img_file) {    //循环遍历文件名数组
            if ($img_file != "." && $img_file != "..") {  //去除 .和 ..的文件名

                $imgName = $img_file;
 // print_r($pestPathArr);
 // 
 // exit;
                     $createdate = filemtime($folderPath."\\".$imgName);

                     $pestPathArr['createdate'] = $createdate;
                $newDatePath = $pestPathArr['basedir'].$pestPathArr['newPestPath']."\\".$pestPathArr['device_folder']."\\".$pestPathArr['date_folder'];
print_r("**************************************************\n\n");
sleep(1);
print_r("Start moving picture waiting...\n\n");
sleep(1);
                $bool = rename($folderPath."\\".$imgName,$newDatePath."\\".$imgName);
                
                
                if($bool){
                	print_r("Picture mobile sucess\n\n");
sleep(1);
                    $newAbsPath = $pestPathArr['basedir'].$pestPathArr['newPestPath']."\\".$pestPathArr['device_folder']."\\".$pestPathArr['date_folder'];
                    $newPath = "\\".$pestPathArr['newPestPath']."\\".$pestPathArr['device_folder']."\\".$pestPathArr['date_folder'];
                    $absSrc = $newAbsPath."\\".$imgName;
                    $thumAbsFilename =  $newAbsPath."\\thum_".$imgName;
                   

                    print_r("Create thumbnail waiting ...\n\n");
                    sleep(1);
                    $boolean = mkThumbnail($absSrc,400,null,$thumAbsFilename);
                    
                    if($boolean){
                    	$newPath = str_replace("\\","/",$newPath);
                    	$src = $newPath."/".$imgName;
                    	$thumFilename =  $newPath."/thum_".$imgName;
                    	
                    	$h = substr($imgName,0,2);
	                    $m = substr($imgName,2,2);
	                    $s = substr($imgName,4,2);

                     	$data = array(
	                        "device_code"=> $pestPathArr['device_folder'],
	                        "unix_addtime" => strtotime($pestPathArr['date_folder']." ".$h.":".$m.":".$s),
	                        "addtime"=> $pestPathArr['date_folder']." ".$h.":".$m.":".$s,
	                        "image" => $src,
	                       
	                        "thum_image" => $thumFilename,
	                        "createdate"=>$createdate,
                    	);
                     	print_r("The success of the thumbnail creation\n\n");
                     	sleep(1);
                     	print_r("The name of the thumbnail is ".$thumFilename."\n\n");
                     	sleep(1);
                     	print_r("Date is ".$data['addtime']."\n\n");
                     	sleep(1);
                     	print_r("Inserting data into the database...\n\n");
                     	sleep(1);
                 	 	$bool = addDatabase($data);
                 	 	if($bool){
                 	 		print_r("Data insertion success \n\n");
                 	 		sleep(1);
                 	 	}else{
                 	 		print_r("Data insertion failure - info:".$bool."\n\n Re insert..\n\n");
                 	 		sleep(1);
                 	 		addDatabase($data);
                 	 	}

                    }else{
                    	$print_r("Thumbnail generation failure ! \n\n Regenerate...");
                    	sleep(1);
                    	getImg($folderPath,$pestPathArr);
                    }

                    
                    
                    
                   
                    // echo $newPath;
                    // echo $src;
                    // echo $thumFilename;
                    // echo $medFilename;
                    // exit;
                   
                    
                        
                  
                 
                    
                   
                }else{
                	print_r("Picture movement failure\n\n Removement\n\n");
                	getImg($folderPath,$pestPathArr);
                }
            }

        }

    }
    /**
     * 数据插入到数据库
     * @param [array] $array [数据数组]
     * @return   [返回正确或错误]
     */
    function addDatabase($data){
	$database  = include 'db.php';
        // print_r($data);
        // exit;
        $device_code = $data['device_code'];
        $unix_addtime = $data['unix_addtime'];
        $addtime = $data['addtime'];
        $image = $data['image'];
        
        $thum_image = $data['thum_image'];
        $createdate = $data['createdate'];
        /**
         * [数据库参数]
         * @var string
         */
        $mysql_server_name=$database['mysql_server_name']; //数据库服务器   
        $mysql_username=$database['mysql_username']; //数据库用户名  
        $mysql_password=$database['mysql_password']; //数据库密码   
        $mysql_database=$database['mysql_database']; //数据库名 



        $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ; //连接数据库   
        mysql_query("set names 'utf8'"); //数据库输出编码 应该与你的数据库编码保持一致.
        mysql_select_db($mysql_database); //打开数据库   
       $sql = "insert into iot_pest_data (device_code,unix_addtime,addtime,image,thum_image,unix_createdate) values ('$device_code','$unix_addtime','$addtime','$image','$thum_image','$createdate')";  
        $result = mysql_query($sql,$conn); //插入 

        return $result;
    }  



/** 
 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp） 
 * @author ruxing.li 
 * @param  string $src      源图片路径 
 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放） 
 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放） 
 * @param  string $filename 保存路径（不指定时直接输出到浏览器） 
 * @return bool 
 */  
function mkThumbnail($src, $width = null, $height = null, $filename = null) {  
    if (!isset($width) && !isset($height))  
        return false;  
    if (isset($width) && $width <= 0)  
        return false;  
    if (isset($height) && $height <= 0)  
        return false;  
  
    $size = getimagesize($src);  
    if (!$size)  
        return false;  
  
    list($src_w, $src_h, $src_type) = $size;  
    $src_mime = $size['mime'];  
    switch($src_type) {  
        case 1 :  
            $img_type = 'gif';  
            break;  
        case 2 :  
            $img_type = 'jpeg';  
            break;  
        case 3 :  
            $img_type = 'png';  
            break;  
        case 15 :  
            $img_type = 'wbmp';  
            break;  
        default :  
            return false;  
    }  
  
    if (!isset($width))  
        $width = $src_w * ($height / $src_h);  
    if (!isset($height))  
        $height = $src_h * ($width / $src_w);  
  
    $imagecreatefunc = 'imagecreatefrom' . $img_type;  
    $src_img = $imagecreatefunc($src);  
    $dest_img = imagecreatetruecolor($width, $height);  
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);  
  
    $imagefunc = 'image' . $img_type;  
    if ($filename) {  
        $imagefunc($dest_img, $filename);  
    } else {  
        header('Content-Type: ' . $src_mime);  
        $imagefunc($dest_img);  
    }  
    imagedestroy($src_img);  
    imagedestroy($dest_img);  
    return true;  
} 



function getDirSize($dir) {
	$sizeResult = '';
    $handle = opendir($dir);
    while (false !== ($FolderOrFile = readdir($handle))) {
        if ($FolderOrFile != "." && $FolderOrFile != "..") {
            if (is_dir("$dir/$FolderOrFile")) {
                $sizeResult += getDirSize("$dir/$FolderOrFile");
            } else {
                $sizeResult += filesize("$dir/$FolderOrFile");
            }
        }
    }
    closedir($handle);
    return $sizeResult;
}