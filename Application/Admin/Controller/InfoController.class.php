<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class InfoController extends AdminBaseController {
    
    function _initialize() {
        parent::_initialize();
    }
    
    
    function index(){
        $xmlString = simplexml_load_file("Public/myInfo.xml", 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlString),true);
        p($val);
        
        $this->display();
    }
    
    
    function xmlToArray(){
        
    }
    
    
}

