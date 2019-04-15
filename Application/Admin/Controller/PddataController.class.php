<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class PddataController extends AdminBaseController {
    private $pddata;
    
    public function _initialize() {
        parent::_initialize();
        $this->pddata = D("Pddata");
    }
    
    /**
     * 病虫害资料展示
     */
    function pddataList(){
        

        $count = $this->pddata->count();
        $all= $this
                ->pddata
                ->where(array())
                ->order("addtime desc")      
               ->select();
        $this->assign(array("all"=>$all,"count"=>$count));
        $this->display();
    }
    
    /**
     * 病虫害资料添加
     */
    function pddataAdd(){
        if(IS_POST){
            $param = I("param.");
            $param['pubtime'] = time();
            $param['content'] = html_entity_decode($param['content']);
            $this->pddata->create($param);
            $num = $this->pddata->add();
            $this->ajaxReturn($num);
        }else{
            $this->display();
        }
    }
    
    
    
    /**
     * 资料编辑
     */
    function pddataEdit(){
       
        if(IS_POST){
            $param =  I("param.");
            $eid = $param['eid'];
            $param['pubtime'] = time();
            $param['content'] = html_entity_decode($param['content']);
            $this->pddata->create($param);
            $num = $this->pddata->where(array("data_id"=>$eid))->save();
            $this->ajaxReturn($num);
        }else{
             $id = I("id");
             $only = $this->pddata->where(array("data_id"=>$id))
                    ->find();
             $this->assign(array("only"=>$only));
            $this->display();
        }
        
    }
    
    
    /**
     * 资料删除
     */
    function pddataDel(){
       $id =  I("id");
       $bool = $this->pddata->where(array("data_id"=>$id))
                       ->delete();
       $this->ajaxReturn($bool);
    }
    /**
     * 资料状态修改  发布-撤销
     */
    function editStatus(){
       $id =  I("id");
       $only = $this->pddata->where(array("data_id"=>$id))
                    ->find();
       if($only['status'] == 1){
           $save = array("status"=>2);
       }else{
           $save = array("status"=>1);
       }
       $bool = $this->pddata->where(array("data_id"=>$id))
                    ->save($save);
       $this->ajaxReturn($bool);
    }

    /**
     * 单资料展示
     */
    function pddataShow(){
        $id = I("id");
        $only = $this->pddata->where(array("data_id"=>$id))
                       ->find();
        $this->assign(array("only"=>$only));
        $this->display();
    }


}

