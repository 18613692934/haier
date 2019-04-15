<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class DeviceController extends AdminBaseController {

	private $device;
	private $alarm;
	private $command;
	private $user;
	private $region;
        private $admin_id;
        private $module;
        private $device_command;
        private $device_module;
         private $device_alarm;
         private $limit = 10;
	public function _initialize() {
		parent::_initialize();
		$this -> device = D('Device');
		$this -> user = D("User");
		$this -> alarm = D("Alarm");
		$this -> command = D("command");
                $this->module = D("module");
		$this -> region = D("region");
                $this -> admin_id = cookie("admin");
                $this->device_command = D("device_command");
		$this -> device_module = D("device_module");
               $this -> device_alarm = D("device_alarm");

	}

	/*设备列表*/
	function deviceList() {
            $limit = I("limit");
            if(!$limit){
                $limit = $this->limit;
            }
                $where = array("is_delete"=>0);
                
            $count = $this->device
                          ->where($where)
                          ->count();

            $Page=new \Org\Bjy\Page($count,$limit);
            $list=$this ->device
                        ->where($where)
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->select();

            $show       = $Page->show();// 分页显示输出
            $this->assign('result',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->assign("count",$count);
            $this->display();

	}
        /*设备搜索*/
        function deviceSearch(){
            $limit = I("limit");
            if(!$limit){
                $limit = $this->limit;
            }
            $post = I("post.");
            $startTime = strtotime($post['startTime']);
            $endTime = strtotime($post['endTime']);
            $data = $post['data'];
                 $where = array("is_delete"=>0);
            if($startTime&&$endTime){
                $where['depurchase_date'] = array(array('egt',$startTime),array('lt',$endTime));
            }
            if($data){
               $where['device_code|custom_name|demodel|deaddress'] = array('like', "%$data%"); 
            }
               
       
            $count = $this->device
                          ->where($where)
                          ->count();

            $Page=new \Org\Bjy\Page($count,$limit);
            $list=$this ->device
                        ->where($where)
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->select();

            $show       = $Page->show();// 分页显示输出
            $this->assign('result',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->assign("count",$count);
            
            
            
            $this->display("deviceList");
        }
	/*设备添加*/
	function deviceAdd() {
            if (IS_POST) {
			$error = array();
			$post = I("post.");
			$post['depurchase_date'] = strtotime($post['depurchase_date']);
                        $post['device_code'] = trim($post['code']);
                        $item = $this->device
                                ->where(array("device_code"=>$post['device_code'],"is_delete"=>0))
                                ->find();
                        if($item){
                            $this->ajaxReturn("cf");
                            exit;
                        }
                        $post['custom_name'] = trim($post['custom']);
                        $post['demodel'] = trim($post['model']);
                        $post['desource'] = trim($post['source']);
                        $post['dephone'] = trim($post['phone']);
			$cmdbox = $post['cmdbox'];
                       
                        $modulebox = $post['modulebox'];
                        
			$this -> device -> create($post);
			$id = $this -> device -> add();
			if ($id) {
                            $device = $post['device_code'];
                           if($this->device_command->where(array("device_code"=>$device_code))->find()){
                               $this->device_command->where(array("device_code"=>$device_code))->delete();
                           }
				foreach ($cmdbox as $value) {
                                    $cmdData = array(
                                        "device_code"=>$post['device_code'],
                                        "cmd_code" => $value,
                                        "default" => "",
                                    );
                                    $this->device_command->create($cmdData);
                                    $command_id = $this->device_command->add();
                                    /* @var $command_id int */
                                    if(!$command_id){
                                        $this -> ajaxReturn("添加失败");
                                    }
				}
                                
                                 if($this->device_module->where(array("device_code"=>$device_code))->find()){
                                     $this->device_module->where(array("device_code"=>$device_code))->delete();
                                }
                                foreach ($modulebox as $value) {
                                    $moduleData = array(
                                        "device_code"=>$post['device_code'],
                                        "module_id" => $value,
                                    );
                                    $this->device_module->create($moduleData);
                                    $module_id = $this->device_module->add();
                                    /* @var $command_id int */
                                    if(!$module_id){
                                        $this -> ajaxReturn("添加失败");
                                    }
				}
                                
                                
				$this -> ajaxReturn("添加成功");
			} else {
				$this -> ajaxReturn("添加失败");
			}

		} else {
			
			$cmddata = $this->command
                                        ->field("cmd_code,cmd_info")
                                        ->where(array("is_delete"=>0))
                                        ->select();
			$userdata = $this->user 
                                         ->field("user_id,uname") 
                                         ->where(array("is_delete"=>0))
                                         ->select();
			$moduledata = $this->module 
                                           ->field("module_id,momodel") 
                                           ->where(array("is_delete"=>0))
                                           -> select();
                        $assign = array(
                                "cmddata" => $cmddata,
                                "moduledata" => $moduledata, 
                                "userdata" => $userdata, 
                                );
			$this -> assign($assign);
			$this -> display();
		}

	}
	/*设备编辑*/
	function deviceEdit() {
                if(IS_POST){
                    $param = I("param.");
                    $param['depurchase_date'] = strtotime($param['depurchase_date']);
                  
                    $param['custom_name'] = trim($param['custom']);
                    $param['demodel'] = trim($param['model']);
                    $param['desource'] = trim($param['source']);
                    $param['dephone'] = trim($param['phone']);
                        $this->device->create($param);
                        $return = $this->device->where(array("device_id"=>$param['eid']))->save();
                        if(0 == $return||$return){
                            $this->ajaxReturn("修改成功");
                        }else{
                            $this->ajaxReturn("修改失败");
                        }
                }
                if(IS_GET){
                    $id = I("id");
                    $userdata = $this->user 
                                    ->field("user_id,uname") 
                                    ->where(array("is_delete"=>0))
                                    ->select();
                    $result = $this -> device 
                                    -> where(array("device_id" => $id,"is_delete"=>0)) 
                                    -> find();
                    $this -> assign(array(
                        
                        "userdata" => $userdata, 
                        "device" => $result
                            ));
			$this -> display();
                }
                
        }
	/**
	 * 设备删除
	 */
	function deviceDel() {
            $id = I("id");
		$where = array("device_id" => $id);
                $item = $this->device->where($where)->find();
                $map = array("device_code"=>$item['device_code']);
		$bool = $this -> device -> where($map) -> delete();
                    if($bool){
                        if($this->device_command->where($map)->find()){
                            $cmdbool = $this->device_command->where($map)->delete();
                            if($cmdbool){
                                if($this->device_module->where($map)->find()){
                                    $modulebool = $this->device_module->where($map)->delete();
                                    if($modulebool){
                                         $this->ajaxReturn("删除成功");
                                    }else{
                                         $this->ajaxReturn("删除失败");
                                    }
                                }
                                 $this->ajaxReturn("删除成功");
                            }else{
                                 $this->ajaxReturn("删除失败");
                            }
              
                        }
                         $this->ajaxReturn("删除成功");
                    }else{
                        $this->ajaxReturn("删除失败");
                    }
			
		
	}

	/**
	 * 单个设备展示
	 */
	function deviceShow() {
		$id = I("id");
		$only = $this -> device 
                        -> table("iot_device as d") 
                        -> join("iot_user as u on d.uid=u.user_id", "left") 
                        -> join("iot_region as r on d.re_id=r.region_id") 
                        -> join("iot_unit as un on d.un_id=un.unit_id", "left") 
                        
                        -> where(array("device_id" => $id)) -> find();
		$this -> assign(array("only" => $only));
		$this -> display();
	}

	

        
	/*模块管理 - 列表*/
	function moduleList() {
		
                    $limit = I("limit");
                if(!$limit){
                    $limit = 10;
                }
        $where = array("is_delete"=>0);
       
    $count = $this->module->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->module->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('all',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
          
		
	}
        
	/*模块管理 - 添加*/
	function moduleAdd() {
            $post = I("post.");
            if (IS_POST) {
   
                $post['momodel'] = trim($post['model']); 
                $post['moen'] = trim($post['en']); 
                $post['moabb'] = trim($post['abb']); 
                $post['moremark'] = trim($post['remark']); 
                $post['unix_create_time'] = time();
                $where  = array("is_delete"=>0);
                $module_item = $this->module->where(array("momodel"=>$post['momodel']))->where($where)->find();
                 $en_item = $this->module->where(array("moen"=>$post['moen']))->where($where)->find();
                  $abb_item = $this->module->where(array("moabb"=>$post['moabb']))->where($where)->find();
                   if($module_item){
                       $data = array("flag"=>"cf","info"=>"模块名已存在");
                       $this->ajaxReturn($data);
                   }
                    if($en_item){
                       $data = array("flag"=>"cf","info"=>"英文名已存在");
                       $this->ajaxReturn($data);
                   }
                    if($abb_item){
                       $data = array("flag"=>"cf","info"=>"英文缩写已存在");
                       $this->ajaxReturn($data);
                   }
                
               
                $this->module -> create($post);
                $return  = $this->module -> add();
                if($return){
                     $this -> ajaxReturn("添加成功");
                }else{
                     $this -> ajaxReturn("添加失败");
                }
               
            } else {
                $this -> display();
            }
	}
	/*** 模块搜索
	function moduleSearch() {
		$post = I("post.");
		$momodel = $post['momodel'];
		$module = D("Device_module");
		$where['momodel'] = array("like", "%" . trim($momodel) . "%");
		$all = $module -> where($where) -> select();
		$count = $module -> where($where) -> count();
		$this -> assign(array("all" => $all, "count" => $count));
		$this -> display("moduleList");
	}*/
	/*模块编辑*/
	function moduleEdit() {
		$post = I("post.");
		$id = I("id");
		
		if ($post) {
			$eid = $post['eid'];
                        $post['momodel'] = trim($post['model']); 
                $post['moen'] = trim($post['en']); 
                $post['moabb'] = trim($post['abb']); 
                $post['moremark'] = trim($post['remark']); 
			$post['unix_modify_time'] = time();
                        
                        $where  = array("is_delete"=>0);
                        $where['module_id'] = array("neq",$eid);
                $module_item = $this->module->where(array("momodel"=>$post['momodel']))->where($where)->find();
                 $en_item = $this->module->where(array("moen"=>$post['moen']))->where($where)->find();
                  $abb_item = $this->module->where(array("moabb"=>$post['moabb']))->where($where)->find();
                   if($module_item){
                       $data = array("flag"=>"cf","info"=>"模块名已存在");
                       $this->ajaxReturn($data);
                   }
                    if($en_item){
                       $data = array("flag"=>"cf","info"=>"英文名已存在");
                       $this->ajaxReturn($data);
                   }
                    if($abb_item){
                       $data = array("flag"=>"cf","info"=>"英文缩写已存在");
                       $this->ajaxReturn($data);
                   }
                        
                        
                        
                        
			$this->module -> create($post);
			$bool = $this->module -> where(array("module_id" => $eid)) -> save();
                        if(0 == $bool|| $bool){
                            $this -> ajaxReturn("修改成功");
                        }else{
                            $this -> ajaxReturn("修改失败");
                        }
			
		} else {
			$only = $this->module -> where(array("module_id" => $id,"is_delete"=>0)) -> find();
			$this -> assign(array("only" => $only));
			$this -> display();
		}

	}
	/*模块删除（虚拟删除）*/
	function moduleDel() {
            $id = I("id");
            $where = array("module_id"=>$id);
            $save = array("is_delete"=>1);
            $return  = $this->module -> where($where) -> save($save);
            if($return){
                $this -> ajaxReturn("删除成功");
            }else{
                $this -> ajaxReturn("删除失败");
            }
            

	}

	/*** 报警信息
	function alarmList() {
		  $limit = I("limit");
                if(!$limit){
                    $limit = 10;
                }
    
       
    $count = $this->alarm->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->alarm->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('all',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
		

	}*/
	/**
	 * 报警信息搜索
	 
	function alarmSearch() {
		$post = I("post.");
	}*/

	/**
	 * 报警删除
	 
	function alarmDel() {
		$id = I("id");
		$bool = $this -> alarm -> where(array("alarm_id" => $id)) -> delete();
		$this -> ajaxReturn($bool);
	}*/

	/***
	 * 报警解除
	 
	function alarmHandle() {
		$id = I("id");
		$num = $this -> alarm -> where(array("alarm_id" => $id)) -> save(array("state" => 1));
		$this -> ajaxReturn($num);
	}*/

	/*命令列表*/
	function commandList() {
                $limit = I("limit");
    if(!$limit){
        $limit = 10;
    }
        $where = array("is_delete"=>0);
       
    $count = $this->command->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->command->where($where)->order('cmd_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('all',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();

	}
	/*
	 * 命令查找
	 */
	function commandSearch() {
		$post = I("post.");
		$code = $post['command_code'];
		$where['dc_code|dc_class|dc_module|dc_info'] = array("like", "%" . trim($code) . "%");
		$all = $this -> command 
                            -> table("iot_device_command as dc") 
                            -> join("iot_device_module as dm on dm.module_id=dc.mod_id") 
                            -> where($where) 
                            -> select();
		$count = $this -> command -> where($where) -> count();
		$this -> assign(array("all" => $all, "count" => $count));
		$this -> display("commandList");

	}
	/*命令添加*/
	function commandAdd() {
            if (IS_POST) {
                $param = I("param.");
                $param['cmd_code'] = trim($param['code']);
                $param['cmd_re'] = trim($param['re']);
                $param['cmd_cate'] = $param['cate'];
                $param['cmd_info'] = trim($param['info']);
                $item = $this->command
                             ->where(array("is_delete"=>0,"cmd_code"=>$param['cmd_code']))
                             ->find();
                if($item){
                    $this->ajaxReturn("cf");
                }
                $item1 = $this->command
                             ->where(array("is_delete"=>0,"cmd_re"=>$param['cmd_re']))
                             ->find();
               if($item1){
                    $this->ajaxReturn("cf");
               }
                
                
                $this -> command -> create($param);
                $return = $this -> command -> add();
                if($return){
                     $this -> ajaxReturn("添加成功");
                }else{
                     $this -> ajaxReturn("添加失败");
                }
            } else {
                $this -> display();
            }
	}
	/*命令修改*/
	function commandEdit() {
            if (IS_POST) {
                $param = I("param.");
                $param['cmd_code'] = trim($param['code']);
                $param['cmd_info'] = trim($param['info']);
                  $param['cmd_re'] = trim($param['re']);
                $param['cmd_cate'] = $param['cate'];
                $eid = I("eid");
                $where = array("is_delete"=>0,"cmd_code"=>$param['cmd_code']);
                $where['cmd_id'] = array("neq",$eid);
                $item = $this->command->where($where)->find();
                if($item){
                    $this->ajaxReturn("cf");
                }
                $map = array("is_delete"=>0,"cmd_re"=>$param['cmd_re']);
                $map['cmd_id'] = array("neq",$eid);
                 $item1 = $this->command->where($map)->find();
                if($item1){
                    $this->ajaxReturn("cf");
                }
                $this -> command -> create($param);
                $return = $this -> command -> where(array("cmd_id" => $eid)) -> save();
                if($return || $return == 0){
                    $this -> ajaxReturn("修改成功");
                }else{
                    $this -> ajaxReturn("修改失败");
                }
               
            } else {
                $id = I("id");
                $only = $this -> command -> where(array("cmd_id" => $id)) -> find();
                $this -> assign(array( "only" => $only));
                $this -> display();
            }
	}
	/*命令删除（虚拟删除）*/
	function commandDel() {
            $id = I("id");
            $return = $this -> command 
                          -> where(array("cmd_id" => $id)) 
                          -> save(array("is_delete"=>1));
            if($return){
                $this -> ajaxReturn("删除成功");
            }else{
                $this -> ajaxReturn("删除失败");
            }
            
	}

}
