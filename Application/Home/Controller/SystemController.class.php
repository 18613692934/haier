<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class SystemController extends HomeBaseController {

    
    private $device;
    private $user_id;
    private $user; 
    private $region;
    private $sex;
    public function _initialize(){
        parent::_initialize(); 
        $this->device = D("device");
        $this->region = D("region");
        $this->user_id = cookie('user');
        $this->user = D('user');
        $userData = $this->user->where(array("user_id"=>$this->user_id))->find();
        /*给予超级管理员一个标记，当标记为all时，判断为超级管理员，显示所有的设备情况*/
        $this->sex = $userData['sex'];
    }
    public function home(){
        $this->display();
    }
    public function index(){
        $this->assign("src","home.html");
        $this->display();
    }
    public function region(){
        $limit = I("limit");
            if(!$limit){
                $limit = $this->limit;
            }
            if($this->sex != "all"){
                $where = array(
                    "r.is_delete"=>0,
                    "r.create_id"=>$this->user_id
                );
            }else{
               $where = array(
                    "r.is_delete"=>0,
                    
                ); 
            }
                
                
            $count = $this->region
                            ->table("iot_region as r")
                            ->join("iot_user as u on r.create_id=u.user_id")
                            ->where($where)
                            ->count();
            
            $Page=new \Org\Bjy\Page($count,$limit);
            $list=$this->region
                        ->table("iot_region as r")
                        ->join("iot_user as u on r.create_id=u.user_id")
                        ->where($where)
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->select();
            $show       = $Page->show();// 分页显示输出
            $this->assign('result',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->assign("count",$count);
            $this->display();
     
    }
     public function regionAdd(){
            
          
                $node = D("user_node");
               
         if(IS_POST){
                $post = I("post.");
                 $sheng = $node->where(array("city_id"=>$post['sheng']))->find();
                 $city = $node->where(array("city_id"=>$post['city']))->find();
                  $xian = $node->where(array("city_id"=>$post['xian']))->find();
            $data = array(
                "rnname"=>$post['rnname'],
                "rnprovince_id" =>$post['sheng'],
                "rncity_id"=>$post['city'],
                "rncounty_id"=>$post['xian'],
                "unix_create_time"=>time(),
                "create_id"=>$this->user_id,
                "rnprovince" => $sheng['ccity'],
                "rncity" => $city['ccity'],
                "rncounty" => $xian['ccity'],
            );
                $this->region->create($data);
            $bool = $this->region->add();
                $this->ajaxReturn($bool);
         }
         if(IS_GET){
                $data = $node->where(array("cpid"=>0))->select();
                $this->assign("sheng",$data);
                
               $this->display();
         }
        
       
      
    }
     public function regionEdit(){
            $node = D("user_node");
        $user = cookie('user');
        $uid = $user['user_id'];
        if(IS_GET){
            $id = I("get.id");
            $item = $this->region->where(array("region_id"=>$id))->find();
            $sheng = $node->where("cpid = 0")->select();
            $this->assign("item",$item);
            $this->assign("sheng",$sheng);
            $this->display();
        }
        if(IS_POST){
            $id = I("post.id");
            $post = I("post.");
             $sheng = $node->where(array("city_id"=>$post['sheng']))->find();
                 $city = $node->where(array("city_id"=>$post['city']))->find();
                  $xian = $node->where(array("city_id"=>$post['xian']))->find();
            $data = array(
                "rnname"=>trim($post['rnname']),
                "rnprovince_id" =>$post['sheng'],
                "rncity_id"=>$post['city'],
                "rncounty_id"=>$post['xian'],
                "rnprovince" => $sheng['ccity'],
                "rncity" => $city['ccity'],
                "rncounty" => $xian['ccity'],
            );
            $this->region->create($data);
            $bool = $this->region->where("region_id = $id")->save();
            if($boo == 0 || $bool){
                $this->ajaxReturn(true);
            }else{
                $this->ajaxReturn(false);
            }
        }
        
    }
    
    function regionDel(){
        
        $id = I("id");
        
        $bool = D("region")->where(array("region_id"=>$id))->delete();
        if($bool){
            $array = array(
                "info"=>"删除成功",
                "state"=>1
            );
           
        }else{
             $array = array(
                "info"=>"删除失败",
                "state"=>2
            );
        }
        $this->ajaxReturn($array);
    }
    /**
 * 城市联动处理方法
 */
function regionAjax(){
    if(IS_AJAX){
        $id = I("value");
        $city = D("user_node");
        if($id != 0){
             $name = I("name");
            $data = $city->where(array("cpid"=>$id))
                         ->select();

            $this->ajaxReturn($data);
        }
       
    }
}
}
