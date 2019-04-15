<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class PestsController extends AdminBaseController {
    
    private $pest;
    private $disease;
    private $type;
    
    /**
     * 初始化方法
     */
    public function _initialize() {
        parent::_initialize();
        $this->pest = D('Pest_data');
        $this->disease = D('Disease');
        $this->type = D('Pest_type');
        

    }
    
    
/***************************************************病虫害类型相关&***************************************/    

/**
 * 病虫害类型列表
 */
function typeList(){
    $all = $this->type->select();
        $count = $this->type->count();
    $this->assign(array("all"=>$all,"count"=>$count));
        $this->display();
}

/**
 * 病虫害类型添加
 * 
 */
function typeAdd(){
    if(IS_POST){
        $param = I("param.");
        $this->type->create($param);
        $num = $this->type->add();
        $this->ajaxReturn($num);
    }else{
        $this->display();
    } 
}

/**
 * 病虫害类型编辑
 */
function typeEdit(){
    if(IS_POST){
        $post = I("post.");
        $eid = I("eid");
        $pt = D("Pest_type");
        $post['modify_time'] = time();
        $pt->create($post);
        $bool = $pt->where(array("pt_id"=>$eid))->save();
        $this->ajaxReturn($bool);
    }else{
        $id = I("id"); 
        $only = $this->type->where(array("pt_id"=>$id))->find();
        $this->assign(array("only"=>$only));
        $this->display();
    }
    
}

/**
 * 病虫害类型删除
 */
function typeDel(){
    $id = I("id");
    $bool = $this->type->where(array("pt_id"=>$id))->delete();
    $this->ajaxReturn($bool);
}
    
    
    
/****************************************虫害的相关管理*******************************************/
/**
 * 虫害信息列表
 */
function pestList(){
    $param = I("param.");
    if($param){
        /*搜索框传递的值-开始*/
        $type = $param['type'];
        $region = $param['region'];
        $device_code = $param['device_code'];
        $startTime = strtotime($param['startTime']);
        $endTime = strtotime($param['endTime']);
        
        /*搜索框传递的值-结束*/
        


        /*datatables插件获取的值-开始*/
        $draw = $param['draw'];
        $start = $param['start'];
        $length = $param['length'];
        $orderColumn = $param["order"][0]["column"];
        $orderDir = $param["order"][0]["dir"];
        $data = $param["columns"][$orderColumn]["data"];
        $searchValue = $param['search']['value'];
        /*datatables插件获取的值-结束*/
        
        /*对搜索框传递过来的值进行判断*/
        if(!empty($startTime)&&!empty($endTime)){
            $where['addtime'] = array("BETWEEN",array($startTime,$endTime));
        }

        if(""!=$region){
            $where['pd.re_id'] = $region;
        }
        if(""!=$device_code){
            $where['pd.device_code'] = $device_code;
        }
        if($data=="device_code"){
            $order = array("pd.".$data." ".$orderDir."");
        }else{
            $order = array("".$data." ".$orderDir."");
        }
        if(!empty($searchValue)){
            $where['pd.device_code|rnname|deaddress|pest_type|pest_num|temp|humidity|wind_direction|wind_speed|lum|addtime'] = array("like","%$searchValue%");
        }
            
           
            
        
        /*联表查询获得害虫、区域、环境、设备数据*/
        $result  = $this->pest
                    ->table("iot_Pest_data as pd")
//                  ->join("iot_env as e on pd.env_id=e.env_id")
                    ->join("iot_region as r on r.region_id=pd.re_id")
                    ->join("iot_device as de on de.device_code=pd.device_code")
                    ->order($order)
                    ->where($where)
                    ->limit($start,$length)
                    ->select();
        /*获得条件查询以后的数据条数*/
        $recordsFiltered = $this->pest
                        ->table("iot_Pest_data as pd")
//                      ->join("iot_env as e on pd.env_id=e.env_id")
                        ->join("iot_region as r on r.region_id=pd.re_id")
                        ->join("iot_device as de on de.device_code=pd.device_code")
                        ->where($where)
                        ->count();
        $recordsTotal = $this->pest
                        ->table("iot_Pest_data as pd")
//                      ->join("iot_env as e on pd.env_id=e.env_id")
                        ->join("iot_region as r on r.region_id=pd.re_id")
                        ->join("iot_device as de on de.device_code=pd.device_code")->count();  //所有的数据条数
        //后台将时间戳转换为Y-m-d H:i:s日期
        foreach ($result as $key => $value) {
            $result[$key]['addtime'] = date('Y-m-d H:i:s', $result[$key]['addtime']);
        } 
        $arr = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $result,
        );
        $this->ajaxReturn($arr);
    }else{
        /*页面展示-下拉菜单赋值-页面fuzhi*/
        $type = $this->type->select();
        $device = D("Device")->select();
        $region = D("Region")->select();
        $count = $this->pest
                        ->table("iot_Pest_data as pd")
//                      ->join("iot_env as e on pd.env_id=e.env_id")
                        ->join("iot_region as r on r.region_id=pd.re_id")
                        ->join("iot_device as de on de.device_code=pd.device_code")->count();
        $this->assign(array("type"=>$type,"device"=>$device,"region"=>$region,"count"=>$count));
        $this->display();
    }
}

    
/**
 * 虫害校验
 */
