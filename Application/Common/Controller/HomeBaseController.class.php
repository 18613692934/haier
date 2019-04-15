<?php
namespace Common\Controller;
use Common\Controller\BaseController;

class HomeBaseController extends BaseController {    
    
    public function __construct() {
        parent::__construct();
        $arr = array();
        $result = D("web_info")->select();
        foreach($result as $value){
            $arr[$value['info_name']] = array(
                "info_title" => $value['info_title'],
                "info_value" => $value['info_value'],
            );
        }
        $this->assign("arr",$arr);
    }
    
    public function _initialize() {
        parent::_initialize();
        $this->is_login();
        
        
    }
    
     public function is_login($name='home', $arr = array('Login/login','Login/isLogin','Public/incompatible')) {
        parent::is_login($name, $arr);
    }
  
}
