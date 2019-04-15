<?php
namespace Common\Controller;
use Common\Controller\BaseController;

/**
 * Auth权限控制器基类
 */
class AuthBaseController extends BaseController {
   protected function _initialize(){  
        //session不存在时，不允许直接访问  
//        if(!session('aid')){  
//            $this->error('还没有登录，正在跳转到登录页',U('Public/login'));  
//        }  
//  
//        //session存在时，不需要验证的权限  
//        $not_check = array('Index/clear/cache',  
//            'Index/edit/pwd','Index/logout','Admin/admin_list',  
//            'Admin/admin/list','Admin/admin/edit','Admin/admin/add');  
//          
//        //当前操作的请求                 模块名/方法名  
//        if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)){  
//            return true;  
//        }  
          
        
    }  
}

