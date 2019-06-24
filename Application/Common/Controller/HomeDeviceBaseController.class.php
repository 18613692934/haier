<?php
namespace Common\Controller;
use Common\Controller\HomeBaseController;

class HomeDeviceBaseController extends HomeBaseController {    
    
    public function __construct() {
        parent::__construct();
       
             $id = I("id");
             $where = array(
                 "device_id"=>$id,
                 "is_delete" => 0,
                     );
           $device_res =  $device  = D("device")->where($where)->find();
            if($device_res){
                cookie("device",$id);
                session("device_code",$device_res['device_code']);
            }
            $result = D("web_info")->where("info_type=0")->select();
            $this->assign("webresult",$result);
      
            
    }
  
}
