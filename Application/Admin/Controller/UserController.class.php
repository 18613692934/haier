<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class UserController extends AdminBaseController {
    private $user;
    
    private $admin_id;
    
    private $region;
    public function _initialize() {
        parent::_initialize();
        $this->user = D("User");
        $this->admin_id = cookie("admin");
        $this->region =  D("Region");
    }
    
    /*会员列表*/
    function userList(){
        $limit = I("limit");
        if(!$limit){
            $limit = 20;
        }
        $where = array("is_delete"=>0);
       
    $count = $this->user->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->user->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('all',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
        
    
    }
    
    
    /*删除的会员列表*/
    function delList(){
        $limit = I("limit");
        if(!$limit){
            $limit = 20;
        }
        $where = array("is_delete"=>1);
       
    $count = $this->user->where($where)->count();

        $Page=new \Org\Bjy\Page($count,$limit);
        $list=$this->user->where($where)->order('unix_create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
   
    $show       = $Page->show();// 分页显示输出
        $this->assign('all',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("count",$count);
    $this->display();
        
    }
    
    /*会员个人展示 */
    function userShow(){
        $id = I('id');
        $only = $this->user->where(array("user_id"=> $id,"u.is_delete"=>0))
                ->table("iot_user as u")
                ->join("iot_region as r on u.region_id=r.region_id","left")
                ->find();
    
        $this->assign(array("only"=>$only));
        $this->display();
    }
    
    /*添加会员*/
    function userAdd(){
        $admin_id = $this->admin_id;
        $post = I('post.');
        if($post){
            $where = array("uname"=>$post['uname']);
            $item = $this->user->where($where)->find();
           if($item){
               $this->ajaxReturn("cf");
           }
            $post['uname'] = trim($post['uname']);
            $post['password'] = trim($post['password']);
            $post['unit_name'] = trim($post['unit_name']);
            $post['name'] = trim($post['name']);
            $post['phone'] = trim($post['phone']);
            $post['email'] = trim($post['email']);
            $post['address'] = trim($post['address']);
            $post['remark'] = trim($post['remark']);
                $post['password'] = md5(md5(md5(md5(md5($post['password'])))));
                $post['unix_create_time'] = time();
                $post['create_id'] = $admin_id;
                $this->user->create($post);
                $id = $this->user->add();
                if($id){
                   $this->ajaxReturn("添加成功"); 
                }else{
                    $this->ajaxReturn("添加失败");
                }
        } else{  
            $reg = $this->region->where(array("is_delete"=>0))->select();  
            $this->assign(array("reg"=>$reg));
            $this->display();
        }

    }
    
    
    /*会员修改*/
    function userEdit(){
        $eid = I("eid");
        if(IS_POST){
                $post = I("post.");
            $post['uname'] = trim($post['uname']);
            $where = array("is_delete"=>0,"uname"=>$post['uname']);
        $where['user_id'] = array("neq",$eid);
            $item = $this->user->where($where)->find();
      
            if($item){
                $this->ajaxReturn("cf");
            }
            if($post['pwd']){
                $post['password'] = md5(md5(md5(md5(md5(trim($post['pwd']))))));
            } 
            $post['unit_name'] = trim($post['unit_name']);
            $post['name'] = trim($post['name']);
            $post['phone'] = trim($post['phone']);
            $post['email'] = trim($post['email']);
            $post['address'] = trim($post['address']);
            $post['remark'] = trim($post['remark']); 
            $post['unix_modify_time'] = time();
                $this->user->create($post);
            $bool = $this->user
                    ->where(array("user_id"=>$eid))
                    ->save();
            if($bool){
                  $this->ajaxReturn("修改成功");
            }else{
                  $this->ajaxReturn("修改失败");
            }
          
        }else{
            $id = I("id");
            $only = $this->user
                         ->where(array("user_id"=>$id,"is_delete"=>0))
                         ->find();
            $reg = $this->region
                    ->where(array("is_delete"=>0))
                    ->select();  
            $this->assign(array("only"=>$only,"reg"=>$reg));
            $this->display();
        }
    }
    
    /**
     *会员停用
   
    function userStop(){
        $id = I("id");
        $num = $this->user->where(array("user_id"=> $id))
             ->save(array("status"=>2));
        $this->ajaxReturn($num);
    }  */
     
    /**
     * 会员启用
    
    function userStart(){
        $id = I("id");
        $num = $this->user->where(array("user_id"=>$id))
             ->save(array("status"=>1));
        $this->ajaxReturn($num);
    } */

    /**
     * 修改密码
     
    function changePassword(){
        $cid = I("cid");
        $post = I("post.");
        //提交修改密码信息
        if($cid){
            $password = $post['password'];
            $newpassword = $post['newpassword'];
            $data = $this->user->where(array("user_id"=>$cid))
                 ->find();
           if($data['password'] == md5(md5(md5(md5(md5($post['password'])))))){
                $data['password'] = md5(md5(md5(md5(md5($post['password'])))));
                $this->user->create($data);
                $num = $this->user->where(array("user_id"=>$cid))
                            ->save();
                $this->ajaxReturn($num);
           } else{
                $this->ajaxReturn(false);
           }
        }else{
            //展示信息
            $id = I("id");
            $only = $this->user->where(array("user_id"=>$id))
                 ->find();
            $this->assign(array("only"=>$only));
            $this->display();
        }
        
    }*/
    
    
    /*会员删除*/
    function userDel(){
        $wid = I("wid");
        if($wid){
            //物理删除
            $bool = $this->user
                         ->where(array("user_id"=>$wid))
                         ->delete();
            if($bool){
                $this->ajaxReturn("删除成功");
            }else{
                  $this->ajaxReturn("删除失败");
            }
            
        }else{
            //虚拟删除
            $did =  I("did");
            $bool = $this->user
                         ->where(array("user_id"=>$did))
                         ->save(array("is_delete"=>1));
            if($bool){
                $this->ajaxReturn("删除成功");
            }else{
                  $this->ajaxReturn("删除失败");
            }
        }    
    }
    /*会员还原*/
    function userRestore(){
        $rid = I("rid");
        $bool = $this->user->where(array("user_id"=>$rid))
                     ->save(array("is_delete"=>0));
        if($bool){
            $this->ajaxReturn("还原成功");
        }else{
           $this->ajaxReturn("还原失败"); 
        }
        
    }
}

