<?php
namespace Error\Controller;
use Common\Controller\BaseController;
class ErrorController extends BaseController {

    public function _initialize(){
        
    }
    public function error404(){
        $this->display();
    }
    public function error500(){
        $this->diaplay();
    }
}
