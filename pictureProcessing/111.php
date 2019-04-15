<meta charset='utf-8'>
<?php
    $file = filesize("E:\WorkSpace\company\dis_and_pest\\ftp\\pest\\4");
    print_r($file);
    exit;
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