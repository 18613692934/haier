<?php
return array(
	//'配置项'=>'配置值'
//    'SHOW_PAGE_TRACE' => true,
    'LOAD_EXT_CONFIG' => 'webconfig,db', 
     'DOMAIN' => 'http://120.224.74.50',

    
    
    
    'TMPL_PARSE_STRING'      => array(    // 定义常用路径
    	
        '__PUBLIC__'         => __ROOT__.'/Public',
        '__PUBLIC_STATIC__'  => __ROOT__.'/Public/static',
        '__PUBLIC_CSS__'     => __ROOT__.'/Public/css',
        '__PUBLIC_JS__'      => __ROOT__.'/Public/js',
        '__PUBLIC_IMAGES__'  => __ROOT__.'/Public/images',
        '__HOME__'           => __ROOT__.trim(TMPL_PATH,'.').'Home/Public',
        '__HOME_CSS__'       => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/css',
        '__HOME_JS__'        => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/js',
         '__HOME_ICON__'        => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/icon',
        '__HOME_IMG__'    => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/img',
        '__ADMIN_CSS__'      => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/css',
        '__ADMIN_JS__'       => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/js',
        '__ADMIN_IMAGES__'   => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/images',
        '__ADMIN_ACEADMIN__' => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/aceadmin',
        '__TPL_PUBLIC_CSS__'     => __ROOT__.trim(TMPL_PATH,'.').'Public/css',
        '__TPL_PUBLIC_JS__'      => __ROOT__.trim(TMPL_PATH,'.').'Public/js',
        '__TPL_PUBLIC_IMAGES__'  => __ROOT__.trim(TMPL_PATH,'.').'Public/images',
        '__USER_CSS__'       => __ROOT__.trim(TMPL_PATH,'.').'User/Public/css',
        '__USER_JS__'        => __ROOT__.trim(TMPL_PATH,'.').'User/Public/js',
        '__USER_IMAGES__'    => __ROOT__.trim(TMPL_PATH,'.').'User/Public/images',
        '__APP_CSS__'        => __ROOT__.trim(TMPL_PATH,'.').'App/Public/css',
        '__APP_JS__'         => __ROOT__.trim(TMPL_PATH,'.').'App/Public/js',
        '__APP_IMAGES__'     => __ROOT__.trim(TMPL_PATH,'.').'App/Public/images',
        '__UPLOAD_IMAGES__'  => __ROOT__.'/Upload/images',
    ),
    
    
    //*************************************附加设置***********************************
    'TMPL_ACTION_ERROR'      => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'    => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
//    'TMPL_EXCEPTION_FILE' => __ROOT__.trim(TMPL_PATH,'.').'TplError/Error/error500.html',
    'ERROR_PAGE'            =>  '/Tpl/Error/Error/error500.html',	// 错误定向页面
    
    //**************************************auth************************************//
    'AUTH_CONFIG' => array(
  'AUTH_ON' => true, //认证开关
  'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
  'AUTH_GROUP' => 'iot_auth_group', //用户组数据表名
  'AUTH_GROUP_ACCESS' => 'iot_auth_group_access', //用户组明细表
  'AUTH_RULE' => 'iot_auth_rule', //权限规则表
  'AUTH_USER' => 'iot_admin'//用户信息表
 )
);