<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

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
        <style type="text/css">
            .clearfix:after{content:"\20";display:block;height:0;clear:both;visibility:hidden}.clearfix{zoom:1}
            .tabBar {border-bottom: 2px solid #222}
            .tabBar span { text-align: center;background-color: #e8e8e8;cursor: pointer;display: inline-block;float: left; width: 553px; font-weight: bold;height: 30px;line-height: 30px;padding: 0 15px}
            .tabBar span.current{background-color: #0044cc;color: #fff}
            .tabCon {display: none} 
        </style>
    </head>
    <body>
        <div id="tab_demo" class="HuiTab">
            <div class="tabBar clearfix"><span>虫害</span><span>病害</span></div>
            <div class="tabCon">
                <section class="zhang-box">
                    <div id="iframe_box" class="Hui-article">
                        <div class="show_iframe">
                            <div style="display:none" class="loading"></div>
                            <iframe scrolling="yes" frameborder="0" src="pestList.html"></iframe>
                        </div>
                    </div>
                </section>
            </div>
            <div class="tabCon">
                <section class="zhang-box">
                    <div id="iframe_box" class="Hui-article">
                        <div class="show_iframe">
                            <div style="display:none" class="loading"></div>
                            <iframe scrolling="yes" frameborder="0" src="diseaseList.html"></iframe>
                        </div>
                    </div>
                </section>
            </div>
            <div class="tabCon">内容三</div>
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
            $(function(){
            $.Huitab("#tab_demo .tabBar span","#tab_demo .tabCon","current","click","0")});
            // #tab_demo 父级id
            // #tab_demo .tabBar span 控制条
            // #tab_demo .tabCon 内容区
            // click 事件 点击切换，可以换成mousemove 移动鼠标切换
            // 1	默认第2个tab为当前状态（从0开始）
    </script>
    </body>
    
</html>