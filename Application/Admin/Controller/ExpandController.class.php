<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class ExpandController extends AdminBaseController {
    //声明城市节点对象
    private $city;
            
    function _initialize() {
        //实例化城市节点对象
       $this->city = D("User_node");
        parent::_initialize();
    } 
/*省市区县展示*/
public function cityList(){
        $all  = $this->city
                     ->where(array("is_delete = 0"))
                     ->order("city_id")
                     ->select();
        $this->assign(array("all"=>$all));
        $this->display();
    } 
/*省市区县添加*/
public function cityAdd(){
        $cpid = I("cpid");
    if(IS_POST){
        $post = I("post.");
        if($cpid){
            //cpid有值就进行子城市的添加
            $parent = $this->city
                           ->where(array("city_id"=>$cpid))
                           ->find();
            $post['clevel'] = $parent['clevel']+1;
             $post['ccity'] = trim($post['ccity']);
             $post['cremark'] = trim($post['cremark']);
            $where = array(
                    "ccity"=>$post['ccity'],
                    "cpid" => $post['cpid']
                    );
            $bool = $this->city->where($where)->find();
            if($bool){
                $this->ajaxReturn(false);
            }
            $this->city->create($post);
            $num = $this->city->add();
            $this->ajaxReturn($num);
        }else{
            //cpid无值就进行顶级城市的添加
            $post['clevel'] = intval(1);
            $post['cpid'] = intval(0);
            $post['ccity'] = trim($post['ccity']);
            $where = array(
                    "ccity"=>$post['ccity'],
                    "cpid" => $post['cpid']
                    );
            $bool = $this->city->where($where)->find();
            if($bool){
                $this->ajaxReturn(false);
            }
            $post['cremark'] = trim($post['cremark']);
            $this->city->create($post);
            $num = $this->city->add();
            $this->ajaxReturn($num);
        }
    }else{
        //post无值添加页面展示
        $id = I("id");
        if(!empty($id)){
            //进行编号回显
            $this->assign(array("cpid"=>$id));
            $this->display();
        }else{
            //展示页面
            $this->display();
        }
        
        
    }
}  
/**省市区县删除*/
public function cityDel(){
    $id = I("id");
    $user_node = D("user_node");
    $item = $user_node -> where(array("cpid"=>$id))->find();
    if($item){
        $this->ajaxReturn("有下级城市，请删除后再试！");
    }else{
        $bool= $user_node ->where(array("city_id"=>$id))->delete();
        if($bool){
            $this->ajaxReturn("已删除");
        }else{
             $this->ajaxReturn("删除失败");
        }
        
        
    }
}
function typeList(){
    $limit = I("limit");
    if(!$limit){
        $limit = 20;
    }
    $where = array("is_delete"=>0);
    $pest_count = D("type")->where(array("type"=>1))->where($where)->count();
    $dis_count =  D("type")->where(array("type"=>2))->where($where)->count();
        $pestPage=new \Org\Bjy\Page($pest_count,$limit);
        $disPage=new \Org\Bjy\Page($dis_count,$limit);
    $pest_type = D("type")->where(array("type"=>1))->limit($Page->firstRow.','.$Page->listRows)->select();
    $dis_type = D("type")->where(array("type"=>2))->limit($Page->firstRow.','.$Page->listRows)->select();
    $pest_show  = $pestPage->show();// 分页显示输出
    $dis_show  = $disPage->show();// 分页显示输出
    $this->assign("pest_type",$pest_type);
    $this->assign("dis_type",$dis_type);
      $this->assign('pest_page',$pest_show);// 赋值分页输出
       $this->assign('dis_page',$dis_show);// 赋值分页输出
        $this->display();
}
function typeAdd(){
$data = array();
if(IS_POST){
       $post = I("post.");
    foreach ($post as $key=>$value){
        $data[$key] = trim($value);
    }
    $data['unix_cretae_time'] = time();
    $data['ty_status'] = 1;
//    $data['img'] = $data["type_$type"];
   $type_id =  D("type")->add($data);
   if($type_id){
       $this->ajaxReturn("添加成功");
   }else{
       $this->ajaxReturn("添加失败");
   }
}else{
    $this->display();
}  
}
function typeEdit(){
    if(IS_POST){
        $post = I("post.");
        $id = I("post.id");
        foreach ($post as $key=>$value){
            $data[$key] = trim($value);
        }
        $data['unix_modify_time'] = time();
//        $data['img'] = $data["type_$type"];
       $boolean =  D("type")->where(array("ty_id"=>$id))->save($data);
       if($boolean){
           $this->ajaxReturn("添加成功");
       }else{
           $this->ajaxReturn("添加失败");
       }
    }
    else{
        $ty_id = I("id");
        $item = D('type')->where(array("ty_id"=>$ty_id))->find();
        $this->assign("item",$item);
        $this->display();
    }
    
}
function typeDel(){
    $id = I("post.id");
    $boolean = D("type")->where(array("ty_id"=>$id))->delete();
    if($boolean){
        $this->ajaxReturn(array("info"=>"删除成功","status"=>1));
    }else{
         $this->ajaxReturn(array("info"=>"删除失败","status"=>2));
    }
    $this->display();
}



/**文件上传
    
    function upload(){
        
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $rootPath = $upload->rootPath  =      './Upload/images/'; // 设置附件上传根目录
        $upload->savePath = "slide/image/";
        $upload->autoSub = true; 
        
        $info=$upload->upload();
        $info = $info['imgFile'];
        session("info", $info);
        $savepath = $info['savepath'];
        $savename = $info['savename'];
        $url = $rootPath .$savepath.$savename;
        if(!$info){
           $this->error($upload->getError());
        }
        $this->ajaxReturn(array("error"=>0,"url"=> $url)) ;
        
}*/
    
/**
     * 友链展示
     
    function linksList(){
    
    $all = $this->links->where(array("is_delete"=>0))
            ->select();
    $count = $this->links->where(array("is_delete"=>0))
            ->count();
    $this->assign(array("all"=>$all,"count"=>$count));
    
    $this->display();
}*/
    
    /**
     * 友链添加
     
    function linksAdd(){
    $param = I("param.");
    //添加
    if(IS_POST){
        $param['create_time'] = time();
        $this->links->create($param);
        $num = $this->links->add();
        $this->ajaxReturn($num);
        
    }else{
        //展示添加界面
        $this->display();
    }
 
}*/
    
    /**
     * 友链编辑
     
    function linksEdit(){
    $eid = I("eid");
    //数据回显
    if(!IS_POST){
       
        $only = $this->links->where(array("links_id"=>$eid))
                ->find();
        $this->assign(array("only"=>$only));
        $this->display();
    }else{
        //数据修改
        $param = I("param.");
        $param['modify_time'] = time();
        $this->links->create($param);
        $num = $this->links->where(array("links_id"=>$eid))->save();
        $this->ajaxReturn($num);
    }
}*/
    
    /**
     * 友链停用
     
    function linksStop(){
    $id = I("id");
    $num = $this->links->where(array("links_id"=>$id))->save(array("status"=>0));
    $this->ajaxReturn($num);
}*/

    /**
     * 友链启用
     
    function linksStart(){
    $id = I("id");
    $num = $this->links->where(array("links_id"=>$id))->save(array("status"=>1));
    $this->ajaxReturn($num);    
    
}*/

    /**
     * 删除友链
     
    function linksDel(){
    $id = I("id");
    $num = $this->links->where(array("links_id"=>$id))->save(array("is_delete"=>1));
    $this->ajaxReturn($num);
}*/





}

