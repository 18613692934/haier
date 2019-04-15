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
<title>通知列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 通知管理 <span class="c-gray en">&gt;</span> 通知列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c">
		<button onclick="removeIframe()" class="btn btn-primary radius">关闭选项卡</button>
	 <span class="select-box inline">
		<select name="" class="select">
			<option value="0">全部分类</option>
			<option value="1">分类一</option>
			<option value="2">分类二</option>
		</select>
                
<!--		</span> 日期范围：
                <input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="input-text Wdate" style="width:120px;">
		-
                <input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="input-text Wdate" style="width:120px;">-->
		<input type="text" name="" id="" placeholder=" 通知名称" style="width:250px" class="input-text">
		<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜通知</button>
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
            <span class="l">
                <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                <a class="btn btn-primary radius" data-title="添加通知" data-href="informAdd" onclick="inform_add('添加通知','informAdd')" href="javascript:;">
                    <i class="Hui-iconfont">&#xe600;</i> 添加通知</a>
                <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
            </span> 
            <span class="r">共有数据：<strong><?php echo ($list[0]['inform_num']); ?></strong> 条</span> 
        </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
                                        <th width="40" onclick="del()">排序</th>
					<th width="80">ID</th>
					<th>标题</th>
					<th width="80">分类</th>
					<th width="80">来源</th>
					<th width="120">更新时间</th>
					<th width="75">浏览次数</th>
					<th width="60">发布状态</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
                            <?php if(is_array($list)): foreach($list as $key=>$li): ?><tr class="text-c">
                                    <td><input class="checkbox" type="checkbox" value="<?php echo ($li['inform_id']); ?>" name="checkbox"></td>
                                    <td><?php echo ($li['inform_sort']); ?></td>
                                    <td><?php echo ($li['inform_id']); ?></td>
                                    <td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="inform_edit('查看','informZhang.html','10001')" title="查看"><?php echo ($li['inform_title']); ?></u></td>
                                    <td><?php echo ($li['']); ?></td>
                                    <td><?php echo ($li['inform_source']); ?></td>
                                    <td><?php echo ($li['inform_modeify_time']); ?></td>
                                    <td><?php echo ($li['inform_count']); ?></td>
                                    <td class="td-status"><span class="label label-<?php echo ($li['status_css']); ?> radius"><?php echo ($li['status_name']); ?></span></td>
                                    <td class="f-14 td-manage">
                                        <?php switch($li['inform_status']): case "2": ?><a onClick="inform_shenhe(this,'<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="审核">审核</a><?php break;?>
                                            <?php case "3": ?><a  onClick="inform_shenhe(this,'<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="审核">审核</i></a><?php break;?>
                                            <?php case "4": ?><a  onClick="inform_start(this,'<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="发布">发布</i></a><?php break;?>
                                            <?php case "5": ?><a  onClick="inform_stop(this,'<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="取消">取消</a><?php break; endswitch;?>
                                        <?php if($li['inform_status'] >= 1): ?><a style="text-decoration:none" class="ml-5" onClick="inform_edit('通知编辑','informEdit','<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
                                            <a style="text-decoration:none" class="ml-5" onClick="inform_del(this,'<?php echo ($li['inform_id']); ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
                                            
                                    </td>
                                </tr><?php endforeach; endif; ?>	
			</tbody>
		</table>
	</div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/Public/js/jquery-1.9.1.min.js"></script> 
<script type="text/javascript" src="/Public/static/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/Public/static/h-ui/js/H-ui.min.js"></script> 
<script type="text/javascript" src="/Public/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/Public/static/My97DatePicker/4.8/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/static/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="/Public/static/laypage/1.2/laypage.js"></script>
<script type="text/javascript">


$('.table-sort').dataTable({
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,8]}// 不参与排序的列
	]
});


/*批量删除（还未完成）*/
function datadel(){
	var objs= document.getElementsByClassName('checkbox');
        var list = new Array();
        for (var i = 0; i < objs.length; i++) {
            var value = objs[i].checked;
            if(value){
                list[i] = objs[i].value;
            }else{
                continue;
            }
        }  
            //原生ajax
    
        var xmlhttp;
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
            xmlhttp = new XMLHttpRequest();
        }else {
            // IE6, IE5 浏览器执行代码
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("post","<?php echo U(delete);?>",true);
        //添加HTTP头信息
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        if(list){
            xmlhttp.send("list="+list);
        }else {
            xmlhttp.send("list");
        } 
        //规定当服务器响应已做好被处理的准备，才触发任务
        xmlhttp.onreadystatechange=function()
        {   //alert(xmlhttp.readyState);
            //当readyState等于4且状态为200时，表示响应就绪
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var result = xmlhttp.responseText;
                alert(result);

            }else {
                
            }
        }
    
        
}

