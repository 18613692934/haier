<?php
namespace Home\Widget;
use Think\Controller;
class DeviceWidget extends Controller{
     function envSlide(){
          $device_code = session("device_code");
//        p($device_code);
        $region_id = $this->deviceData['re_id'];
        $rnname = D("region")->where(array("region_id"=>$region_id))->field("rnname")->find();
        $time = time() - 10800;  //获取当前时间2小时之前的时间戳
        /* 设置1小时内的条件参数 */
        $where['unix_createdate'] = array(array('gt', $time), array('lt', time()));
        $where['device_code'] = $device_code;
        if($this->sex != "all"){
            $where['temp&humidity&beam&wind_direction&wind_speed'] = array("neq", "");
        }
        /* 查找到1小时内的环境数据 */
        $env_res = D("env")
                ->where($where)
                ->order("unix_createdate desc")
                ->find();
        $env_res['wind_direction'] = getWindDirection($env_res['wind_direction']);

        $this->assign(array("env" => $env_res,"rnname"=>$rnname));
         
         $this->display("Device/envSlide");
    }
    
}

