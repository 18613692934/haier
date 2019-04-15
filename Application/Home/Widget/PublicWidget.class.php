<?php
namespace Home\Widget;
use Think\Controller;
class PublicWidget extends Controller{
     function _meta(){
         $this->display("Public/_meta");
    }
    function _footer(){
        $this->display("Public/_footer");
    }
    function codeing(){
        $this->display("Public/codeing");
    }
    function incompatible(){
        $this->display("Public/incompatible");
    }
    function message(){
        $this->display("Public/message");
    }
    function noData(){
        $this->display("Public/noData");
    }
    public function header(){
        $user_id = cookie("user");
        $item = D("user")->where(array("user_id"=>$user_id))->find();
        $logo = D("web_info")->where(array("info_name"=>"logo"))->find();
        $this->assign("user",$item);
        $this->assign("logo",$logo);
        $this->display("Header/header");
    }
    public function device_header(){
        $user_id = cookie("user");
         $logo = D("web_info")->where(array("info_name"=>"logo"))->find();
        $item = D("user")->where(array("user_id"=>$user_id))->find();
        $device_data = D("device")->where(array("device_id" => I("id")))->find();
        $pest_count = D("pest_data")->where(array("device_code"=>$device_data['device_code'],"imgstatus"=>4))->count();
        $dis_count = D("disease")->where(array("device_code"=>$device_data['device_code'],"imgstatus"=>2))->count();
        $count = $pest_count+$dis_count;
        $this->assign("count",$count);
        $this->assign("user",$item);
        $this->assign("device",$device_data);
         $this->assign("logo",$logo);
        $this->display("Header/device_header");
    }
    public function footer(){
       $data = array();
        $where['_string'] = "FIND_IN_SET('2',info_type)";
        $result = D("web_info")->where($where)->order("info_sort asc")->select();
        $this->assign("result",$result);
        $this->display("Header/right_bottom_footer");
    }
    
    public function loading(){
        $this->display('Header:loading');
    }
    public function _menu(){
        $this->display('Header/_menu');
    }
}

