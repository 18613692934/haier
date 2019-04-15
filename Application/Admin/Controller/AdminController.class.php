<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class AdminController extends AdminBaseController {
    protected $admin;
    private  $admin_id;
    function _initialize() {
       parent::is_login();
        parent::_initialize();
       $this->admin = D("admin");
       $this->admin_id = cookie("admin");
    }
    /*管理员展示*/
    function adminShow(){
        $id = I("id");
        $map = array('is_delete' =>0,'admin_id'=>$id);
        $only = $this->admin->where($map)
                      ->find(); 
        $this->assign(array("only"=>$only));
        $this->display();
    }
    /*管理员列表*/
    function adminList(){
    $limit = I("limit");
    if(!$limit){
        $limit = 20;
    }
        $where = array("is_delete"=>0);
       
    $count = $this->admin->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
    $list=$this->admin
            ->field("admin_id,login_name,mobile,email,a.status,unix_create_time,ag.title")
            ->alias("a")
            ->join("iot_auth_group_access as aga on a.admin_id = aga.uid","left")
            ->join("iot_auth_group as ag on ag.id = aga.group_id","left")
            ->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    
    // 获取第一条数据
		$first=$list[0];
		$first['title']=array();
		$user_data[$first['admin_id']]=$first;
                
		// 组合数组
		foreach ($list as $k => $v) {
			foreach ($user_data as $m => $n) {
				$uids=array_map(function($a){return $a['admin_id'];}, $user_data);
				if (!in_array($v['admin_id'], $uids)) {
					$v['title']=array();
					$user_data[$v['admin_id']]=$v;
				}
			}
		}
               
		// 组合管理员title数组
		foreach ($user_data as $k => $v) {
			foreach ($list as $m => $n) {
				if ($n['admin_id']==$k) {
					$user_data[$k]['title'][]=$n['title'];
				}
			}
			$user_data[$k]['title']=implode('、', $user_data[$k]['title']);
		}
    $show  = $Page->show();// 分页显示输出
        $this->assign('list',$user_data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
        
    }
    
    
    /*删除的管理员列表*/
    function delList(){
        
        $limit = I("limit");
        if(!$limit){
            $limit = 20;
        }
        $where = array("is_delete"=>1);
       
    $count = $this->admin->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->admin->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('delList',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
    }
    /**
 * 批量删除管理员
 *
function dataDel(){
    $arr = I("arr");
    $flg = TRUE;
    foreach ($arr as $val) {
        $bool = $this->admin->where(array("admin_id"=>$val))->delete();
        if(!$bool){
            $flg = FALSE;
        }
    }
    $this->ajaxReturn($flg);
    
}*/
    /**
 * 批量还原管理员
 
function dataRes(){
    $arr = I("arr");
    $flg = TRUE;
    foreach ($arr as $val) {
        $bool = $this->admin
                    ->where(array("admin_id"=>$val))
                     ->save(array("is_delete"=>0));
        if(!$bool){
            $flg = FALSE;
        }
    }
    $this->ajaxReturn($flg);
}*/

/**还原管理员*/
function adminRestore(){
    $id = I("id");

    $restore = $this->admin->where(array("admin_id"=>$id))
                     ->save(array("is_delete"=>0));
    if($restore){
        $this->ajaxReturn("还原成功");
    }else{
        $this->ajaxReturn("还原失败");
    }
    
}
    
    /*管理员添加*/
    function adminAdd(){
    
    //判断是否是post提交   
    if(IS_POST){
        $param = I('param.');
      
        $result = $this->admin->where(array("login_name"=>$param['login_name']))->find();
        
        if($result){
            $this->ajaxReturn("cf");
        }else{
            $admin_id = $this->admin_id;
            
            
            $param['login_name']  = trim($param['login_name']);
            $param['nick_name']  = trim($param['nick_name']);
            $param['password']  = md5(trim($param['password']));
            $param['name']  = trim($param['name']);
            $param['mobile']  = trim($param['mobile']);
            $param['email']  = trim($param['email']);
            $param['remark']  = trim($param['remark']); 
            $param['unix_create_time'] = time();
            $param['create_id'] = $admin_id;
            $param['status'] = 1;
            $this->admin->create($param);
            $num = $this->admin->add();
            
            if($num){
                foreach($param['groups'] as $k => $v){
                    $data = array(
                        "uid"=>$num,
                        "group_id"=> $v,
                    );
                    D("auth_group_access")->create($data);
                    $id = D("auth_group_access")->add();
                    if(!$id){
                        $this->ajaxReturn("添加失败");
                    }
                }
                $this->ajaxReturn("添加成功");
            }else{
                $this->ajaxReturn("添加失败");
            }
             //返回前台$result
            
        }
    } else {
        $result = D("auth_group")->where("status=1")->select();
        $this->assign("result",$result);
        $this->display();
    }
}
    /*管理员登录名验证*/
    function adminVerify(){
        $login_name = I("login_name");
        $where= array(
            "login_name"=>$login_name,
            "is_delete" => 0,
            ); 
        $result = $this->admin->where($where)->find();
        $this->ajaxReturn($result);
    }


    /*管理员编辑*/
    function adminEdit(){ 
        if(IS_POST){
            $eid = I("eid");
            $param = I("param.");
            $param['login_name']  = trim($param['login_name']);
            $param['nick_name']  = trim($param['nick_name']);
            $param['name']  = trim($param['name']);
            $param['mobile']  = trim($param['mobile']);
            $param['email']  = trim($param['email']);
            $param['remark']  = trim($param['remark']);
            
            $param['unix_modify_time'] = time();
            $where = array(
                "is_delete" => 0,
                "login_name" =>$param['login_name'] ,
                );
            $where['admin_id'] = array("neq",$eid);
            $item = $this->admin
                    ->where($where)
                    ->find();
            if($item){
                $this->ajaxReturn("cf");
            }
            $this->admin->create($param); 
            $num = $this->admin
                    ->where(array("admin_id"=>$eid,"is_delete"=>0))
                    ->save();
            if($num){
                $bool = D("auth_group_access")->where(array("uid"=>$eid))->delete();
                 $access = D("auth_group_access");
                foreach ($param['groups'] as $key => $value) {
                    $data = array(
                        "uid"=>$eid,
                        "group_id"=>$value,
                    );
                    $id = $access->add($data);
                    if(!$id){
                        $this->ajaxReturn("修改失败");
                    }
                }
                
                
                 $this->ajaxReturn("修改成功");
            }else{
                  $this->ajaxReturn("修改失败");
            }
        } else{
            $arrs = array();
            $id = I("id");
            $group_ids = D("auth_group_access")->field("group_id")->where("uid=%d",$id)->select();
            foreach ($group_ids as $k=>$v){
                foreach ($v as $ke => $val) {
                    $arrs[$k] = $val;
                }
            }

            $group_id_str = implode(',', $arrs);


            $arr = $this->admin->where(array("admin_id"=>$id,"is_delete"=>0))->find();
            $result = D("auth_group")->where("status=1")->select();
     
            $this->assign("result",$result);
            $this->assign(array("arr"=>$arr));
             $this->assign(array("ids"=>$arrs));
            $this->display();
        }
    }
    
    /*管理员删除*/
    function adminDel(){
    $id = I("id");
   $bool = $this->admin->where("admin_id=$id")->delete();
        if($bool){
            $boolean = D("auth_group_access")->where("uid=%d",$id)->delete();
            if($boolean){
                $this->ajaxReturn("删除成功");
            }else{
                $this->ajaxReturn("删除失败");
            }
        }else{
            $this->ajaxReturn("删除失败");
        }
}
    /*管理员删除(物理删除)*/
    public function delete(){
        $did = I("did");
        $bool = $this->admin->where("admin_id=$did")->delete();
        if($bool){
            $this->ajaxReturn("删除成功");
        }else{
            $this->ajaxReturn("删除失败");
        }
    }
/**
 * 管理员停用（完成）
 
function adminStop(){
    $id = I("id");
    $bool = $this->admin->where(array("admin_id" => $id))->save(array("status"=>0));
    $this->ajaxReturn($bool);   
}*/
    
    
/**
 * 管理员启用（完成）

function adminStart(){
    $id = I("id");
    $bool = $this->admin->where(array("admin_id" => $id))->save(array("status"=>1));
    $this->ajaxReturn($bool);  
} */
    
/**
 * 修改密码
 */
//function changePassword(){
//    $admin_id = $this->admin_id;
//    $cid = I("cid");
//    $param = I("param.");
//    if($cid){
//        $password = trim($param['password']);
//        $newpassword = trim($param['newpassword']);
//        $data = $this->admin->where(array("admin_id"=>$cid))
//                      ->find();
//       if($data['password'] == md5($password)){
//            $data['password'] = md5($newpassword);
//            $this->admin->create($data);
//            $num = $this->admin->where(array("admin_id"=>$cid))
//                        ->save();
//            if($num){
//                $this->ajaxReturn("修改成功");
//            }else{
//                $this->ajaxReturn("修改失败");
//            }
//       } else{
//            $this->ajaxReturn(false);
//       }
//    }else{
//        $only = $this->admin
//                     ->where(array("admin_id"=>$admin_id,"is_delete"=>0))
//                     ->find();
//        if($only){
//            $this->assign(array("only"=>$only));  
//            $this->display();
//        }else{
//            $this->redirect("Login/login");
//        }
//        
//    }
//}

}

