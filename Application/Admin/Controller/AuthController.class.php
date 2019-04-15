<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class AuthController extends AdminBaseController {
    
    function _initialize() {
        parent::_initialize();
       parent::is_login();
    }
    /**********************用户组***********************************************/
    function checkUser(){
        if(IS_GET){
           $group_id = I("id");
           $group_name= D("auth_group")->getFieldById($group_id,'title');
            $uids = D("auth_group_access")->where(array("group_id"=>$group_id))->getField('uid',true);
//            p($uids);
//            exit;
//           $where['ag.id'] = array("neq",$group_id);
            $result = D("admin")->where("is_delete=0")                   
                    ->select();
             $this->assign("result",$result);   
              $this->assign("group_id",$group_id);  
               $this->assign("group_name",$group_name);   
                 $this->assign("uids",$uids);   
            $this->display();
        }
        if(IS_POST){
           $uid =  I("uid");
           $group_id = I("id");
           $data = array(
               "uid"=>$uid,
               "group_id"=>$group_id,
           );
           $id = D("auth_group_access")->add($data);
           if($id){
               $this->ajaxReturn("设置成功");
           }else{
               $this->ajaxReturn("设置失败");
           }
        }
       
    }
    
    
    function groupRule(){
        if(IS_GET){
            $id = I("id");
            $result = D("auth_rule")->order("order_number asc")->select();
            $rules = D("auth_group")->where(array("id"=>$id))->getField('rules',true);
             
            $rule_arr = explode(",", $rules[0]);
              
            $arr = $this->recursion($result,0);

            $this->assign("arr",$arr);
            $this->assign("id",$id);
             $this->assign("rule",$rule_arr);
            $this->display();
            
        }
        if(IS_POST){
            $post = I("post.");
            $string = implode(",",$post['checkbox'] );
            $data = array(
                "rules" => $string,
                );
            D("auth_group")->create($data);
            $bool = D("auth_group")->where("id=%d",$post['id'])->save();
            if($bool || $bool == 0){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        
    }
    function groupList(){
        $result = D("auth_group")->select();
        $this->assign("result",$result);
        $this->display();
    }
    function groupAdd(){
        if(IS_POST){
            $post = I("post.");
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }
            
            D("auth_group")->create($post);
            $id = D("auth_group")->add($post);
            if($id){
                $this->ajaxReturn("添加成功");
            }else{
                $this->ajaxReturn("添加失败");
            }
        }
        if(IS_GET){
            $pid = I("pid");
            $this->assign("pid",$pid);
            $this->display();
        }
       
        
    }
    function groupEdit(){
       
        if(IS_POST){
            
            $post = I("post.");
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }
            D("auth_group")->create($post);
           $bool = D("auth_group")->where(array("id"=>$post['eid']))->save();
            if($bool||$bool == 0){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        if(IS_GET){
            $id = I("id");
            $item = D("auth_group")->where("id=$id")->find();
            $this->assign("item",$item);
             $this->display();
        }
       
    }
    function groupDel(){
        
        $id = I("id");
        $bool = D("auth_group")->where("id=%d",$id)->delete();
        if($bool){
            $this->ajaxReturn("删除成功");
        }else{
            $this->ajaxReturn("删除失败");
        }
    }
    /******************************规则********************************************************/
    /**/
    function ruleList(){
        $result  = D("auth_rule")->order("order_number asc")->select();
       $count =  D("auth_rule")->count();
        $this->assign("result",$result);
         $this->assign("count",$count);
        $this->display();
    }
    function ruleAdd(){
        if(IS_POST){
            $post = I("post.");
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }
            if($post['pid'] == 0){
                 $post['level'] = 1;
            }else{
                $item = D("auth_rule")->where(array("id"=>$post['pid']))->find();
                $post['level'] = $item['level']+1;
            }
            D("auth_rule")->create($post);
            $id = D("auth_rule")->add();
            if($id){
                $this->ajaxReturn("添加成功");
            }else{
                $this->ajaxReturn("添加失败");
            }
        }
        IF(IS_GET){
            $pid = I("pid");
            $this->assign("pid",$pid);
            $this->display();
        }
    }
    function ruleEdit(){
        if(IS_POST){
            $post = I("post.");
            foreach ($post as $k => $v) {
                $post[$k] = trim($v);
            }
            D("auth_rule")->create($post);
            $bool = D("auth_rule")->where(array("id"=>$post['eid']))->save();
            if($bool||$bool == 0){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        if(IS_GET){
            
            $id = I("id");
           $item =  D("auth_rule")->where("id=$id")->find();
           $this->assign("item",$item);
            $this->display();
        }
        
    }
    function ruleDel(){
        $id = I("id");
        $item = D("auth_rule")->where(array("pid"=>$id))->find();
        if($item){
            $this->ajaxReturn("含有子规则，无法删除");
        }
        $bool = D("auth_rule")->where(array("id"=>$id))->delete();
        if($bool){
            $this->ajaxReturn("删除成功");
            
        }else{
            $this->ajaxReturn("删除失败");
        }
    }
    function ruleOrder(){
        $post = I("post.");
        $rule = D("auth_rule");
        foreach ($post as $key => $value) {
            $data['order_number'] = $value;
            $bool = $rule->where("id=%d",$key)->save($data);
            if($bool!=0&& !$bool){
                $this->ajaxReturn("排序失败");
            }
        }
        $this->ajaxReturn("排序成功");
        
    }
    
    /*
     * 递归遍历
     * @param $data array
     * @param $id int
     * return array
     * */
        function recursion($data, $id=0) {
            $list = array();
            foreach($data as $v) {
                if($v['pid'] == $id) {
                    $v['son'] = $this->recursion($data, $v['id']);
                    if(empty($v['son'])) {
                        unset($v['son']);
                    }
                    array_push($list, $v);
                }
            }
            return $list;
        }
    }

