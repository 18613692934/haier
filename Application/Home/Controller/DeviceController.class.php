<?php

namespace Home\Controller;

use Common\Controller\HomeDeviceBaseController;

class DeviceController extends HomeDeviceBaseController {

    private $device;
    private $deviceData;
    private $userData;
    private $user;
    private $user_id;
    private $env;
    private $pest;
    private $device_id;
    private $command ;
    private $device_command ;
    private $port;
    private $ip;
    private $sex;

    public function __construct() {
        parent::__construct();
        $this->device_id = cookie("device");
        $this->user_id = cookie('user');
        $this->device = D("device");
         $this->pest = D("pest_data");
        $this->user = D('user');
        $this->env = D("env");
        $this->command =D("command");
        $this->device_command =D("device_command");
        $this->port = C('PORT');
        $this->ip = C('IP');
        $this->deviceData = $this->device->where(array("device_id" => $this->device_id))->find();
         $userData = $this->user->where(array("user_id"=>$this->user_id))->find();
        /*给予超级管理员一个标记，当标记为all时，判断为超级管理员，显示所有的设备情况*/
        $this->sex = $userData['sex']; 
    }

     /*命令发送*/
    public function manage(){
    
    if(IS_POST){
        $param = I("param.");
        
        switch ($param['type']) {
            //装置开关时间设定
            case 'switch':
                $startItem = $this->command->where(array("cmd_re"=>'startTime'))->find();
                $starttime = explode(":",$param['startTime'] );
                $startHours =$starttime[0];
                $startMin = $starttime[1];
                
              
          
                $endItem = $this->command->where(array("cmd_re"=>'endTime'))->find();
                $endtime = explode(":",$param['endTime'] );
                $endHours =$endtime[0];
                $endMin = $endtime[1];
                $startData = array(
                    170,
                    204,
                    $startItem['cmd_cate'],
                    $this->deviceData['device_code'],
                    $startItem['cmd_code'],
                    $startHours,
                    $startMin,
     
                );
//                p($startData);
//                exit;
                $endData = array(
                    170,
                    204,
                    $endItem['cmd_cate'],
                    $this->deviceData['device_code'],
                    $endItem['cmd_code'],
                    $endHours,
                    $endMin
                
                );
                $sbool = client($this->ip, $this->port, $startData);
                    if($sbool){
                        $ebool = client($this->ip, $this->port, $endData);
                        if($ebool){
                            $this->ajaxReturn("命令执行成功");
                        }
                    }
                $this->ajaxReturn("命令执行失败");
                break;
            //球机
            case 'onOff':
                 $item = $this->command->where(array("cmd_re"=>$param['type']))->find();
                 $demoData = array(
                    170,
                    204,
                    $item['cmd_cate'],
                    $this->deviceData['device_code'],
                    $item['cmd_code'],
                    0,
                    $param['onOff'],
     
                );
                  $bool = client($this->ip, $this->port, $demoData);
                  if($bool){
                        $this->ajaxReturn("命令执行成功");
                    }else{
                        $this->ajaxReturn("命令执行失败");
                    }
                break;
            //拍照间隔
            case 'cameraInterval':
                    
                    $item = $this->command->where(array("cmd_re"=>$param['type']))->find();
                    $data = array(
                    170,
                    204,
                    $item['cmd_cate'],
                    $this->deviceData['device_code'],
                    $item['cmd_code'],
                    0,
                    $param['cameraInterval'],
     
                );
                  $bool = client($this->ip, $this->port, $data);
                  if($bool){
                        $this->ajaxReturn("命令执行成功");
                    }else{
                        $this->ajaxReturn("命令执行失败");
                    }
                break;
             case 'dataInterval':
                    $item = $this->command->where(array("cmd_re"=>$param['type']))->find();
                    $data = array(
                    170,
                    204,
                    $item['cmd_cate'],
                    $this->deviceData['device_code'],
                    $item['cmd_code'],
                    0,
                    $param['dataInterval'],
     
                );
                  $bool = client($this->ip, $this->port, $data);
                  if($bool){
                        $this->ajaxReturn("命令执行成功");
                    }else{
                        $this->ajaxReturn("命令执行失败");
                    }
                break;
           
        }

            $this->ajaxReturn($bool);

        }
        if(IS_GET){
            $device_code = $this->deviceData['device_code'];
            $cameraInterval = $this->device_command->where(array("device_code"=>$device_code,"cmd_code"=>37))->find();
             $dataInterval = $this->device_command->where(array("device_code"=>$device_code,"cmd_code"=>38))->find();
              $startTime = $this->device_command->where(array("device_code"=>$device_code,"cmd_code"=>39))->find();
              if(!empty($startTime['default'])){
                $start = substr($startTime['default'], 0, 2);
                $start.=":";
                $start .= substr($startTime['default'],2,2);
              }else{
                  $start = "";
              }
                $endTime = $this->device_command->where(array("device_code"=>$device_code,"cmd_code"=>40))->find();

              if(!empty($endTime['default'])){
                $end = substr($endTime['default'], 0, 2);
                 $end.=":";
              $end .= substr($endTime['default'],2,2);
              }else{
                  $end = "";
              }
              
                 
              $onOff = $this->device_command->where(array("device_code"=>$device_code,"cmd_code"=>41))->find();
            $assign = array(
               "cameraInterval" => intval($cameraInterval['default']),
                "dataInterval" =>intval($dataInterval['default']),
                "startTime" =>$start,
                "endTime" =>$end,
                "onOff" => intval($onOff['default']),
                
               );

            $this->assign($assign);
            $this->display();
        }
   
      
    }  
    
        
    public function pest() {
        
        //获取设备唯一编码
        $device_code = $this->deviceData['device_code'];
        
        $where = array("device_code" => $device_code);
        $where['pn_category_name'] = array("neq","");
        //获取虫害所有数据，及5种虫害的分别的数量和
        $category_sum =  D("pest_number")->where($where)->field("sum(pn_number) as pn_number,pn_category_name")->group("pn_category_name")->select();
        $sum =  D("pest_number")->where($where)->field("sum(pn_number) as pn_number_sum")->select();
//        p($category_sum);
//        p($sum);

        $this->assign(array( "device_code" => $device_code));
        $this->assign("category_sum",$category_sum);
        $this->assign("sum",$sum);
        $this->display();
    }
    
    function pestInfo(){
         
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */

        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $device_where = array("device_code" => $device_code, "imgstatus" => 1);
        $where = array();
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $pestInfo = $this->pest    //获取分页数据
                ->where($where)
                ->where($device_where)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->select();

        $recordsTotal = $this->pest
                ->where($device_where)
                ->count();
        $recordsFiltered = $this->pest
                ->where($device_where)
                ->where($where)
                ->count();
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $pestInfo,
        );
        $this->ajaxReturn($arr);
    }