/*通知-添加（完成）*/
function inform_add(title,url,w,h){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}

/*通知-编辑（完成）*/
function inform_edit(title,url,id,w,h){
    var id = id;
	var index = layer.open({
            type: 2,
            title: title,
            content: url+"/id/"+id
	}); 
       
	layer.full(index);
}
/*通知-删除*/
function inform_del(obj,id){
    var id = id;
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: "<?php echo U('Inform/informDel');?>",
                        data: {
                            "id":id,
                        },
			dataType: 'json',
			success: function(data){
                           if(data){
                               $(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:2000});
                           }else{
                               layer.msg('删除失败!',{icon:1,time:2000});
                           }	
			},
			error:function(data) {
                                layer.msg('删除失败!',{icon:1,time:2000});
				console.log(data.msg);
			},
		});		
	});
}

/*通知-审核(完成)*/
function inform_shenhe(obj,id){
        var id = id;
	layer.confirm('审核文章？', {
		btn: ['通过','不通过','取消'], 
		shade: false,
		closeBtn: 0
	},
	function(){
                var bool= edit_status(id,4);
                if(bool){
                    $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="inform_start(this,'+id+')" href="javascript:;" title="发布">发布</a>');
                    $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">未发布</span>');
                    $(obj).remove();
                    layer.msg('审核通过，等待发布', {icon:6,time:2000});
                }else{     
                    layer.msg('审核失败，请重新操作！', {icon:6,time:2000});
                }       
	},
	function(){
                var bool = edit_status(id,3);
                if(bool){
                    $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="inform_shenhe(this,'+id+')" href="javascript:;" title="审核">审核</a>');
                    $(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
                    $(obj).remove();
                    layer.msg('未通过', {icon:5,time:2000});
                }else{
                     
                    layer.msg('审核失败，请重新操作！', {icon:5,time:2000});
                }
		
	});	
}

/*通知-取消发布(完成)*/
function inform_stop(obj,id){
     var id = id;
	layer.confirm('确认要取消吗？',function(index){
                var bool = edit_status(id,4);
                if(bool){
                    $(obj).parents("tr").find(".td-manage").prepend('<a onClick="inform_start(this,'+id+')" href="javascript:;" title="发布">发布</a>');
                    $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">未发布</span>');
                    $(obj).remove();
                    layer.msg('已取消!',{icon: 5,time:2000});
                }else{
                    layer.msg('取消失败，请重新操作！',{icon: 5,time:2000});
                }
		
	});
}

/*通知-发布(完成)*/
function inform_start(obj,id){
     var id = id;
	layer.confirm('确认要发布吗？',function(index){
                var bool = edit_status(id,5);
                if(bool){
                    
                    $(obj).parents("tr").find(".td-manage").prepend('<a onClick="inform_stop(this,'+id+')" href="javascript:;" title="取消">取消</a>');
                    $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
                    $(obj).remove();
                    layer.msg('已发布!',{icon: 6,time:2000});
                }else{
                    layer.msg('发布失败，请重新发去!',{icon: 6,time:2000});
                }
		
	});
}
/*通知-申请上线*/
function inform_shenqing(obj,id){
	$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">待审核</span>');
	$(obj).parents("tr").find(".td-manage").html("");
	layer.msg('已提交申请，耐心等待审核!', {icon: 1,time:2000});
}

/*修改文章状态的方法(完成)*/
function edit_status(id,status){
    var result;
    $.ajax({
        type: 'POST',
        url: "<?php echo U('Inform/editStatus');?>",
        data:{
            id:id,
            status:status,
        },
        async:false,
        dataType: 'json',
        success: function(data){
            result = data;
        },
        error:function(error) {
            window.console.info(data);
            result = error;
        },
    });
return result;
}

/*通知-草稿*/
function inform_caogao(){
    
}
</script> 
</body>
</html>