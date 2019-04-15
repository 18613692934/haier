<?php
namespace Home\Model;
use Think\Model;

class LoginModel extends Model{
  //开启批量验证
  protected $patchValidate = true;
  protected $_validate = array(
    
      array('uname','require','用户名不能为空'),
      array('password','require','密码不能为空'),

  );
}