//全部导出数据方法  
        public function allPestExp()  
        {  
            $post = I('post.');
            $startTime = strtotime($post['startTime']);
            $endTime = strtotime($post['endTime']);
            $listSumData = $post['listSumData'];
            $device_code = $this->deviceData['device_code'];
            $device_where = array("device_code" => $device_code); 
            $where = array();
            $where['imgstatus'] = 1;
         /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {  //当开始时间和结束时间不为空时，进行区间查找
                $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
            }
        $pest_res = $this->pest
                ->where($device_where)
                ->where($where)
                ->order("unix_createdate desc")
                ->select();
//                foreach ($pest_res as $key => $value) {
//                    $pest_res[$key]['wind_direction'] = getWindDirection($value['wind_direction']);
//                }
                $goods_list = $pest_res;
//         if (!empty($listSumData)) { //当汇总周期不为空时，进行汇总
//              $data = getEnvCollect( $listSumData,$startTime, $endTime, $start, $length, $device_code);
//              $goods_list = $data['data'];
//         } 
    //链接所导出的数据表  
//        $xlsModel = D('env');  
//        $goods_list  = $xlsModel->where($where)->field('device_code,temp,humidity,wind_speed,wind_direction,beam,unix_starttime,unix_endtime,unix_createdate')->where(array("device_code"=>$this->deviceData['device_code']))->select();  
        $count=1;//导出Excel序号排列  
        $data = array();  
    //循环查询后的数据，进行每一列  
        foreach ($goods_list as $k=>$goods_info){  
            $data[$k][ID] = $count++;
            $data[$k][temp] = $goods_info['temp'];
            $data[$k][humidity] = $goods_info['humidity']; 
            $data[$k][baie_num] = $goods_info['baie_num']; 
            $data[$k][yxz_num] = $goods_info['yxz_num'];
            $data[$k][wde_num] = $goods_info['wde_num'];  
            $data[$k][cch_num] = $goods_info['cch_num']; 
            $data[$k][clj_num] = $goods_info['clj_num']; 
            $data[$k][other_num] = $goods_info['other_num'];   
            $data[$k][device_code] = $goods_info['device_code'];    
            $data[$k][unix_starttime] = $goods_info['unix_starttime']?date('Y-m-d H:i:s',$goods_info['unix_starttime']):"-";  
            $data[$k][unix_endtime] = $goods_info['unix_endtime'] ?date('Y-m-d H:i:s',$goods_info['unix_endtime']):"-"; 
            $data[$k][unix_createdate] = $goods_info['unix_createdate']?date('Y-m-d H:i:s', $goods_info['unix_createdate']):"-";   
        }  
    //每列表的名称  
        foreach ($data as $field=>$v){  
           if($field == 'ID'){  
                $headArr[]='编号';  
            }  
            if($field == 'temp'){  
                $headArr[]='温度';  
            }  
            if($field == 'humidity'){  
                $headArr[]='湿度';  
            }  
             if($field == 'baie_num'){  
                $headArr[]='美国白蛾';  
            }  
            if($field == 'yxz_num'){  
                $headArr[]='杨小舟蛾';  
            }  
             if($field == 'wde_num'){  
                $headArr[]='舞毒蛾';  
            }  
            if($field == 'cch_num'){  
                $headArr[]='春尺蠖';  
            }  
             if($field == 'clj_num'){  
                $headArr[]='草履蚧';  
            }  
            if($field == 'other_num'){  
                $headArr[]='草履蚧';  
            }  
            if($field == 'device_code'){  
                $headArr[]='设备号';  
            } 
            if($field == 'unix_starttime'){  
                $headArr[]='开始时间';  
            }  
            if($field == 'unix_endtime'){  
                $headArr[]='结束时间';  
            }  
            if($field == 'unix_createdate'){  
                $headArr[]='采集时间';  
            }  
        }  
        $filename= $this->deviceData['device_code']."号环境数据表";//所导出的保存文件名称  
        $sss=$this->getExcel($filename,$headArr,$data);//调用导出引用方法  
    }  
    public function pestList() {
         $de_res = $this->device->where(array("device_id" => $this->device_id))->find();   //获取设备
        $device_code = $de_res['device_code'];   //获取设备唯一编码值
        /* 条件查询参数 */
        $where = array("device_code" => $device_code, "imgstatus" => 1);
        if($this->sex != "all"){
//             $where['baie_num']
            
//            $where['temp&humidity'] = array("neq","");
//            $where['baie_num|cch_num|yxz_num|wde_num|clj_num|other_num'] = array("neq",0);
        }
       
        
//        $pestRes = $this->pest     //根据设备编码获取所有的虫害数据,按时间升序排序
//                ->where($where)
//                ->order("unix_createdate asc")
//                ->select();
//            p($this->pest->getLastSql());
//					   p($pestRes);
        /* 获取datatables插件发送到后台来的数据 */
        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        $order_num = I("order")[0][column];
        $order_dir = I("order")[0][dir];
        $order_name = I("columns")[$order_num][data];
//        p($order_dir);
//        p($order_name);
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $listSumData = I("listSumData");  //汇总周期
        $sumData = array();   //汇总周期新数组
        $newSumData = array();
        $data;
        $order = $order_name." ".$order_dir;
       
//     /*判断sumData是否有值*/
//     if($sumData){
//         $recordsFiltered = count($sumData); //条件过滤后的数据总计
//     }
        /* 对前台搜索栏中的数据进行判断和过滤 */
        $time_where = array();
        if (!empty($startTime) && !empty($endTime)){
              $time_where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        
         /* 通过插件数据，进行分页 */
        $pest_res = $this->pest
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                ->where($where)
                ->where($time_where)
                ->limit($start, $length)
                ->order($order)
                ->select();
//        p($pest_res);exit;
        
        $recordsTotal = $this->pest
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                ->where($where)
                ->count();
        $recordsFiltered = $this->pest
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                ->where($where)
                ->where($time_where)
                ->count(); //条件过滤后的数据总计
        $lastData = array(
                    "draw" => $draw,
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" =>$pest_res,
                );
        $this->ajaxReturn($lastData);
        
//        if (!empty($startTime) && !empty($endTime)) {  //当开始时间和结束时间不为空时，进行区间查找
////            if (!empty($listSumData)) { //当汇总周期不为空时，进行汇总
////                switch ($listSumData) {
////                    /* 当汇总周期为小时时 */
////                    case "hours":
////                        
////                        $data = pestSumFunction($pestRes, $startTime, $endTime, 3600,"hours", $start, $length);
////                        break;
////                    case "tian":
////                        $data = pestSumFunction($pestRes, $startTime, $endTime, 86400,"day", $start, $length);
////                        break;
////                    case "zhou":
////                        $data = pestSumFunction($pestRes, $startTime, $endTime, 604800,"week", $start, $length);
////                        break;
////                    case 'yue':
////                        $data = pestSumFunction($pestRes, $startTime, $endTime, 2592000,"month", $start, $length);
////                        break;
////                    case "nian":
////                        $data = pestSumFunction($pestRes, $startTime, $endTime, 31536000, "year",$start, $length);
////                        break;
////                };
////                /* 将需要返回给插件的数据组合成固定的数组形式 */
////                $lastData = array(
////                    "draw" => $draw,
////                    "recordsTotal" => $data['recordsTotal'],
////                    "recordsFiltered" => $data['recordsFiltered'],
////                    "data" => $data['data'],
////                );
////                
////            } else {
////                $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
////                $pest_res = $this->pest
////                ->where($where)
////                ->limit($start, $length)
////                ->order($order)
////                ->select();
////                $recordsTotal = $this->pest
////                        ->where($where)
////                        ->count();
////                $recordsFiltered = $this->pest
////                        ->where($where)
////                        ->count(); //条件过滤后的数据总计
////                $lastData = array(
////                    "draw" => $draw,
////                    "recordsTotal" => $recordsTotal,
////                    "recordsFiltered" => $recordsFiltered,
////                    "data" => $pest_res,
////                );
////            }
//              $this->ajaxReturn($lastData);
//        } else {
//            if (!empty($listSumData)) { //当汇总周期不为空时，进行汇总
//                switch ($listSumData) {
//                    case "hours":
////                        p($pestRes);
////                        exit;
//                        $data = pestSumFunction($pestRes, "", "", 3600, "hours",$start, $length);
//                        break;
//                    case "tian":
//                        $data = pestSumFunction($pestRes, "", "", 86400,"day", $start, $length);
//                        break;
//                    case "zhou":
//                        $data = pestSumFunction($pestRes, "", "", 604800,"week", $start, $length);
//                        break;
//                    case "yue":
//                       $data = pestSumFunction($pestRes, "", "", 2592000, "month",$start, $length);
//                        break;
//                    case "nian":
//                        $data = pestSumFunction($pestRes, "", "", 31536000,"year", $start, $length);
//                        break;
//                };
//                /* 将需要返回给插件的数据组合成固定的数组形式 */
//                $lastData = array(
//                    "draw" => $draw,
//                    "recordsTotal" => $data['recordsTotal'],
//                    "recordsFiltered" => $data['recordsFiltered'],
//                    "data" => $data['data'],
//                );
//            }else{
//                $pest_res = $this->pest
//                        ->where($where)
//                        ->limit($start, $length)
//                        ->order($order)
//                        ->select();
//                $recordsTotal = $this->pest
//                        ->where($where)
//                        ->count();
//                $recordsFiltered = $this->pest
//                        ->where($where)
//                        ->count(); //条件过滤后的数据总计
//                $lastData = array(
//                    "draw" => $draw,
//                    "recordsTotal" => $recordsTotal,
//                    "recordsFiltered" => $recordsFiltered,
//                    "data" => $pest_res,
//                );
//            }
//             $this->ajaxReturn($lastData);
//        }
       
    }

    function get_array_value($data){
        $result=array();
        array_walk_recursive($data,function($value) use (&$result){
          array_push($result,$value);
        });
        return $result;
    }
    
    
    
    public function pestCharts() {
        $device_code = $this->deviceData['device_code'];  //获取设备唯一编码
        
        $startTime = strtotime(I("startTime"));  //获取时间插件时间戳格式
        $endTime = strtotime(I("endTime"));   //获取时间插件时间戳格式
        $device_where = array("device_code" => $device_code, "imgstatus" => 1);
        $device_where['pn_category_name'] = array("neq","");
        if($this->sex != "all"){
//             $where['baie_num']
            
//            $where['temp&humidity'] = array("neq","");
//            $where['baie_num|cch_num|yxz_num|wde_num|clj_num|other_num'] = array("neq",0);
        }
        $pest_item = $this->pest
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                ->where($where)
                ->where($device_where)
                ->order("unix_createdate desc")
                ->find(); 
        //获取数据中时间戳最大的数据
        $maxTime = $pest_item['unix_createdate'];
        //获取$amxTime 在一天内的范围值;
        $date = getTime($maxTime, "day");
        
        
        
        //判断当时间插件内容不为空时，进行条件判断
        if (!empty($startTime) && !empty($endTime)) {
            $where["unix_createdate"] = array(array("gt", $startTime), array("lt", $endTime));
        }else{
            $where["unix_createdate"] = array(array("gt", $date['beginTime']), array("lt", $date['endTime']));
        }
        
        $pest_res = $this->pest
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                ->where($where)
                ->where($device_where)
                ->order("pn_category_name ")
                ->field("pn_category_name,temp,humidity,unix_createdate,pn_number")
                ->select(); 
        $pest_type = D("pest_number")
                ->where($device_where)
                ->field("pn_category_name")
                ->group("pn_category_name")
                ->select(); 

        $type = array();
        //获得存在的所有害虫的类型
        foreach ($pest_type as $key => $value) {
            $type[] =  $value['pn_category_name'];
        }
 
        
        if (IS_AJAX) {
            $arr;
            $res = array(); //想要的结果
                foreach ($pest_res as $k => $v) {
                  $res[$v['unix_createdate']][] =  $v; 
                  
                }
//                p($res);
                $array = array();
                $array['category_name'] = array("温度","湿度"); 
                foreach ($type as $key => $value) {
                    $array['category_name'][] = $value;
                }
//                p($type);
                
                foreach ($res as $ke => $val) {
                    $arr['createdate'][] = date("Y-m-d H:i:s",$ke);
                    foreach($val as $k=>$v){
                        if($v['temp']){
                            $array['温度'][] = $v['temp'];
                        }else{
                            $array['温度'][] = "";
                        }
                        if($v['humidity']){
                            $array['湿度'][] = $v['humidity'];
                        }else{
                             $array['湿度'][] = "";
                        }
                    }
                    for ($i = 0; $i < count($type); $i++) {
                            if( in_array($val[$i]['pn_category_name'], $type)){
                                $array[$type[$i]][] = $val[$i]['pn_number'];
                            }else{
                                $array[$type[$i]][] = 0;
                            }
                            
                    }
                }
                foreach ($array['category_name'] as $key => $value) {
                    $arr['data'][] = array(
                        'name'=>$value,
                        'data'=>$array[$value],
                    );
                }
//                p($arr);
            $this->ajaxReturn($arr);
        }
        $this->assign(array("pest" => $pest_res));
        $this->assign(array("date" => $date));
//        $this->display();
    }

    public function pestHistory() {
        
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */
        
        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $where = array("device_code" => $device_code, "imgstatus" => 1);
//$where['pn_category_name'] = array("neq","");
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $pestHistory_res = $this->pest    //获取分页数据
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pn.pd_id=pd.pd_id")

                ->where($where)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->group("unix_createdate")
                ->select();
//        p($pestHistory_res);
//        exit;
        $recordsTotal = $this->pest
                 ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pn.pd_id=pd.pd_id")
                ->where(array("device_code" => $device_code, "imgstatus" => 1))
                ->group("unix_createdate")
                ->count();
        $recordsFiltered = $this->pest
                 ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pn.pd_id=pd.pd_id")
                ->where($where)
                ->group("unix_createdate")
                ->count();
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $pestHistory_res,
        );
        if(!$pestHistory_res){
            $arr = array();
        }
        
        $this->ajaxReturn($arr);

//        $this->display();
    }

    public function seed() {
        $typeAll = D("type")->where("type=2")->select();
//        p($typeAll);
        //设备的唯一编码
        $device_code = $this->deviceData['device_code'];

        //实例化病害表
        $seed = D("disease");
        $seed_res = $seed
                ->where(array("device_code" => $device_code))
                ->order("unix_cretaedate desc")
                ->limit(0, 8)
                ->select();
        $this->assign(array("seed_res" => $seed_res));
        $this->assign("typeAll",$typeAll);
        $this->display();
    }

    public function seedHistory() {
        $seed = D("disease");  //实例化病害表
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */
        $draw = I("draw");
        $start = I("start");
        $length = I("length");

        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        /* 查找条件数组定义 */
        $where = array("device_code" => $device_code, "imgstatus" => 1);
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_cretaedate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $seedHistory_res = $seed    //获取分页数据
                ->where($where)
                ->limit($start, $length)
                ->select();
        $recordsTotal = $seed
                ->where(array("device_code" => $device_code, "imgstatus" => 1))
                ->count();
        $recordsFiltered = $seed
                ->where($where)
                ->count();

        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $seedHistory_res,
        );

        $this->ajaxReturn($arr);

        $this->display();
    }
    
     function env() {
         
        $this->display();
    }
    
    public  function getExcel($fileName,$headArr,$data)  
        {  
            //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入  
            import("Org.Util.PHPExcel");  
            import("Org.Util.PHPExcel.Writer.Excel5");  
            import("Org.Util.PHPExcel.IOFactory.php");  
  
            $date = date("Y_m_d",time());  
            $fileName .= "_{$date}.xls";  
  
            //创建PHPExcel对象，注意，不能少了\  
            $objPHPExcel = new \PHPExcel();  
            $objProps = $objPHPExcel->getProperties();  
  
            //设置表头  
            $key = ord("A");  
            //print_r($headArr);exit;  
            foreach($headArr as $v){  
                $colum = chr($key);  
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);  
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);  
                $key += 1;  
            }  
  
            $column = 2;  
            $objActSheet = $objPHPExcel->getActiveSheet();  
  
            //print_r($data);exit;  
            foreach($data as $key => $rows){ //行写入  
                $span = ord("A");  
                foreach($rows as $keyName=>$value){// 列写入  
                    $j = chr($span);  
                    $objActSheet->setCellValue($j.$column, $value);  
                    $span++;  
                }  
                $column++;  
            }  
  
            $fileName = iconv("utf-8", "gb2312", $fileName);  
  
            //重命名表  
            //$objPHPExcel->getActiveSheet()->setTitle('test');  
            //设置活动单指数到第一个表,所以Excel打开这是第一个表  
            $objPHPExcel->setActiveSheetIndex(0);  
            ob_end_clean();//清除缓冲区,避免乱码  
            header('Content-Type: application/vnd.ms-excel');  
            header("Content-Disposition: attachment;filename=\"$fileName\"");  
            header('Cache-Control: max-age=0');  
  
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            $objWriter->save('php://output'); //文件通过浏览器下载  
            exit;  
              
        }  
    //全部导出数据方法  
        public function allEnvExp()  
        {  
            
            
            $post = I('post.');
            $startTime = strtotime($post['startTime']);
            $endTime = strtotime($post['endTime']);
            $listSumData = $post['listSumData'];
            $device_code = $this->deviceData['device_code'];
            $device_where = array("device_code" => $device_code); 
            $where = array();
         /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {  //当开始时间和结束时间不为空时，进行区间查找
                $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
            }
        $env_res = $this->env
                ->where($device_where)
                ->where($where)
                ->order("unix_createdate desc")
                ->select();
                foreach ($env_res as $key => $value) {
                    $env_res[$key]['wind_direction'] = getWindDirection($value['wind_direction']);
                }
                $goods_list = $env_res;
         if (!empty($listSumData)) { //当汇总周期不为空时，进行汇总
              $data = getEnvCollect( $listSumData,$startTime, $endTime, $start, $length, $device_code);
              $goods_list = $data['data'];
         } 
    //链接所导出的数据表  
//        $xlsModel = D('env');  
//        $goods_list  = $xlsModel->where($where)->field('device_code,temp,humidity,wind_speed,wind_direction,beam,unix_starttime,unix_endtime,unix_createdate')->where(array("device_code"=>$this->deviceData['device_code']))->select();  
        $count=1;//导出Excel序号排列  
        $data = array();  
    //循环查询后的数据，进行每一列  
        foreach ($goods_list as $k=>$goods_info){  
            $data[$k][ID] = $count++;
            $data[$k][device_code] = $goods_info['device_code'];
            $data[$k][temp] = $goods_info['temp']; 
            $data[$k][humidity] = $goods_info['humidity']; 
            $data[$k][wind_speed] = $goods_info['wind_speed'];
            $data[$k][wind_direction] = $goods_info['wind_direction'] ? getWindDirection($goods_info['wind_direction']):"-";  
            $data[$k][unix_starttime] = $goods_info['unix_starttime']?date('Y-m-d H:i:s',$goods_info['unix_starttime']):"-";  
            $data[$k][unix_endtime] = $goods_info['unix_endtime'] ?date('Y-m-d H:i:s',$goods_info['unix_endtime']):"-"; 
            $data[$k][unix_createdate] = $goods_info['unix_createdate']?date('Y-m-d H:i:s', $goods_info['unix_createdate']):"-";   
        }  
    //每列表的名称  
        foreach ($data as $field=>$v){  
           if($field == 'ID'){  
                $headArr[]='序号';  
            }  
            if($field == 'device_code'){  
                $headArr[]='设备号';  
            }  
            if($field == 'temp'){  
                $headArr[]='温度';  
            }  
            if($field == 'humidity'){  
                $headArr[]='湿度';  
            }  
             if($field == 'wind_speed'){  
                $headArr[]='风力';  
            }  
            if($field == 'wind_direction'){  
                $headArr[]='风向';  
            }  
            if($field == 'unix_starttime'){  
                $headArr[]='开始时间';  
            }  
            if($field == 'unix_endtime'){  
                $headArr[]='结束时间';  
            }  
            if($field == 'unix_createdate'){  
                $headArr[]='创建时间';  
            }  
        }  
        $filename= $this->deviceData['device_code']."号环境数据表";//所导出的保存文件名称  
        $sss=$this->getExcel($filename,$headArr,$data);//调用导出引用方法  
    }  
        

    
     function envList() {
        $env = D("env");
        $soil = D("soil_temp");
        /* 获取datatables插件的相关数据 */
        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        $order_num = I("order")[0][column];
        $order_dir = I("order")[0][dir];
        $order_name = I("columns")[$order_num][data];
        $order = $order_name." ".$order_dir;
        /* 获取设备唯一编码 */
        $device_code = session("device_code");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $listSumData = I("listSumData");  //汇总周期
        $data = array();
        $where = array();
        
        /* 根据条件查询 */
        $device_where = array("device_code" => $device_code);
        $soilWhere['device_code'] = $device_code;
//        if($this->sex != "all"){
//            $device_where['temp&humidity&beam&wind_direction&wind_speed'] = array("neq", "");
//        }
         /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {  //当开始时间和结束时间不为空时，进行区间查找
                $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
                $soilWhere['unix_addtime'] = array(array("gt", $startTime), array("lt", $endTime));
            }
            /*测试*/
        $env_item = $env
            ->where($device_where)
            ->where($where)
            ->order("unix_createdate desc")
            ->find();
        $soil_item = $soil
            ->where($soilWhere)
            ->order("unix_addtime desc")
            ->find();
        
        
        
            //当最新环境数据大于最新土壤温湿度
        if($env_item['unix_createdate']>=$soil_item['unix_addtime']){
            $env_res = $env
                ->where($device_where)
                ->where($where)
                ->limit($start, $length)
                ->order($order)
                ->select();
            foreach ($env_res as $key => $value) {
                $newDate = $value['unix_createdate']+300;
                $soil_where['unix_addtime'] = array(array("gt", $value['unix_createdate']), array("lt", $newDate));
                $soil_where['device_code'] = $device_code;
                $soilItem = $soil->where($soil_where)->find();
                $env_res[$key]['soil_tem'] = $soilItem['soil_tem'];
                $env_res[$key]['soil_hum'] = $soilItem['soil_hum'];
                $env_res[$key]['crop_tem'] = $soilItem['crop_tem'];
                $env_res[$key]['crop_tem_second'] = $soilItem['crop_tem_second'];
                $env_res[$key]['unix_addtime'] = $soilItem['unix_addtime'];
                $env_res[$key]['wind_direction'] = getWindDirection($value['wind_direction']);
            }
        }else{
            $soil_res = $soil
                ->where($soilWhere)
                ->limit($start, $length)
                ->order("unix_addtime desc")
                ->select();
            foreach ($soil_res as $key => $value) {
                $newDate = $value['unix_addtime']+300;
                $envWhere['unix_createdate'] = array(array("gt", $value['unix_addtime']), array("lt", $newDate));
                $envWhere['device_code'] = $device_code;
                $envItem = $env->where($envWhere)->find();
                $env_res[$key]['soil_tem'] = $value['soil_tem'];
                $env_res[$key]['soil_hum'] = $value['soil_hum'];
                $env_res[$key]['crop_tem'] = $value['crop_tem'];
                $env_res[$key]['crop_tem_second'] = $value['crop_tem_second'];
                $env_res[$key]['unix_createdate'] = $value['unix_addtime'];
                $env_res[$key]['wind_direction'] = $envItem['wind_direction'];
                $env_res[$key]['temp'] = $envItem['temp'];
                $env_res[$key]['humidity'] = $envItem['humidity'];
                $env_res[$key]['wind_speed'] = $envItem['wind_speed'];
                $env_res[$key]['beam'] = $envItem['beam'];
                 $env_res[$key]['unix_starttime'] = ' ';
                  $env_res[$key]['unix_endtime'] = ' ';
                   $env_res[$key]['device_code'] = $value['device_code'];
                
            }
        }
            
        
        
                
                
                
                $recordsTotal = $env
                                   ->where($device_where)
                                   ->count();
                
                $recordsFiltered = $env
                                   ->where($device_where)
                                   ->where($where)
                                   ->count();
                $data = array(
                    "draw" => $draw,
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $env_res,
                );
         if (!empty($listSumData)) { //当汇总周期不为空时，进行汇总
              $data = getEnvCollect( $listSumData,$startTime, $endTime, $start, $length, $device_code);
         }
        $lastData = array(
            "draw" => $draw,
            "recordsTotal" => $data['recordsTotal'],
            "recordsFiltered" => $data['recordsFiltered'],
            "data" => $data['data'],
        );
         $this->ajaxReturn($lastData);
    }
    

    public function envCharts() {

        $device_code = session("device_code");  //获取设备唯一编码
        $startTime = strtotime(I("startTime"));
        $endTime = strtotime(I("endTime"));
        $type = I("type");
        $where = array("device_code" => $device_code);
        if($this->sex != "all"){
            $where['temp&humidity&beam&wind_direction&wind_speed'] = array("neq", "");
        }
        $env_item = $this->env
                ->where($where)
                ->field('unix_createdate')
                ->order("unix_createdate desc")
                ->find();

        //获取数据中时间戳最大的数据
        $maxTime = $env_item["unix_createdate"];
        //获取时间戳所在的一天的范围值;
         $date = getTime($maxTime, "day");
         
        if (!empty($startTime) && !empty($endTime)) {
            $where["unix_createdate"] = array(array("gt", $startTime), array("lt", $endTime));
        }else{
            $where["unix_createdate"] = array(array("gt", $date['beginTime']), array("lt", $date['endTime']));
        }
         
         $env_res = D("env")
                ->where($where)
                ->field('uid,region,province,city,county,address',true)
                ->order("unix_createdate")
                ->select();
        
//        //获取最小时间的时间戳
//        $firstTime = current($env_res);
//        $firstTime = $firstTime['unix_addtime'];
//        
//        //获取最大时间的时间戳
//        $lastTime = end($env_res);
//        $lastTime = $lastTime["unix_addtime"];
//        
        
        $time = array("firstTime" => $firstTime, "lastTime" => $lastTime);
        if (IS_AJAX) {
            $arr = array();
            switch ($type) {
                case "temp":
                    foreach ($env_res as $key => $value) {
                        $arr["addtime"][] = date("Y-m-d H:i:s", $value["unix_createdate"]);
                        $arr["temp"][] = $value["temp"];
                    }
                    break;
                case "hum":
                    foreach ($env_res as $key => $value) {
                        $arr["addtime"][] = date("Y-m-d H:i:s", $value["unix_createdate"]);
                        $arr["hum"][] = $value["humidity"];
                    }
                break;   
                case "beam":
                    foreach ($env_res as $key => $value) {
                        $arr["addtime"][] = date("Y-m-d H:i:s", $value["unix_createdate"]);
                        $arr["beam"][] = $value["beam"];
                    }
                break; 
                case "wind_speed":
                    foreach ($env_res as $key => $value) {
                        $arr["addtime"][] = date("Y-m-d H:i:s", $value["unix_createdate"]);
                        $arr["wind_speed"][] = $value["wind_speed"];
                    }
                break; 
                
            }
            
//            $pest_res['firstTime'] = date("Y-m-d H:i:s",$firstTime);
//            $pest_res['lastTime'] =  date("Y-m-d H:i:s",$lastTime);
//            foreach ($env_res as $key => $value) {
//                $arr["addtime"][] = date("Y-m-d H:i:s", $value["unix_createdate"]);
//                $arr["temp"][] = $value["temp"];
//                $arr["humidity"][] = $value["humidity"];
//                $arr["beam"][] = $value["beam"];
//                $arr["wind_speed"][] = $value["wind_speed"];
//                $arr["precipitation"][] = $value["precipitation"];
//            }
            $this->ajaxReturn($arr);
        }
        $this->assign(array("env" => $env_res));
        $this->assign(array("time" => $time));
        $this->display();
    }

