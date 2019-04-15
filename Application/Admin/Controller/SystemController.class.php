<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class SystemController extends AdminBaseController {
    
    private $region;
    
    private $city;
    
    private $web_info;
    
    private $admin_id;
    
    public function _initialize() {
        parent::_initialize();
        $this->region = D("region");
        $this->web_info = D("web_info");
        $this->city = D("user_node");
        $this->admin_id = cookie("admin");
    }
    
/**
 * 网站设置
 */
function webSetup(){
   
  
    if(IS_POST){
       
        $file = $_FILES['pic1'];
        if($file){
            $info = upload($file);
    //        p($info);
            if(!$info){
                $this->ajaxReturn(array("info"=>"图片上传失败","status"=>2));
            }
            $num = $this->web_info->where(array("info_name"=>"logo"))->save(array("info_value"=>$info['savepath'].$info['savename']));
            if(!$num&&num!=0){
                $this->ajaxReturn(array("info"=>"图片上传失败","status"=>2));
            }
        }
                $post = I("post.");
               
                foreach ($post as $key => $value) {
                    $value = trim($value);
                    $where = array("info_name"=>$key);

                    $row = $this->web_info->where($where)->save(array("info_value"=>$value));
                    if(0!=$row&&!$row){
                        $this->ajaxReturn(array("info"=>"保存失败","status"=>2)); 
                    }
                }
                 $this->ajaxReturn(array("info"=>"保存成功","status"=>1));
          
     }else{
         $data = array();
//         $where['info_type'] = array("EGT","0");
         $item = $this->web_info->where(array("info_name"=>"logo"))->find();
             $result = $this->web_info->where($where)->select();
//             p($result);
//             exit;
             foreach($result as $key=>$value){
                $data[$value['info_name']] = array("info_value"=>$value['info_value'],"info_title"=>$value['info_title']);
             }
             $this->assign(array("v"=>$data,"item"=>$item));
             $this->display();
     }


}
   
/*区域展示*/  
function regionList(){
    $limit = I("limit");
    if(!$limit){
        $limit = 20;
    }
        $where = array("is_delete"=>0);
       
    $count = $this->region->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->region->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('result',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
}
/*城市三级联动处理方法*/
function region(){
    if(IS_AJAX){
        $id = I("value");
        if($id != 0){
             $name = I("name");
            $data = $this->city->where(array("cpid"=>$id))
                         ->select();
            $this->ajaxReturn($data);
        }
       
    }
}
/**区域搜索*/
function regionSearch(){
    $name = I("name");
    $limit = I("limit");
    if(!$limit){
        $limit = 20;
    }
    $where = array("is_delete"=>0,"rnstatus"=>1);
    $where['rnname|rnprovince|rncity|rncounty|rndescription'] = array("like","%$name%");
    $count = $this->region->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->region->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('result',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display("regionList");
}
/*区域添加 */
function regionAdd(){
    $admin_id = $this->admin_id;
    if(IS_POST){
        $param = I("param.");
        $province = $this->city->where(array("city_id"=>$param['sheng']))->field("ccity")->find();
        $city = $this->city->where(array("city_id"=>$param['city']))->field("ccity")->find();
        $county = $this->city->where(array("city_id"=>$param['xian']))->field("ccity")->find();
        if($province){
            $param['rnprovince'] = $province['ccity'];
        }
        if($city){
            $param['rncity'] = $city['ccity'];
        }
        if($county){
            $param['rncounty'] = $county['ccity'];
        }
        $param['rnname'] = trim($param['rnname']);
        $where = array(
            "is_delete"=> 0,
            "rnstatus" => 1,
            "rnname" => $param['rnname'],
        );
        $bool = $this->region->where($where)->find();
        if($bool){
            $this->ajaxReturn("区域名已存在");
            exit;
        }
        $param['rnprovince_id'] = $param['sheng'];
        $param['rncity_id'] = $param['city'];
        $param['rncounty_id'] = $param['xian'];
        $param['unix_create_time'] = time();
        $param['create_id'] = $admin_id;
        $this->region->create($param);
        $num = $this->region->add();
        if($num){
            $this->ajaxReturn("添加成功");
        }else{
            $this->ajaxReturn("添加失败");
        }
    }else{
        $city = $this->city->where(array("cpid"=>0,'is_delete'=>0))->select();
        $this->assign(array("sheng"=>$city));
        $this->display();
    }
}
/*区域删除*/
function regionDel(){
    $id = I("id");
    $bool = $this->region->where(array("region_id"=>$id))->delete();
    if($bool){
         $this->ajaxReturn("已删除");
    }else{
         $this->ajaxReturn("删除失败");
    }
   
    
}
/*区域编辑*/
function regionEdit(){
    $admin_id = $this->admin_id;
    if(IS_GET){
        $id = I("id");
        $where = array(
            "region_id" => $id,
            "is_delete" => 0,
            "rnstatus" => 1,
            );
        $only = $this->region
                     ->where($where)
                     ->find();
        $map = array(
            "cpid"=>0,
            "is_delete"=> 0,
        );
        $sheng = $this->city->where($map)->select();
        $this->assign(array("sheng"=>$sheng,"only"=>$only));
        $this->display(); 
    }
    if(IS_POST){
        $id =  I("id");
        $param = I("param.");
        $province = $this->city->where(array("city_id"=>$param['sheng'],"is_delete"=>0))->field("ccity")->find();
        $city = $this->city->where(array("city_id"=>$param['city'],"is_delete"=>0))->field("ccity")->find();
        $county = $this->city->where(array("city_id"=>$param['xian'],"is_delete"=>0))->field("ccity")->find();
        if($province){
            $param['rnprovince'] = $province['ccity'];
        }
        if($city){
            $param['rncity'] = $city['ccity'];
        }
        if($county){
            $param['rncounty'] = $county['ccity'];
        }
        $param['rnname'] = trim($param['rnname']);
        $where = array(
            "is_delete"=> 0,
            "rnstatus" => 1,
            "rnname" => $param['rnname'],
        );
        $where['region_id'] = array("neq",$id);
        $bool = $this->region->where($where)->find();
        if($bool){
            $this->ajaxReturn("区域名已存在");
            exit;
        }
        $param['rnprovince'] = $province['ccity'];
        $param['rncity'] = $city['ccity'];
        $param['rncounty'] = $county['ccity'];
        $param['unix_modify_time'] = time();
        $param['modify_id'] = $admin_id;
        
        $this->region->create($param);
        $num = $this->region->where(array("region_id"=>$id,"is_delete"=>0))->save();
        if($num == 0|| $num){
            $this->ajaxReturn("修改成功");
        }else{
            $this->ajaxReturn("修改失败");
        }
       
    }   
}

/***************************后台菜单管理**********************************************/

function navList(){
    $this->display();
}
function navAdd(){
    $this->display();
}
function navEdit(){
    $this->display();
}





}

