<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>

<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/js/html5shiv.js"></script>
<script type="text/javascript" src="/Public/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/Public/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/Public/static/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/Public/static/h-ui.admin/css/style.css" />
<link rel="stylesheet" type="text/css" href="tableTree.css"/>
<style type="text/css">
    .table .c1 {
        position: relative;
    }
    .table .c1 span {
        position: absolute;
        left: 60px;
    }
    .table .c2 {
        position: relative;
    }
    .table .c2 span {
        position: absolute;
        left: 75px;
        top: 10px;
    }
</style>
<!--[if IE 6]>
<script type="text/javascript" src="/Public/js/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>角色管理</title>
</head>

<body style="list-style:none">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
 
    
    
	<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" href="javascript:;" onclick="admin_role_add('添加角色','admin-role-add.html','800')"><i class="Hui-iconfont">&#xe600;</i> 添加角色</a> </span> <span class="r">共有数据：<strong>54</strong> 条</span> </div>
        
        
        <table class="table table-border table-bordered table-hover table-bg">
            <thead>
                <tr>
                    <th scope="col" colspan="6">角色管理</th>
                </tr>
                <tr class="text-c">           
                    <th width="25"><input type="checkbox" value="" name="checkbox"></th>
                    <th width="40">ID</th>  
                    <th width="200">角色名</th>
                    <th>用户列表</th>
                    <th width="300">描述</th>
                    <th width="70">操作</th>
                </tr>
            </thead>
            <tbody >
                <tr class="text-c" id="c1">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td onclick="showhide_obj('c1','icon1')" class="c1"><span class="Hui-iconfont" >&#xe62d; 后台角色</span><i id="icon1" class="Hui-iconfont" style="float: right">&#xe6d5;</i></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>
                <tr class="text-c" id="c2" parent="c1" style="display: none">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td class="c2"><span>|--超级管理员</span></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>
                <tr class="text-c" id="c3" parent="c1" style="display: none">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td onclick="" class="c2"><span>|--主编</span></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>    
                <!--************************************************************************-->
                <tr class="text-c" id="c4">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td onclick="showhide_obj('c4','icon2')" class="c1"><span class="Hui-iconfont" >&#xe60d; 用户角色</span><i id="icon2" class="Hui-iconfont" style="float: right">&#xe6d5;</i></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>
                <tr class="text-c" id="c5" parent="c4" style="display: none">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td class="c2"><span>|--市级用户</span></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>
                <tr class="text-c" id="c6" parent="c4" style="display: none">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td>1111</td>
                    <td onclick="" class="c2"><span>|--县级用户</span></td>
                    <td><a href="#">admin</a></td>
                    <td>33333</td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>    
                
                
                
            </  tbody>
	</table>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/Public/js/jquery-1.9.1.min.js"></script> 
<script type="text/javascript" src="/Public/static/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/Public/static/h-ui/js/H-ui.min.js"></script> 
<script type="text/javascript" src="/Public/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/Public/static/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  
    function showhide_obj(id,icon){
        icon = document.getElementById(icon);
        obj = document.getElementById(id);  
        tr_list = document.getElementsByTagName("tr");
         for (var i = 0; i < tr_list.length; i++) {
             thisTr = tr_list[i];
             if(!thisTr.id){
             continue;
             }
             parent = document.getElementById(thisTr.id).getAttribute("parent");
             if(parent){
                 thisParent = parent.valueOf();
                 if(thisParent === id){
                     if(thisTr.style.display === "none"){
                         thisTr.style.display = "";
                        icon.innerHTML = "&#xe6d6;";
                     }else{
                         thisTr.style.display = "none";
                        icon.innerHTML = "&#xe6d5;";
                     }
                     
                 }
             }   
         }    
    }   

</script>
</body>