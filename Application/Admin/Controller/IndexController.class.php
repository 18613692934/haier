<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class IndexController extends AdminBaseController {
    //声明管理员表对象
    private $admin;
    //声明管理员id
    private $admin_id;
    /*初始化方法*/
    function _initialize() {
        parent::_initialize();
        //实例化管理员对象
        $this->admin = D("Admin");
        //从cookie中取出管理员id值
         $this->admin_id = cookie("admin");
    }
    
    /*后台首页*/
    public function index(){
        $admin_id = $this->admin_id ;
        
        $only = $this->admin
                     ->where(array("admin_id"=>$admin_id))
                     ->find();
        $webname = D("web_info")->where(array("info_name"=>"webname"))->find();
        $this->assign(array("only"=>$only,"webname"=>$webname));
        $this->display();
    }
    
    
    
    /**管理员退出*/
    public function quit(){
        cookie("admin", null);
        $this->redirect("Login/login");
    }
    
    /*管理员展示*/
    function adminShow(){
        $id = I("id");
            $only = $this->admin
                         ->where(array("admin_id"=>$id))
                         ->find();
        $this->assign(array("only"=>$only));
            $this->display();
    }

    /*环境界面*/
    function welcome(){ 
        $admin_id = $this->admin_id ;
        $only  = $this->admin
                      ->where(array("admin_id"=>$admin_id ,"is_delete"=>0))
                      ->find();
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
        );
        $this->assign(array('info'=>$info,"only"=>$only));
        $this->display();
    }
}