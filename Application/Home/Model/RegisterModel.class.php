<?php
namespace Home\Model;
use Think\Model;

class RegisterModel extends Model{
  //开启批量验证
  protected $patchValidate = true;
  protected $_validate = array(
      array('uname','require','用户名不能为空'),
      array('password','require','密码不能为空'),
      array('repassword','require','第二次密码不能为空'),
      array('unit','require','请选择单位性质'),
  );
}
