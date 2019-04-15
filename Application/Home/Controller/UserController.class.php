<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class UserController extends HomeBaseController {

    
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
    public function leaf(){
          $limit = I("limit");
            if(!$limit){
                $limit = 10;
            }
        if($this->sex != "all"){
        $where = array(
            "uid"=>$this->user_id,
            "is_delete"=>0
        );  
        }else{
             $where = array(  
                "is_delete"=>0
            );
        }   
        
//        D("device")->getLastSql
        $res = D("device")->where($where)->field("device_code")->select();
        $str='';
        foreach ($res as $k => $v){
            $str.=$v['device_code'].',';
        }
        $device_codes=substr($str,0,strlen($str)-1);
        $map['device_code'] = array("in",$device_codes);
        $count = D("withered_leaf")
                    ->where($map)
                    ->count();
        $Page=new \Org\Bjy\Page($count,$limit);
        $all = D("withered_leaf")
                    ->where($map)
                    ->limit($Page->firstRow.','.$Page->listRows)
                    ->select();
        
        $show       = $Page->show();// 分页显示输出
        $this->assign('result',$all);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
        $this->display();
    }
    public function leafAdd(){
        $param =   I("param.");
        if(IS_POST){
            $param['unix_createdate'] = strtotime($param['unix_createdate']);
            D("withered_leaf")->create($param);
            $row = D("withered_leaf")->add();
            if($row){
                $data = array(
                    "info"=>"添加成功",
                    "state"=>1
                    );
               
            }else{
                $data = array(
                    "info"=>"添加失败",
                    "state"=>2
                    );
            }
             $this->ajaxReturn($data);
        }
         if($this->sex != "all"){
        $where = array(
            "uid"=>$this->user_id,
            "is_delete"=>0
        );  
        }else{
             $where = array(  
                "is_delete"=>0
            );
        }   
        $data = D("device")->where($where)->field("device_code")->select();
        $this->assign("data",$data);

         $this->display();
    }
    public function leafEdit(){
        $id = I("id");
       $where = $this->getUserMap();  
        if(IS_POST){
            $param = I("param.");
            $eid = $param['eid'];
            $param['unix_createdate'] = strtotime($param['unix_createdate']);
            $bool = D("withered_leaf")->where(array("wl_id"=>$eid))->save($param);
            if($bool||$bool==1){
                $data = array(
                    "info"=>"修改成功",
                    "state"=>1
                    );
            }else{
                $data = array(
                    "info"=>"修改失败",
                    "state"=>2
                );
            };
            $this->ajaxReturn($data);
        }
        
        $item = D("withered_leaf")->where(array("wl_id"=>$id))->find();
        $data = D("device")->where($where)->field("device_code")->select();
        $this->assign("data",$data);
//        p($item);
        $this->assign("item",$item);
         $this->display();
    }
    public function leafDel(){
        $did = I("did");
        $bool = D("withered_leaf")->where(array("wl_id"=>$did))->delete();
        if($bool){
            $data = array(
                "info"=>"删除成功",
                "state"=>1
            );
        }else{
            $data = array(
                "info"=>"删除失败",
                "state"=>2
                );
        }
        $this->ajaxReturn($data);
        $this->display();
    }
    public function getUserMap(){
        if($this->sex != "all"){
            $where = array(
                "uid"=>$this->user_id,
                "is_delete"=>0
            );  
        }else{
            $where = array(  
                "is_delete"=>0
            );
        }   
        return $where;
    }
    public function getDeviceCodes(){
        $where = $this->getUserMap();
        $device_codes = D("device")->where($where)->field("device_code")->select();
        return $device_codes;
    }
    public function pestPicture(){
        $device = D("device");
        $device_codes = $this->getDeviceCodes();
        foreach ($device_codes as $key => $value) {
            $deaddress = $device->where(array('device_code'=>$value['device_code']))->field('deaddress')->find();
            $device_codes[$key]['deaddress'] = $deaddress['deaddress'];
        }
//        p($device_codes);
        $this->assign("device_codes",$device_codes);
        $this->display();
    }
    
    public function pictureList(){
        $limit = I("limit");
        if(!$limit){
            $limit = 10;
        }
        $startTime = strtotime(I("startTime"));  //获取时间插件时间戳格式
        $endTime = strtotime(I("endTime"));   //获取时间插件时间戳格式
        $device_code = I("device_code");
        $where = array();
        $device_codes = $this->getDeviceCodes();
        $ds = arr2str($device_codes);
        
         //判断当时间插件内容不为空时，进行条件判断
        if (!empty($startTime) && !empty($endTime)) {
            $where["unix_createdate"] = array(array("gt", $startTime), array("lt", $endTime));
        }
        if(!empty($device_code)){
            $map['device_code'] = $device_code;
        }else{
            $map['device_code'] = array('in',$ds);
        }
        $map['imgstatus'] =  2;
        
    $count = $this->device
                ->where($where)
                ->table("iot_device as d")
                ->join("iot_region as r on d.re_id = r.region_id","left")
                ->join("iot_user as u on d.uid=u.user_id","left")
                ->count();

        $pestInfo = D("pest_data")    //获取分页数据
                ->where($where)
                ->where($map)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->select();
        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->device->where($where)
                ->table("iot_device as d")
                ->join("iot_region as r on d.re_id = r.region_id","left")
                ->join("iot_user as u on d.uid=u.user_id","left")
                ->order('device_id desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('result',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
         
        

       
        
        
        $this->display();
    }
    
    public function pestPictureList(){
        $draw = I("draw");
        $start = I("start");
        $length = I("length");
        $startTime = strtotime(I("startTime"));  //获取时间插件时间戳格式
        $endTime = strtotime(I("endTime"));   //获取时间插件时间戳格式
        $device_code = I("device_code");
        $where = array();
        $device_codes = $this->getDeviceCodes();
        $ds = arr2str($device_codes);
        //判断当时间插件内容不为空时，进行条件判断
        if (!empty($startTime) && !empty($endTime)) {
            $where["unix_createdate"] = array(array("gt", $startTime), array("lt", $endTime));
        }
        if(!empty($device_code)){
            $map['device_code'] = $device_code;
        }else{
            $map['device_code'] = array('in',$ds);
        }
        $map['imgstatus'] =  2;
        
        $pestInfo = D("pest_data")    //获取分页数据
                ->where($where)
                ->where($map)
                ->order("unix_createdate desc")
                ->limit($start, $length)
                ->select();
        $absPath = realpath(__ROOT__);
        foreach ($pestInfo as $key => $value) {
            $path=str_replace('/','\\',$value['image']);
            $imgPath = $absPath.$path;
            $bool = file_exists($imgPath);
            
            if($bool){
                $pestInfo[$key]['file_exists'] = 1;
                
            }else{
                $pestInfo[$key]['file_exists'] = 0;
                
            }
           
        }
        $recordsTotal =  D("pest_data")
                ->where($map)
                ->count();
//        p( D("pest_data")->getLastSql());
        $recordsFiltered =  D("pest_data")
                ->where($where)
                ->where($map)
                ->count();
//        p( D("pest_data")->getLastSql());
        $arr = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $pestInfo,
        );
  
            $this->ajaxReturn($arr);
   
    }
    public function infoPestYes(){
         $id = I("id");
        $save = array(
            "imgstatus" => 4,
        );
        $bool = D("pest_data")->where(array("pd_id"=>$id))->save($save);
        if($bool||$bool==1){
            $data = array(
                "info"=>"标记成功",
                "state"=>1,
            );
            
        }else{
             $data = array(
                "info"=>"标记失败",
                "state"=>2,
            );
        }
        $this->ajaxReturn($data);
    }
    
    function infoPestNo(){
        $id = I("id");
        $save = array(
            "imgstatus" => 3,
        );
        $bool = D("pest_data")->where(array("pd_id"=>$id))->save($save);
        if($bool||$bool==1){
            $data = array(
                "info"=>"标记成功",
                "state"=>1,
            );
            
        }else{
             $data = array(
                "info"=>"标记失败",
                "state"=>2,
            );
        }
        $this->ajaxReturn($data);
    }
    
    function datadel(){
       $ids =  I("ids");
//       p($ids);
       $where['pd_id'] = array("in",$ids);
       $save = array(
           "imgstatus" => 3,
       );
       $bool = D("pest_data")->where($where)->save($save);
       if($bool||$bool == 1){
           $data = array(
               "info"=>"标记成功",
               "status" =>1
           );
       }else{
            $data = array(
               "info"=>"标记失败",
               "status" =>2
           );
       }
       $this->ajaxReturn($data);
    }
    public function home(){
        $this->display();
    }
    public function device(){
    $limit = I("limit");
    if(!$limit){
        $limit = 10;
    }
    if($this->sex != "all"){
        $where = array(
            "uid"=>$this->user_id,
            "d.is_delete"=>0
        );  
    }else{
         $where = array(
             
            "d.is_delete"=>0
        );
    }   
    $count = $this->device
                ->where($where)
                ->table("iot_device as d")
                ->join("iot_region as r on d.re_id = r.region_id","left")
                ->join("iot_user as u on d.uid=u.user_id","left")
                ->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->device->where($where)
                ->table("iot_device as d")
                ->join("iot_region as r on d.re_id = r.region_id","left")
                ->join("iot_user as u on d.uid=u.user_id","left")
                ->order('device_id desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('result',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
         
         

       
        
    }
    
    
    public function deviceShow(){
        $id = I("id");
       
        $data = $this->device->where(array("device_id"=>$id))
                ->table("iot_device as d")
                ->join("iot_region as r on d.re_id = r.region_id","left")
                ->find();
       $this->assign("only",$data);
       $this->display();
    }
    public function deviceEdit(){
        if(IS_GET){
            $id = I("id");
            $item = $this->device->where(array("device_id"=>$id))->find();
            $re_data = $this->region->where("create_id = $this->user_id")->select();
            $this->assign("item",$item);
            $this->assign("re_data",$re_data);
           $this->display();
        }
        if(IS_POST){
            $eid = I("eid");
            $post = I("post.");
            $post['deinst_time'] = strtotime($post['deinst_time']);
            $post['deremove_time'] = strtotime($post['deremove_time']);
            $post['custom_name'] = trim($post['custom_name']);
            $post['deaddress'] = trim($post['deaddress']);
           
            $post['degps'] = trim($post['degps']);
            $this->device->create($post);
            $bool = $this->device->where(array("device_id"=>$eid))->save();
            if($bool == 0 || $bool){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
            
            
        }
       
    }  
    
    
    public function index(){
        $this->assign("src","home.html");
        $this->display();
    }
     public function index1(){
        $this->assign("src","region.html");
        $this->display("index");
    }
    public function region(){
        $limit = I("limit");
            if(!$limit){
                $limit = $this->limit;
            }
                $where = array(
                    "is_delete"=>0,
                    "create_id"=>$this->user_id
                    );
                
            $count = $this->region
                            ->where($where)
                            ->count();

            $Page=new \Org\Bjy\Page($count,$limit);
            $list=$this->region
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
            $this->ajaxReturn("删除成功");
        }else{
            $this->ajaxReturn("删除失败");
        }
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
    
    public function pwdEdit() {
        
        
        if(IS_POST){
            $param = I("param.");
            $param['password'] = trim($param['password']);
            $param['newPwd'] = trim($param['newPwd']);
            $item = $this->user->where("user_id=$this->user_id")->find();
            $password = $item['password'];
            $data = array(
                    "password"=>md5(md5(md5(md5(md5($param['newPwd']))))),
                );
            if($password == md5(md5(md5(md5(md5($param['pwd'])))))){
                $bool = $this->user->where("user_id=$this->user_id")->save($data);
               
                if($bool == 0||$bool){
                    $this->ajaxReturn("修改成功");
                }else{
                    $this->ajaxReturn("修改失败");
                }
            }else{
                $this->ajaxReturn("原密码错误");
            }
        }
        if(IS_GET){
            $this->display();  
        }
        
    }
    public function userPro() {
        
       
        if(IS_GET){
            $item = $this->user->where("user_id = $this->user_id")
                ->table("iot_user as u")
                ->find();
            $this->assign("item",$item);
            $this->display();  
        }
        if(IS_POST){
            $param =  I("param.");
            $param['name'] = trim($param['name']);
             $param['unit_name'] = trim($param['unit_name']);
            $param['phone'] = trim($param['phone']);
            $param['address'] = trim($param['address']);
            $param['email'] = trim($param['email']);
            $param['remark'] = trim($param['remark']);
               
               
               
           $bool =  $this->user ->where("user_id = $this->user_id")->save($param);
           if($bool == 0 || $bool){
               $this->ajaxReturn("修改成功");
           }else{
               $this->ajaxReturn("修改失败");
           }
           
        }
        
        
 
    }
}
