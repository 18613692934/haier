<?php
namespace Common\Controller;
use Common\Controller\BaseController;

/**
 * admin控制器基类
 */
class AdminBaseController extends BaseController {
    /**
     * 初始化方法
     */
    function _initialize() {
        parent::_initialize();
        $this->is_login();
        if(in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $arr = array('Login/login','Login/verify','Login/isLogin','Index/quit'))){
            return true;
        }else{
            //下面代码动态判断权限  
            $auth = new \Think\Auth();  
            if(!$auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,cookie('admin')) && cookie('admin') != 1){  
                $this->error('没有权限',U('Index/welcome'));  
//                return alert_error("没有权限", 3);
            }  
        }
        
        
    }
    public function is_login($name='admin',$arr = array('Login/login','Login/verify','Login/isLogin') ) {
        parent::is_login($name, $arr);
    }  
}

