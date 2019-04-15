<?php
namespace Common\Controller;
use Common\Controller\BaseController;

class UserBaseController extends BaseController {

       public function __construct(){
            parent::__construct();
            //重定向到登录页面
//            if(!cookie('uid')) $this->redirect('/Home/login/index');
    }
}
