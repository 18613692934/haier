<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class IndexController extends HomeBaseController {
    private $user;
    private $device;
    private $user_id;
    private $pest;
    /*超级管理员的标记值*/
    private $sex;
    public function _initialize(){
        parent::_initialize();
        $this->user_id = cookie('user');
        
        $this->device = D("Device");
        $this->user = D('user');
        $userData = $this->user->where(array("user_id"=>$this->user_id))->find();
        /*给予超级管理员一个标记，当标记为all时，判断为超级管理员，显示所有的设备情况*/
        $this->sex = $userData['sex'];
        $this->pest = D("Pest_data");
    }
    /**
     * 判断是否为超级管理员
     * @return array  
     */
    function is_admin(){
        if($this->sex != "all"){
            $where = array(
            "uid"=>$this->user_id,
            );
        }
        return $where;
    }
    public function index(){
       $where =  $this->is_admin();
       $where['is_delete'] = 0;
       
        $deviceData = $this->device->where($where)->order('device_code')->select();
        
        foreach($deviceData as $key=>$val){
            $pest = D("pest_data");
            $env = D("env");
            $env_where['device_code'] = $val['device_code'];
            $env_maxDate = $env->where($where)->where($env_where)->max('unix_createdate');


            $maxDate = $pest->where(array("device_code"=>$val['device_code'],"imgstatus"=>2))->max('unix_createdate');            

            $deviceData[$key]['maxDate'] = date('Y-m-d H:i:s', $maxDate);
            $deviceData[$key]['env_maxDate'] = date('Y-m-d H:i:s', $env_maxDate);
        }
            $this->assign(array(
                "deviceData"=>$deviceData
            ));
                
        $this->display();
    }
    
    
    
    function map(){
        $where = $this->is_admin();
        $data = $this->device
                    ->table("iot_device as d")
                    ->join("iot_region as r on d.re_id=r.region_id")
                    ->where($where)
                    ->select();
             foreach($data as $key=>$val){
                $pest = D("pest_data");
                $pest_sum = D("pest_data")
                        ->table("iot_pest_data as pd")
                        ->join("iot_pest_number as pn on pd.pd_id=pn.pd_id")
                            ->where(array("device_code"=>$val['device_code']))
                        ->field("sum(pn_number) as sum")
                        ->find();
                $env = D("env");
                $env_where['device_code'] = $val['device_code'];
                $env_maxDate = $env->where($where)->where($env_where)->max('unix_createdate');
                
                    if(!$pest_sum['sum']){
                        $pest_sum['sum'] = 0;
                    }
                $data[$key]['sum'] = $pest_sum['sum'];
                $count = $pest->where(array("device_code"=>$val['device_code'],"imgstatus"=>4))->count();
                $maxDate = $pest->where(array("device_code"=>$val['device_code'],"imgstatus"=>4))->max('unix_createdate');            
                $data[$key]['count'] = $count;
                $data[$key]['maxDate'] = date('Y-m-d H:i:s', $maxDate);
                
                $data[$key]['env_maxDate'] = date('Y-m-d H:i:s', $env_maxDate);
                }

            $this->ajaxReturn($data);

        
    }


   
}
