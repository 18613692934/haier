<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class PersonController extends AdminBaseController {
    
    function _initialize() {
        parent::_initialize();
    }
    
    function changePassword(){
        $admin_id = cookie("admin");
        $eid = I("eid");
        $param = I("param.");
        
        if(IS_POST){
            
            $password = trim($param['password']);
            $newpassword = trim($param['newpassword']);
            $data = D("admin")->where(array("admin_id"=>$eid))
                          ->find();
           if($data['password'] == md5($password)){
                if("" == trim($param['newpassword'])){
                    $this->ajaxReturn("密码未修改");
                }
                $data['password'] = md5($newpassword);
                D("admin")->create($data);
                $num = D("admin")->where(array("admin_id"=>$eid))
                            ->save();
                if($num==0 || $num){
                    $this->ajaxReturn("修改成功");
                }else{
                    $this->ajaxReturn("修改失败");
                }
           } else{
                $this->ajaxReturn("原密码错误");
           }
        }
        if(IS_GET){
            $only = D("admin")->where(array("admin_id"=>$admin_id,"is_delete"=>0))
                              ->find();
            if($only){
                $this->assign(array("only"=>$only));  
                $this->display();
            }else{
                $this->redirect("Login/login");
            }
        }
}
    
    
}