function pestCheck(){
    $pest = D("Pest_data");
    if(IS_POST){
        $param = I("param.");
        $id = $param['img'];
        $status = $param['status'];
        $save = array(
            "baie_num" =>$param['baie'],
            "cch_num" =>$param['cch'],
            "yxz_num" =>$param['yxz'],
            "wde_num" =>$param['wde'],
            "clj_num" =>$param['clj'],
            "imgstatus"=>$status,
        );
        $bool = $pest->where(array("pd_id"=>$id))
                ->save($save);
        $this->ajaxReturn($bool);

    }else{
        $where['image'] = array("NEQ"," ");
        $where['imgstatus'] = array("egt",2);
        $count=$this->pest->where($where)->count();
        if($count>5){
            $rand=mt_rand(0,$count-5); //产生随机数。
            $limit=$rand.','.'5'; 
        }else{
            $limit = "0,5";
        }
        
        $all  =$pest->where($where)->limit($limit)->select();
        $this->assign(array("all"=>$all));
        $this->display();
    }

}
    
    
/**
 * 虫害查找
 */
function pestFind(){
    $id = I("id");
    $pest = D("Pest_data");
    $only = $pest->table("iot_pest_data as pd")
                 ->join("iot_region as r on r.region_id=pd.re_id")
                 ->join("iot_device as de on de.device_code=pd.device_code","left")
                 ->where(array("pd_id"=>$id))
                 ->find();
  
    $only['addtime'] = date("Y-m-d H:i:s",$only['addtime']);
    $this->ajaxReturn($only);
}
    

    /**
     * 暂时不使用（）;
     */
    function showList(){
        
        $result  = $this->pest
                ->select();
        
        $this->assign('result',$result);
       
        $this->display();

    }
    
    /**
     * 人工校验
     */
    function manualCheck(){
        
       
       
        $this->display();

    }
    
    
    
    
    /**************************************病害的相关管理***************************************/
/**
 * 病害列表
 */
