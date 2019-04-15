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
<!--[if IE 6]>
<script type="text/javascript" src="/Public/js/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title></title>
<style>

#preview{ 
    position: absolute;
    padding:5px;
    display:none;
    color:#fff;
   
}
#preview img{
     width: 300;
    height: 300px;
}
</style>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 列表展示 <span class="c-gray en">&gt;</span> 虫害列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <form id='myForm'>
        <div class="text-c">	
             <span class="select-box inline">
                <select name="type" class="select">
                        <option value="0">按种类</option>
                        <option value="1">美国白蛾</option>
                        <option value="2">春尺蠖</option>
                        <option value="2">杨小舟蛾</option>
                        <option value="2">舞毒蛾</option>
                        <option value="2">草履蚧</option>
                </select>
                 <select name="" class="select">
                        <option value="0">按区域</option>
                        <option value="1"></option>
                        <option value="2"></option>
                </select>
            </span> 日期范围：
                    <input type="text" name='statrTime' onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="input-text Wdate" style="width:120px;">
                    -
                    <input type="text" name='endTime' onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="input-text Wdate" style="width:120px;">
                    
                    <button name="" id="submit" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜虫害</button>
        </div>
    </form>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" data-title="添加虫害" data-href="article-add.html" onclick="Hui_admin_tab(this)" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加虫害</a></span> <span class="r">共有数据：<strong>54</strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
                                        <th width="80">设备编号</th>
					<th>区域</th>
					<th width="100">害虫种类</th>
					<th width="100">数量</th>
                                        <th>图片</th>
					<th width="120">添加时间</th>
                                        <th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
                        <?php if(is_array($result)): foreach($result as $key=>$for): ?><tr class="text-c">
                                    <?php echo ($result['pest_type']); ?>
					<td><input type="checkbox" value="" name=""></td>
					<td><?php echo ($for['pest_id']); ?></td>
					<td class="text-l"><?php echo ($for['pest_device_num']); ?></td>
					<td><?php echo ($for['pest_area']); ?></td>
					<td>
                                            <?php switch($for['pest_type']): case "1": ?>美国白蛾<?php break;?>
                                                <?php case "2": ?>春尺蠖<?php break;?>
                                                <?php case "3": ?>杨小舟蛾<?php break;?>
                                                <?php case "4": ?>舞毒蛾<?php break;?>
                                                <?php case "5": ?>草履蚧<?php break; endswitch;?>
                                        </td> 
					<td><?php echo ($for['pest_num']); ?></td>
                                        <td>
                                            <a href="javascript:" rel="/Upload/images/pest/20160915124953.jpg" class="preview"><img src="/Upload/images/pest/20160915124953.jpg" alt="画廊缩略图" width="40" height="40" /></a></li>            
                                        </td>
					<td><?php echo (date('Y-m-d H:i:s',$for['pest_add_time'])); ?></td>
                                        <td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit('角色编辑','admin-role-add.html','1')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                         
				</tr><?php endforeach; endif; ?>
				
			</tbody>
                        <tfoot>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
                                        <th width="80">设备编号</th>
					<th>区域</th>
					<th width="100">害虫种类</th>
					<th width="100">数量</th>
                                        <th>图片</th>
					<th width="120">添加时间</th>
                                        <th width="80">操作</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

    <!--_footer 作为公共模版分离出去-->
    <script type="text/javascript" src="/Public/js/jquery-1.11.1.min.js"></script> 
    <script type="text/javascript" src="/Public/static/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="/Public/static/h-ui/js/H-ui.min.js"></script> 
    <script type="text/javascript" src="/Public/static/h-ui.admin/js/H-ui.admin.js"></script>
    <!--/_footer 作为公共模版分离出去-->

    <!--请在下方写此页面业务相关的脚本-->  
    <script type="text/javascript" src="/Public/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/Public/static/My97DatePicker/4.8/WdatePicker.js"></script> 
    <script type="text/javascript" src="/Public/static/datatables/1.10.0/jquery.dataTables.min.js"></script> 
    <script type="text/javascript" src="/Public/static/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
    $('.table-sort').dataTable({
        serverSide: true,
        ajax:{
            url:"<?php echo U('Pests/pestList');?>",
            type:"post",
            success: function(data){
                alert(data);
            }
       }
    });
    
    
    
//$('.table-sort').dataTable({
//    "aaSorting": [[ 1, "asc" ]],//默认第几个排序
//    "bStateSave": true,//状态保存
//    "aoColumnDefs": [
//      //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
//    {"orderable":false,"aTargets":[0]}// 不参与排序的列
//    ]
//}); 

    
    
    
    
    
    this.imagePreview = function(){
	/* CONFIG */	
		xOffset = 250;
		yOffset = -350;	
	/* END CONFIG */
	$("a.preview").hover(function(e){
            //alert(e.pageY);
		this.t = this.title;
		this.title = "";	
		var c = (this.t != "") ? "<br/>" + this.t : "";
		$("body").append("<div id='preview'><img src='"+ this.rel +"' alt='Image preview' />"+ c +"</div>");								 
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#preview").remove();
    });	
	$("a.preview").mousemove(function(e){
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset)+ "px");
	});			
};
// starting the script on page load
$(document).ready(function(){
	imagePreview();
});

   

   
/*虫害-添加*/
function article_add(title,url,w,h){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*虫害-编辑*/
function article_edit(title,url,id,w,h){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*虫害-删除*/
function article_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
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
</script> 
</body>
</html>