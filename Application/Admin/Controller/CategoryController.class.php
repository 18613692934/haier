<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
//未完成
//
class CategoryController extends AdminBaseController {
    private $cat;
    public function _initialize() {
        parent::_initialize();
        $this->cat = D("Category");
    }
    
    
/*栏目列表*/   
function catList(){
    $where = array("cstatus"=>1,"is_delete"=>0);
    $all = $this->cat->where($where)->select();
    $this->assign(array("all"=>$all));
    $this->display();
}
/**
 * 栏目添加
 */
function catAdd(){
    if(IS_GET){
        $cpid = I("cpid");      
        $this->assign(array("cpid"=>$cpid));
        $this->display(); 
    }else{
        $param = I("param.");
        $cpid = $param['cpid'];
        if(0==$cpid){
            $param['clevel'] = 0;
            $param['cpid'] = 0;
            $this->cat->create($param);
            $num = $this->cat->add();
            $this->ajaxReturn($num);
        }else{
            $only = $this->cat->where(array("cat_id"=>$cpid))
                        ->find();
            $param['clevel'] = $only['clevel']+1;
            $param['cpid']  = $only['cat_id'];
            $this->cat->create($param);
            $num = $this->cat->add();
            $this->ajaxReturn($num);   
        }
    } 
}
/**
 * 栏目编辑
 */
function catEdit(){

    if(IS_POST){
        $param = I("param.");
        $param['modify_time'] = time();
        $eid = $param['eid'];
        $this->cat->create($param);
        $bool = $this->cat->where(array("cat_id"=>$eid))->save();
        $this->ajaxReturn($bool);
    }else{
        $id = I("id");
        $only = $this->cat->where(array("cat_id"=>$id))->find();
        $this->assign(array("only"=>$only));
        $this->display();
    }
    
}


/**
 * 栏目删除
 */
function catDel(){
    $id = I("id");
    $bool = $this->deleteallclass(array($id));
    $this->ajaxReturn($bool);
}
/**
 * 递归删除
 */
private function deleteallclass($ids){
    $mod = D('Category');
    $mod->delete(implode(',',$ids));
    foreach($ids as $k){
        $ret = $mod->where(array('cpid'=>$k))->getField('cat_id',true);
        if(empty($ret)){
            return true;
        }
        $ret2=implode(',',$ret);
        $bool = $mod->delete($ret2);
        if($bool){
             $this->deleteallclass($ret);
        }else{
            return false;
        }   
    }
    return true;
 }
 
 /**
     * 栏目停用（完成）
     */
    function catStop(){
        $id = I("id");
        $bool = $this->stopallclass(array($id));
        
        $this->ajaxReturn($bool);   
    }
    /**
     * 递归停用
     * @param type $ids
     * @return boolean
     */
   private function stopallclass($ids){
        $ret2 = array();
        $mod = D('Category');
        foreach ($ids as $va) {
            $ab = $mod->where(array("cat_id"=>$va,"cstatus"=>1))->save(array("cstatus"=>0));

            if(!$ab){
                return false;
            }
            
        }
        foreach ($ids as $va) {
             $ret = $mod->where(array('cpid'=>$va))->getField('cat_id',true);
             $ret2 = array_merge_recursive($ret2,$ret);
        }
    
             
           
        
        
        if(!empty($ret2)||null!==$ret2){
            $this->stopallclass($ret2);
        }
        return true;        
 } 
    
    /**
     * 栏目启用（完成）
     */
    function catStart(){
        $id = I("id");
        
        $bool = $this->startallclass(array($id));
        $this->ajaxReturn($bool);  
    }
 
    /**
     * 递归启用
     * @param array $ids  //
     * @return boolean   //是否成功
     */
    private function startallclass($ids){
        $ret2 = array();
        $mod = D('Category');
        foreach ($ids as $va) {
            $ab = $mod->where(array("cat_id"=>$va,"cstatus"=>0))->save(array("cstatus"=>1));
            if(!$ab){
                return false;
            }
        }
        foreach ($ids as $va) {
             $ret = $mod->where(array('cpid'=>$va))->getField('cat_id',true);
             $ret2 = array_merge_recursive($ret2,$ret);
        }
        if(!empty($ret2)||null!==$ret2){
            $this->startallclass($ret2);
        }
            return true; 

    }
/**
     * 导航停用（完成）
     */
    function navStop(){
        $id = I("id");

        $bool =  $this->cat->where(array("cat_id"=>$id))
                    ->save(array("cis_nav"=>0));
        $this->ajaxReturn($bool);   
    }
    
    /**
     * 导航启用（完成）
     */
    function navStart(){
        $id = I("id");

        $bool =  $this->cat->where(array("cat_id"=>$id))
                    ->save(array("cis_nav"=>1));
        $this->ajaxReturn($bool);  
    }

}