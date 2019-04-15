<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class LoginController extends AdminBaseController {
    /* 初始化方法*/
    public function _initialize() {
        parent::_initialize();
    }
    /*登录页面逻辑判断*/
    public function login(){
        if(IS_POST){
            $param = I('param.');
                $admin = D('Admin');
            $verify = I('verify');
                $login_name = I('login_name');
            $password = md5(I('password'));
                $where = array(
                    'login_name' => $login_name,
                    'password'   => $password,
                );
            //判断验证码
            if($this->check_verify($verify)){
                $login=$admin->where($where)
                      ->find();
                //判断管理员信息
                if($login){
                    $admin_id = $login['admin_id'];
                    cookie('admin',$admin_id);
                    $admin->where(array("admin_id"=>$admin_id))
                            ->save(array("login_time"=>time(),"login_ip"=> get_client_ip(),));
                    $this->ajaxReturn(3);   //登录成功
                }else {
                    $this->ajaxReturn(2);//账号密码错误
                }
            }else{
                $this->ajaxReturn(1);  //验证码错误
            }
        }else{
            $this->display();
        }
    }
    
    function isLogin(){
        $this->display();
    }

    /*生成验证码*/
    function verify(){
        $config = array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'useCurve'    =>    false, // 关闭混淆曲线
            'imageW'      =>    145,
            'imageH'      =>    40,
        );
        $verify = new \Think\Verify($config);
        $verify->entry(); 
    }
    
    /*验证码验证*/
    function check_verify($ver){
        $verify = new \Think\Verify();
        //验证码错误
        if(!$verify->check($ver)){
            return false;
        } 
        return true;
    }
}