function diseaseList(){
    $param = I("param.");
    if($param){
        /*datatables插件获得的数据*/
        $draw = $param['draw'];
        $start = $param['start'];
        $length = $param['length'];
        
         /*搜索框传递的值-开始*/
        $sType = $param['sType'];
        $bType = $param['bType'];
        $region = $param['region'];
        $level = $param['level'];
        $startTime = strtotime($param['startTime']);
        $endTime = strtotime($param['endTime']);
        /*搜索框传递的值-结束*/
        
        /*判断搜索框传递的值是否为空*/
        if(!empty($startTime)&&!empty($endTime)){
            $where['addtime'] = array("BETWEEN",array($startTime,$endTime));
        }
        if(""!=$sType){
            $where['stype'] = $sType;
        }
        if(""!=$bType){
            $where['dse_type'] = $bType;
        }
        if(""!=$level){
            $where['dse_level'] = $level;
        }
        if(""!=$region){
            $where['d.re_id'] = $region;
        }
        /*联表查询获取区域、环境和病害的信息*/
        $result  = $this->disease
                    ->table("iot_disease as d")
                    ->join("iot_region as r on r.region_id=d.re_id")
                    ->join("iot_device as de on de.device_code=d.device_code")
//                    ->join("iot_env as e on e.env_id=d.env_id")
                    ->order("de_id asc")
                    ->where($where)
                    ->limit($start,$length)
                    ->select();
        /*后台循环将时间戳转换为Y-m-d H:i:s格式*/
        foreach ($result as $key => $value) {
            $result[$key]['addtime'] = date('Y-m-d H:i:s', $result[$key]['addtime']);
        } 
        /*获得datatables插件需要的返回参数*/
        $recordsTotal = $this->disease
                    ->table("iot_disease as d")
                    ->join("iot_region as r on r.region_id=d.re_id")
                    ->join("iot_device as de on de.device_code=d.device_code")
//                    ->join("iot_env as e on e.env_id=d.env_id")
                    ->count();
        $recordsFiltered =$this->disease
                    ->table("iot_disease as d")
                    ->join("iot_region as r on r.region_id=d.re_id")
                    ->join("iot_device as de on de.device_code=d.device_code")
//                    ->join("iot_env as e on e.env_id=d.env_id")
                    ->where($where)
                    ->count();;
        /*返回datatables需要的各种参数信息*/
        $arr = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $result,
        );
        $this->ajaxReturn($arr);
    }else{
        $count = $this->disease
                    ->table("iot_disease as d")
                    ->join("iot_region as r on r.region_id=d.re_id")
                    ->join("iot_device as de on de.device_code=d.device_code")
//                    ->join("iot_env as e on e.env_id=d.env_id")
                    ->count();
        $region = D("Region")->field("regino_id","rnname")->select();
        $dse_type = $disease = D("Disease")->where(array("dse_type"=>array("neq","")))->group("dse_type")->select();
        $sType = $disease = D("Disease")->where(array("stype"=>array("neq","")))->group("stype")->select();
        $level = $disease = D("Disease")->where(array("dse_level"=>array("neq","")))->group("dse_level")->select();

        $this->assign(array(
            "count"=>$count,
            "region"=>$region,
            "bType"=>$dse_type,
            "sType"=>$sType,
            "level"=>$level,
                ));
        $this->display();
    }


}
     
    
/**
 * 病害校验
 */
function diseaseCheck(){
    $dse = D("Disease");
    if(IS_POST){
        $param = I("param.");
        $id = $param['img'];
        $bType = $param['bType'];
        $level = $param['level'];
        $sType = $param['sType'];
        $status = $param['status'];
        $bool = $dse->where(array("de_id"=>$id))
                ->save(array("dse_type"=>$bType,"stype"=>$sType,"dse_level"=>$level,"imgstatus"=>$status));
        $this->ajaxReturn($bool);

    }else{
        $where['image'] = array("NEQ"," ");
        $where['imgstatus'] = array("egt",2);
        $count=$this->disease->where($where)->count();
        if($count>5){
            $rand=mt_rand(0,$count-5); //产生随机数。
            $limit=$rand.','.'5'; 
        }else{
            $limit = "0,5";
            
        }
        $all  =$dse->where($where)->limit($limit)->select();
        $this->assign(array("all"=>$all));
        $this->display();
    }
}
    
/**
 * 病害查找
 */
function diseaseFind(){
    $id = I("id");
    $dse = D("Disease");
    $only = $dse->table("iot_disease as d")
                ->join("iot_region as r on r.region_id=d.re_id")
                ->join("iot_device as de on de.device_code=d.device_code")
                ->where(array("de_id"=>$id))->find();
    $only['addtime'] = date("Y-m-d H:i:s",$only['addtime']);
    $this->ajaxReturn($only);
}
    
    
}

