<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
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
<!--表格树形菜单的css-->
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
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" href="javascript:;" onclick="admin_role_add('添加角色','admin-role-add.html','800')"><i class="Hui-iconfont">&#xe600;</i> 添加角色</a> </span> <span class="r">共有数据：<strong>54</strong> 条</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="6">角色管理</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" value="" name=""></th>
				<th width="40">ID</th>
				<th width="200">角色名</th>
				<th>用户列表</th>
				<th width="300">描述</th>
				<th width="70">操作</th>
			</tr>
		</thead>
		<tbody >
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><tr class="text-c" id="c<?php echo ($li['role_id']); ?>">
                    <td><input type="checkbox" value="" name="checkbox"></td>
                    <td><?php echo ($li['role_id']); ?></td>
                    <td onclick="showhide_obj('c<?php echo ($li['role_id']); ?>','icon1')" class="c1"><span class="Hui-iconfont" >&#xe62d; <?php echo ($li['role_name']); ?></span><i id="icon1" class="Hui-iconfont" style="float: right">&#xe6d5;</i></td>
                    <td><a href="#">admin</a></td>
                    <td><?php echo ($li['role_describe']); ?></td>
                    <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                </tr>
                    <?php if(is_array($li['children'])): $i = 0; $__LIST__ = $li['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ch): $mod = ($i % 2 );++$i;?><tr class="text-c" id="c<?php echo ($ch['role_id']); ?>" parent="c<?php echo ($li['role_id']); ?>" style="display: none">
                            <td><input type="checkbox" value="" name="checkbox"></td>
                            <td><?php echo ($ch['role_id']); ?></td>
                            <td class="c2"><span><?php echo ($ch['role_name']); ?></span></td>
                            <td><a href="#">admin</a></td>
                            <td><?php echo ($ch['role_describe']); ?></td>
                            <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                <!--************************************************************************-->
         
                
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
/*管理员-角色-添加*/
function admin_role_add(title,url,w,h){
	layer_show(title,url,w,h);
}
/*管理员-角色-编辑*/
function admin_role_edit(title,url,id,w,h){
	layer_show(title,url,w,h);
}
/*管理员-角色-删除*/
function admin_role_del(obj,id){
	layer.confirm('角色删除须谨慎，确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: '',
			dataType: 'json',
			success: function(data){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
			},
			error:function(data) {
				console.log(data.msg);
			},
		});		
	});
}

/*表格树形菜单js代码*/
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
</html>