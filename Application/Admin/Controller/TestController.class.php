<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class TestController extends BaseController {
    function index(){
        $this->display();
    }
    
    function informList(){
        //p(I("param."));
        //p(I('draw'));
       $start  = I('start');
       $length = I('length');
        $model = D('Pest');
        p($length);
        $list = $model->limit($start, $length)->select();
        p($list);
        $this->display();
    }
	
	
	function test(){
		
	}
    
   function select(){
        $city = D("User_node");
        if(IS_AJAX){
            $id = I("id");
            $name = I("name");
            $level = 0;
            if("province"==$name){
                $level = 1;
            }
            if("city"==$name){
                $level = 2;
            }
            if("county"==$name){
                $level = 3;
            }
            $data = $city->where(array("cpid"=>$id,"clevel"=>$level+1))
                         ->select();
            $this->ajaxReturn($data);

       }else{
           
            $province = $city->where(array("cpid"=>0))->select();
            $this->assign(array("province"=>$province));
            $this->display();
       }
       
   }
   
   function pddata(){
       $this->display();
   }
   
   
   function slide(){
       $pest = D("Pest");
       $where['img'] = array("NEQ"," ");
       $all  =$pest->where($where)->limit(0, 5)->where(array("imgstatus"=>2))->select();
       $this->assign(array("all"=>$all));
       $this->display();
   }
   
   function slidenew(){
       $param = I("param.");
       p($param);
       $this->display();
   }
   
   function pestSlide(){
       $this->display();
   }
    
    
    
    
    
    
    
    
    function news(){
        $data = array(
                    'title'      => I('title'),
                    'short'      => I('short'),
                    'abstract'   => I('abstract'),
                    'sort'       => I('sort'),
                    'keywords'   => I('keywords'),
                    'abstract'   => I('abstract'),
                    'author'     => I('author'),
                    'source'     => (I('sources')),
                    'content'    => html_entity_decode(I('editorValue')),
                    'status'     => I('status'),

                    );
            $arr = array(
                        'create_time' => time(),
                        'create_id' => $cookie['admin_id'],
                    );
                    $result = $data+$arr;
                    $cookie = cookie("admin");
                    $id =$cookie['admin_id'];
                    
                    p($id);
              
}





function demo(){
    $env = D("Env");
    $all = $env->table("iot_env as e")
        ->join("iot_device as d on e.device_code=d.device_code","left")
        ->join("iot_region as r on d.re_id = r.region_id ","left")
        ->select();
    p($all);
    $this->display();
}

}

