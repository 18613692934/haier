<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 生产环境管理
 */
class EnvController extends AdminBaseController {
    private $env;
    public function _initialize() {
        $this->env = D("Env");
        parent::_initialize();
    }   
    
    
/**
 * 生产环境数据展示
 */
function envList(){
    if(IS_POST){
       $param = I("param.");
       $draw =  $param['draw'];
       $start = $param['start'];  //分页开始位置
       $length = $param['length']; //分页页面显示长度
       $order  = $param['order']["0"]['column'];  //以第几个列进行排序
       $orderColumn = $param["columns"][$order]["data"];
       $orderDir   = $param["order"]["0"]["dir"];  //默认升序
       $address = $param['address'];
       $region = $param['region'];
       $code = $param['code'];
       $startTime = $param['startDate'];
       $endTime = $param['endDate'];
          //实例化对象
       if(""!=$region){
           $where['region_id'] = $region;
       }
       if(""!=$address){
           $where['deaddress'] = $address;
        }
        if(""!=$code){
            $where['device_id'] = $code;
        }
        if(!empty($startTime)&&!empty($endTime)){
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            $where['unix_addtime'] = array("BETWEEN",array($startTime,$endTime));
        }
       
                //数据查找并分页  排序
            $env = D("Env");
            $all = $this->env->table("iot_env as e")
                    ->join("iot_device as d on e.device_code=d.device_code")
                    ->join("iot_region as r on d.re_id = r.region_id ")
                    ->where($where)
                    ->limit($start, $length)
                    ->order("e.".$orderColumn." ".$orderDir."")
                    ->select();
            $filter = $this->env->table("iot_env as e")
                    ->join("iot_device as d on e.device_code=d.device_code")
                    ->join("iot_region as r on d.re_id = r.region_id ")
                    ->where($where)
                    ->count();
            $num = $this->env->table("iot_env as e")
                    ->join("iot_device as d on e.device_code=d.device_code")
                    ->join("iot_region as r on d.re_id = r.region_id ")->count();  
        $arr = array(
            'draw' => $draw,
            'recordsFiltered' => $filter,
            'recordsTotal' => $num ,
            'data' => $all,
        );
        $this->ajaxReturn($arr);

    }else{
        $address = D("Device")->group("deaddress")->select();
        $code = D("Device")->field("device_id,device_code")->select();
        $region = D("Region")->select();
        $count = $this->env->table("iot_env as e")
                    ->join("iot_device as d on e.device_code=d.device_code")
                    ->join("iot_region as r on d.re_id = r.region_id ")->count();
        $this->assign(array("code"=>$code,"region"=>$region,"count"=>$count,"address"=>$address,"all"=>$all));
        $this->display();
    }


}

}