//    public function view() {
//        $d = $_SERVER['DOCUMENT_ROOT'];
//        $path = $d."/video.json";
//      $jsonClass = new \Home\Controller\JsonController();
//      $data = $jsonClass->getJsonDoc($path);
//            
//            foreach ($data['video'] as $key => $value) {
//               if(session('device_code')==$value['device_code']){
//                   $rtmp = $value['rtmp-gq'];
//               }
//            }
//             $this->assign("rtmp",$rtmp);
//             
//        $this->display();
//    }
                function view(){
                    $yuming = C("DOMAIN");
                    $device_code = session("device_code");
                    $src = $yuming.":".$device_code."0";
                    $this->assign("src",$src);
                    $this->display();
                }

    public function info(){
        $id = I("id");
        $limit = 30;
        $item = $this->device->where("device_id=$id")->find();
        $where = array(
            "imgstatus"=>2,
            "device_code"=>$item['device_code'],
            );
        $count=$this->pest->where($where)->count();
        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->pest->where($where)->order('pd_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $show       = $Page->show();// 分页显示输出
        $this->assign('result',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    function infoDisease(){
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */

        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $device_where = array("device_code" => $device_code, "imgstatus" => 2);
        $where = array();
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_addtime'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $diseaseInfo = D('disease')    //获取分页数据
                ->where($where)
                ->where($device_where)
                ->order("unix_addtime desc")
                ->limit($start, $length)
                ->select();
         $absPath = realpath(__ROOT__);
        foreach ($diseaseInfo as $key => $value) {
            $path=str_replace('/','\\',$value['image']);
            $imgPath = $absPath.$path;
            $bool = file_exists($imgPath);
            
            if($bool){
                $pestInfo[$key]['file_exists'] = 1;
                
            }else{
                $pestInfo[$key]['file_exists'] = 0;
                
            }
           
        }
    
        $recordsTotal = D('disease')
                ->where($device_where)
                ->count();
        $recordsFiltered = D('disease')
                ->where($device_where)
                ->where($where)
                ->count();
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $diseaseInfo,
        );
        $this->ajaxReturn($arr);
    }
    function infoPest(){
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */

        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $device_where = array("device_code" => $device_code, "imgstatus" => 4);
        $where = array();
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $pestInfo = $this->pest    //获取分页数据
                ->where($where)
                ->where($device_where)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->select();
         $absPath = realpath(__ROOT__);
        foreach ($pestInfo as $key => $value) {
            $path=str_replace('/','\\',$value['image']);
            $imgPath = $absPath.$path;
            $bool = file_exists($imgPath);
            
            if($bool){
                $pestInfo[$key]['file_exists'] = 1;
                
            }else{
                $pestInfo[$key]['file_exists'] = 0;
                
            }
           
        }
    
        $recordsTotal = $this->pest
                ->where($device_where)
                ->count();
        $recordsFiltered = $this->pest
                ->where($device_where)
                ->where($where)
                ->count();
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $pestInfo,
        );
        $this->ajaxReturn($arr);
    }
    
    function infoPestImgDel(){
        $id = I("id");
       $pest =  D("pest_data");
       $bool = $pest->where(array("pd_id"=>$id))->delete();
       if($bool){
           $data = array(
               "info"=> "删除成功",
               "state" => 1,
               "id" => $id,
           );
       }else{
           $data = array(
               "info"=> "删除失败",
               "state" => 2,
           );
       }
       $this->ajaxReturn($data);
    }
    
    public function pestImgShow(){
        $pd_id = I("pd_id");
        $item = D("pest_data")
                ->table("iot_pest_data as pd")
                ->join("iot_pest_number as pn on pn.pd_id=pd.pd_id")
                ->where(array("pd.pd_id"=>$pd_id))
                ->select();
        $this->assign("item",$item);
        $this->display();
    }
    public function pestImgHandle(){
        $this->display();
    }
     /*信息处理中的单一图片显示*/
    public function diseaseImgShow(){
        $id = I("id");
        $where = array("de_id"=>$id);
        $item = D('disease')->where($where)->find();
        $type = D("type");
    $pestType = $type->where("type=1")->select();
        $deItem = $this->device->where(array("device_code"=>$item['device_code']))->find();
        $region = D("region");
        $reItem = $region->where(array("region_id"=>$deItem['re_id']))->field("rnname,rnprovince,rncity,rncounty")->find();
        
        $this->assign("item",$item);
        $this->assign("deItem",$deItem);
        $this->assign("reItem",$reItem);
//         $this->assign("pestType",$pestType);

        $this->display();
    }
    
    
    /*信息处理中的单一图片显示*/
    public function infoImgShow(){
        $id = I("id");
        $where = array("pd_id"=>$id);
        $item = $this->pest->where($where)->find();
$type = D("type");
    $pestType = $type->where("type=1")->select();
        $deItem = $this->device->where(array("device_code"=>$item['device_code']))->find();
        $region = D("region");
        $reItem = $region->where(array("region_id"=>$deItem['re_id']))->field("rnname,rnprovince,rncity,rncounty")->find();
        
        $this->assign("item",$item);
        $this->assign("deItem",$deItem);
        $this->assign("reItem",$reItem);
         $this->assign("pestType",$pestType);

        $this->display();
    }
    /* 图片人工校验，信息添加*/
    public function imgInfoAdd(){
        $param = I("param.");
        $param['imgstatus'] = 1;
        $pest_num = D("pest_number");
        $pd_id = $param['pd_id'];
        $device_code = $param['device_code'];
        $createdate = $param['unix_createdate'];
        $region = $param['region'];
        $ago_createdate = $createdate-7200;
        $after_createdate = $createdate+7200;
        
        $pestType= $param['pestType'];   //类型数组  二维数组
        $fayu= $param['fayu'];   //发育数组  二维数组
        $num= $param['num'];   //数量数组  二维数组
        $ci= $param['ci'];   //数量数组  二维数组
        $array = array();
        foreach ($pestType as $key => $value) {
            $array['pd_id'] = $pd_id;
            $array['pn_category_name'] = $pestType[$key];
            $array['pn_number'] =  $num[$key];
            $array['pn_period'] = $fayu[$key];
            $array['pn_female'] = $ci[$key];
            $array['pn_device_code'] = $device_code;
            $id = $pest_num->add($array);
            if(!$id){
                 $data = array(
                    "info"=>"保存成功",
                    "state"=> 2,
                );
                $this->ajaxReturn($data);
            }
        }
//        $where = array("device_code"=>$device_code);
        $where['temp&humidity'] = array("neq","");
        $where['region'] = $region;
        $where['unix_createdate'] = array(array('gt',$ago_createdate),array('lt',$after_createdate)) ;
        $item = $this->env->where($where)->order("unix_createdate desc")->find();
//        p($item);
        if($item){
            $param['temp'] = $item['temp'];
            $param['humidity'] = $item['humidity'];
            $param['wind_direction'] = $item['wind_direction'];
            $param['wind_speed'] = $item['wind_speed'];
            $param['lum'] = $item['beam'];
        }else{
            $param['temp'] = "";
            $param['humidity'] = "";
            $param['wind_direction'] = "";
            $param['wind_speed'] = "";
            $param['lum'] = "";
        }

        $this->pest->create($param);
        $bool = $this->pest->save();
        if(0 == $bool||$bool){
            $data = array(
                "info"=> "保存成功",
                "state" => 1,
            );
           
        }else{
             $data = array(
                "info"=> "保存失败",
                "state" => 2,
            );
             
        }
        $this->ajaxReturn($data);
        
    }
    /*可能暂时没使用------具体测试以后再说*/
    public  function infoDate(){
	$date = I("date");
	$dateArr = array();
        $pest = D("pest_data");
        $seed = D("disease");
        $device_code = $this->deviceData['device_code'];
        $pestDir = "/ftp/pest/";
        $diseaseDir = "/ftp/disease/";
        $fixed = "/01/pic/";
        $ftpDir = $_SERVER["DOCUMENT_ROOT"] . $pestDir;
        $uid = cookie("home");
        $un_id = cookie('user')['un_id'];
//        p($un_id);
        
        //$newDir E:/WorkSpace/NetBeans/new_forestry/Upload/images/pest/sdjn004/2017-06-15
        $newDir = $_SERVER["DOCUMENT_ROOT"] . "/Upload/images/pest/";
//        p($newDir);
        
        $newDir1 = "/Upload/images/pest/";
       
        
        if(file_exists($ftpDir.$device_code)){
             $device_size = getDirSize($ftpDir . $device_code . "/");
        }else{
            $this->success("无信息处理~0~");
            exit(0);
        }
       
	   
	   $files_1 = scandir($ftpDir . $device_code);   //获取该目录下的所有文件名
//            p($files_1);
        foreach ($files_1 as $dateDir) {    //循环遍历文件名数组
            if ($dateDir != "." && $dateDir != "..") {  //去除 .和 ..的文件名
            $dateArr[]['date'] = $dateDir;
			}
		}
		$dateArr = array_reverse($dateArr,true);
	   
//       p($device_size);
       

//        $deviceSize = getDirSize($ftpDir . $device_code . "/");
            if (!file_exists($newDir . $device_code)) {  //判断文件夹中是否有设备文件夹
                mkdir($newDir . $device_code);    //创建文件夹
                
            }
        if ($device_size > 10000) {  //判断文件夹的大小是否大于20kb
               
                    $dateSize = getDirSize($ftpDir . $device_code . "/" . $date);   //获取下级文件夹的大小
                    if (!file_exists($newDir . $device_code . "/" . $date)) {  //判断文件夹中是否有时间文件夹
                        mkdir($newDir . $device_code . "/" . $dateDir);    //创建文件夹
                    }
                   
                    if ($dateSize > 20000) {   //判断文件夹的大小是否大于20kb
                        $files_2 = scandir($ftpDir . $device_code . "/" . $date . $fixed); //获取该目录下的所有文件名
                        
                        foreach ($files_2 as $fileName) {   //循环遍历文件名数组
                            if ($fileName != "." && $fileName != "..") {   //去除 .和..的文件名    这是获得的图片
                                $hours = substr($fileName, 0, 2);
                                $minute = substr($fileName, 2, 2);
                                $second = substr($fileName, 4, 2);
                                $creattime = $dateDir . " " . $hours . ":" . $minute . ":" . $second;  //获取date格式时间
                                $addtime = strtotime($creattime);   //获取时间戳
                                $boolRename = rename($ftpDir . $device_code . "/" . $dateDir . $fixed . $fileName, $newDir . $device_code . "/" . $dateDir . "/" . $fileName);
                             
                                if ($boolRename) {
                                    $arr['addtime'] = $addtime;
                                    $arr['imgstatus'] = 2;
                                    $arr['image'] = $newDir1 . $device_code . "/" . $dateDir . "/" . $fileName;
                                    $arr['device_code'] = $device_code;
                                    $arr['creattime'] = $creattime;
                                    $arr['uid'] = $uid;
                                    $arr['un_id'] = $un_id; 
									$arr['baie_num'] = 0;
									$arr['wde_num'] = 0;
									$arr['cch_num'] = 0;
									$arr['yxz_num'] = 0;
									$arr['clj_num'] = 0;
									$arr['temp'] = 0;
									$arr['humidity'] = 0;
									$arr['wind_direction'] = 0;
									$arr['wind_speed'] = 0;
									$arr['lum'] = 0;
                                    $this->pest->create($arr); 
                                    $bool = $this->pest->add();
                                }
                            }
                        }
                    }
               
            
        }
		$device_code = $this->deviceData['device_code'];
        $seed_res = $seed->where(array("device_code" => $device_code, "imgstatus" => 2))->select();
        $this->assign(array("seed_res" => $seed_res)); 
        $pest_res = $this->pest->where(array("device_code" => $device_code, "imgstatus" => 2))->select();
        $this->assign(array("pest_res" => $pest_res,"dateArr"=>$dateArr)); 
        $this->display();
        
	
	
}
    public function info2() {
     

        $date = I("timeSelect"); //获取下拉菜单数据	
		$date = strstr($date, '(',true);
		$dateArr = array();
        $pest = D("pest_data");
        $seed = D("disease");
        $device_code = $this->deviceData['device_code'];
        $pestDir = "/ftp/pest/";
        $diseaseDir = "/ftp/disease/";
        $fixed = "/01/pic/";
        $ftpDir = $_SERVER["DOCUMENT_ROOT"] . $pestDir;
        $uid = cookie("home");
        $un_id = cookie('user')['un_id'];
//        p($un_id);
        
        //$newDir E:/WorkSpace/NetBeans/new_forestry/Upload/images/pest/sdjn004/2017-06-15
        $newDir = $_SERVER["DOCUMENT_ROOT"] . "/Upload/images/pest/";
//        p($newDir);
        
        $newDir1 = "/Upload/images/pest/";
       
        
        if(file_exists($ftpDir.$device_code)){
             $device_size = getDirSize($ftpDir . $device_code . "/");
        }else{
            $this->success("无信息处理~0~");
            exit(0);
        }
       
	   /*------------------------开始 ---------------------------------------
	    * 1.循环遍历设备编号文件夹下的所有时间文件夹名称
	    * 2.将获取的时间文件名数组去除.和..并存入到一个新数组中
	    * 3.对新数组进行翻转顺序
	    * 4.判断下拉菜单的数据是否为空，为空就将时间文件夹数组中的最大值作为下拉菜单的数据
	    */
	   $files_1 = scandir($ftpDir . $device_code);   //获取设备编号文件夹下的所有时间文件名
//            p($files_1);
		$i = 0;
        foreach ($files_1 as $dateDir) {    //循环遍历文件名数组
            if ($dateDir != "." && $dateDir != "..") {  //去除 .和 ..的文件名
//				$count =  scandir($newDir.$device_code."/".$dateDir);
//				if($count){
//					$dateArr[$i]['count'] = count($count)-2;
//				}else{
//					$dateArr[$i]['count'] = count($count)-1;
//				}
            	$dateArr[$i]['date'] = $dateDir;
				$i++;
			}
		}
		$dateArr = array_reverse($dateArr);
		if(empty($date)){
			$date = $dateArr[0]['date'];
		}
//		p($date);
		
		/*--------------------------结束-------------------------------*/
//       p($device_size);
      

//        $deviceSize = getDirSize($ftpDir . $device_code . "/");
            if (!file_exists($newDir . $device_code)) {  //判断文件夹中是否有设备文件夹
                mkdir($newDir . $device_code);    //创建文件夹
                
            }
        if ($device_size > 10000) {  //判断文件夹的大小是否大于20kb
               
                    $dateSize = getDirSize($ftpDir . $device_code . "/" . $date);   //获取下级文件夹的大小
                    if (!file_exists($newDir . $device_code . "/" . $date)) {  //判断文件夹中是否有时间文件夹
                        mkdir($newDir . $device_code . "/" . $date);    //创建文件夹
                    }
                   
                    if ($dateSize > 20000) {   //判断文件夹的大小是否大于20kb
                        $files_2 = scandir($ftpDir . $device_code . "/" . $date . $fixed); //获取该目录下的所有文件名
                        
                        foreach ($files_2 as $fileName) {   //循环遍历文件名数组
                            if ($fileName != "." && $fileName != "..") {   //去除 .和..的文件名    这是获得的图片
                                $hours = substr($fileName, 0, 2);
                                $minute = substr($fileName, 2, 2);
                                $second = substr($fileName, 4, 2);
                                $creattime = $date . " " . $hours . ":" . $minute . ":" . $second;  //获取date格式时间
                                $addtime = strtotime($creattime);   //获取时间戳
                                $createdate = filectime($ftpDir . $device_code . "/" . $date . $fixed . $fileName);
                                $createdate =  date('Y-m-d H:i:s',$createdate);
                                
                                $boolRename = rename($ftpDir . $device_code . "/" . $date . $fixed . $fileName, $newDir . $device_code . "/" . $date . "/" . $fileName);
                                if ($boolRename) {
                                    $arr['addtime'] = $addtime;
                                    $arr['imgstatus'] = 2;
                                    $arr['image'] = $newDir1 . $device_code . "/" . $date . "/" . $fileName;
                                    $arr['device_code'] = $device_code;
                                    $arr['creattime'] = $creattime;
                                    $arr['createdate'] = $createdate;
                                    $arr['uid'] = $uid;
                                    $arr['un_id'] = $un_id; 
                                    $arr['baie_num'] = 0;
                                    $arr['wde_num'] = 0;
                                    $arr['cch_num'] = 0;
                                    $arr['yxz_num'] = 0;
                                    $arr['clj_num'] = 0;
                                    $arr['temp'] = 0;
                                    $arr['humidity'] = 0;
                                    $arr['wind_direction'] = 0;
                                    $arr['wind_speed'] = 0;
                                    $arr['lum'] = 0;
                                    $this->pest->create($arr); 
									
                                    $bool = $this->pest->add();
									
                                }
                            }
                        }
                    }
               
            
        }
			$where = array("device_code" => $device_code, "imgstatus" => 2);
			foreach ($dateArr as $key => $value) {
				$startTime =  strtotime($value['date']);
				$endTime = $startTime+86400;
				$where['addtime'] = array("between",array($startTime,$endTime));
				$dateArr[$key]['count'] = $this->pest->where($where)->count();
			}
	
		
			$unixDate = strtotime($date);
//			p($unixDate);
			$endUnixDate = $unixDate+86400;
			
			if($unixDate){
				$where['addtime'] =  array("between",array($unixDate,$endUnixDate));
			}
	
		$device_code = $this->deviceData['device_code'];
        $seed_res = $seed->where($where)->select();
        $this->assign(array("seed_res" => $seed_res)); 
	
        $pest_res = $this->pest
        			->where($where)
        			->select();	
//					p($pest_res);
        $this->assign(array("pest_res" => $pest_res,"dateArr"=>$dateArr,"date"=>$date)); 
        $this->display();
	
	
}
    public function anal() {
		$pd_id = I("id");
		$post = I("post.");
		
		$arr = array();
		$arr['baie_num'] = $post['baie_num'];
		$arr['cch_num'] = $post['cch_num'];
		$arr['wde_num'] = $post['bwde_num'];
		$arr['yxz_num'] = $post['yxz_num'];
		$arr['clj_num'] = $post['clj_num'];
		$arr['imgstatus'] = $post['fz'];
        
		
		$pest = D("pest_data");
		$pest_one = $this->pest->where(array('pd_id'=>$pd_id))->find();
		$env = D("env");
		$endTime = $pest_one['addtime']+3600;
		$startTime = $pest_one['addtime']-3600;
		$where = array("device_code"=>$pest_one['device_code']);
		$where['unix_addtime'] = array("between",array($startTime,$endTime));
		$env_one = $env->where($where)->order("unix_addtime desc")->find();
		if($env_one){
			$arr['temp'] = $env_one['temp'];
			$arr['humidity'] = $env_one['humidity'];
		}else{
			$arr['temp'] = "-";
			$arr['humidity'] = "-";
		}
		
		$this->pest->create($arr);
		$bool = $this->pest->where(array("pd_id"=>$pd_id))->save();
		$this->ajaxReturn($bool);
		
		
    }
    public function witheredLeaf(){
        $device_code = $this->deviceData['device_code']; //获取当前设备编号
        /* 获取datatables插件传来的数据 */
        $witheredLeaf = D("withered_leaf");
        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        /* 前台搜索栏中的数据 */
        $startTime = strtotime(I("startTime"));   //开始时间
        $endTime = strtotime(I("endTime"));      //结束时间
        $device_where = array("device_code" => $device_code);
        $where = array();
        /* 对前台搜索栏中的数据进行判断和过滤 */
        if (!empty($startTime) && !empty($endTime)) {//当开始时间和结束时间不为空时，进行区间查找
            $where['unix_createdate'] = array(array("gt", $startTime), array("lt", $endTime));
        }
        $Info = $witheredLeaf    //获取分页数据
                ->where($where)
                ->where($device_where)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->select();

        $recordsTotal = $witheredLeaf
                ->where($device_where)
                ->count();
        $recordsFiltered = $witheredLeaf
                ->where($device_where)
                ->where($where)
                ->count();
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $Info,
        );
        $this->ajaxReturn($arr);
        

    }
    /*命令发送*/
    public function manage1(){
    $param = I("param.");
    if(IS_POST){
        $tmp = rand(1, 4295367296);
        $post = I("post.");
        switch ($post['type']) {
            //装置开关功能
            case 'switch':
                $data = "start*0*2*37*15*".$tmp."switch";
                $bool = $this->client("192.168.0.112", 6000,$data );
            
                break;
            //频次调节功能
            case 'frequency':
            $data = "start*0*2*37*15*".$tmp."frequency";
                $bool = $this->client("192.168.0.112", 6000,$data );
                break;
            //信息报警功能
            case 'alert':
            $data = "start*0*2*37*15*".$tmp."alert";
                $bool = $this->client("192.168.0.112", 6000,$data );
                break;
           
        }

            $this->ajaxReturn($bool);

        }
   



        $this->display();
    }  
}

// 获取文件夹大小
function getDirSize($dir) {
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

