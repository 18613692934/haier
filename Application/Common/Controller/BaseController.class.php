<?php
namespace Common\Controller;
use Think\Controller;
/**
 * Base基类控制器
 */
class BaseController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    public function __call($method, $args) {
        $this->display("../Error/Error/error404");
    }
    /**
     * 初始化方法
     */
     public function _initialize(){
         $user_id = cookie('user');
         $user = D('user')->where(array('user_id'=>$user_id))->field('uname,user_id')->find();
         //p($user);
         $this->assign('user',$user);
     }


    function is_login($name='',$arr=array()){
    //不进行验证的操作名（数组）
    $action = CONTROLLER_NAME.'/'.ACTION_NAME;
    $cname = $_COOKIE[$name];
    $boolean = in_array($action, $arr);
        if(!$boolean){
            if(!isset($cname) || empty($cname)){
                $this->redirect("Login/login");
            }
        }
    }


}
