<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class LoginController extends HomeBaseController {
    private $user;
    
    public function _initialize() {
        parent::_initialize();
        $this->user = D("user");
    }

    public function login(){
        $param = I("param.");
        if(IS_POST){
            $param['uname'] = trim($param['uname']);
            $param['password'] = trim($param['password']);
            $where = array(
                    "is_delete"=>0,
                    "uname"=>$param['uname'],
                    "password"=> md5(md5(md5(md5(md5($param['password']))))),
                    "status" => 1
                    );
            $is_user = $this->user
                    ->where($where)
                    ->find();
            if($is_user){
                cookie("user",$is_user["user_id"]);
                cookie("home",$is_user["user_id"]);
                cookie("lastLoginTime",$is_user["unix_logtime"]);
                $data = array("unix_logtime"=>time());
                $this->user->where(array("user_id"=>$is_user["user_id"]))->save($data);
            }
            $this->ajaxReturn($is_user);
        }else{
            
            $this->display();
        }
        
    }
    
    function logout(){
            $_SESSION = array(); //清除SESSION值.  
          if(isset($_COOKIE['user'])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.  
                setcookie('user','',time()-1,'/');  
                 setcookie('home','',time()-1,'/');  
            }  
            session_destroy();  //清除服务器的sesion文件
             $this->redirect("Login/login");
    }


}
