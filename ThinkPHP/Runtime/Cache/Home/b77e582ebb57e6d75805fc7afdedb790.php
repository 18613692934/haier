<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content= content="text/html;charset=utf-8"/>
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/common.css">
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/index.css">
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/listpage.css">
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/article.css">
</head>

<body>
  
        <link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/header.css"><!--引用外样式 -->
<header id="div_header">
    <div class="row bg_color_gray top-header">
        <div class="container" id="div_header_fixed">
            <div class="top-title">
                <a href="#">济宁市林业物联网公共服务平台</a>
            </div>
            <ul class="top-nav">
                <li>
                    <img class="pr10" src="/Tpl/Home/Public/images/cwa-map.png"><a href="/map.shtml" target="_blank">网站地图</a></li>
                <li>
                    <img class="pr10" src="/Tpl/Home/Public/images/cwa-homeico.png"><a onclick="SetHome(window.location)" href="javascript:void(0)">设为首页</a></li>
                <li class="border_none">
                    <img class="pr10" src="/Tpl/Home/Public/images/cwa-scico.png"><a onclick="AddFavorite(window.location,document.title)" href="javascript:void(0)">加入到收藏</a></li>
            </ul>
        </div>
    </div>
    <div class="row pull-left">
        <div class="container border-bottom" id="top_stop" style="position: relative; width: 1200px; height: 111px;">
            <div class="head-logo" style="padding-top: 30px;">
                <a href="/">
                    <img src="/images/cwa-logo.png" alt="网站LOGO"></a>
            </div>
            <div class="head-box" style="padding-top: 40px;">
                <div class="head-search">
                    <input name="word" id="word" class="search_text" maxlength="20" value="请输入查询关键字" onblur="if(this.value=='') this.value='请输入查询关键字';" onfocus="if(this.value=='请输入查询关键字') this.value='';" onkeydown=" return SearchKeyClick(event)" type="text"><input name="" class="search_button" onclick="checkw();" type="button">
                </div>
                <div id="userlogin" style="float: left;">
                    <h3><a href="/loginregister.shtml" target="_top">登录</a></h3>
                    <h3>/</h3>
                    <h3><a href="/loginregister.shtml?type=1" target="_top">注册</a></h3>
                </div>
                <div id="onlogin" style="float: left; display: none;">
                    <h3 id="loginusernumber"></h3>
                    <h3><a href="/usercenter/default.html" target="_blank">个人中心</a></h3>
                    <h3><a href="javascript:void(0);" target="_blank" onclick="loginout();">退出</a></h3>
                    <h3>/</h3>
                </div>

                
            </div>
        </div>
        <div class="row pull-left" id="div_memun">
            <div class="container">
                <div class="cwa-nav">
                    
				
				  	<a href="/index.php/Home/Inform/informList" target="">公示公告</a>
				   
				
				
				  	<a href="/chcontents/zx/index.shtml" target="_blank">种植面积</a>
				   
				
				
				  	<a href="/chcontents/rwjx/index.shtml" target="_blank">环境监测</a>
				   
				
				
				  	<a href="/chcontents/zhs/index.shtml" target="_blank">火灾预警</a>
				   
				
				
				  	<a href="/chcontents/jjfa/index.shtml" target="_blank">病虫害监测</a>
				   
				
				
				  	<a href="/chcontents/rmnygzh/index.shtml" target="_blank">苗情监测</a>
				   
				
				
				  	<a href="http://www.chinacn.me/" target="_blank">异常报警</a>
				   
				
				
				  	<a href="/chcontents/hd/index.shtml" target="_blank">专家服务</a>
				   
				
				
				  	<a href="/chcontents/yjzx/index.shtml" target="_blank">系统设置</a>
				   
				
				
				  	
				   
				
                </div>


                <div class="cwa-nav-small"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</header>
	<div id="kj_blank">
	</div>
	<div class="kj_tu">
		<img class="kj_tu" src="/Tpl/Home/Public/images/xwzx.jpg"/>
	</div>
	<div class="listPage">
		<div class="nav">
			<p class="nav_menu">
				<a class="nav_menu" href="javascript:">首页</a>
				<em class="em_color">></em>
				<a class="nav_menu"href="javascript:">公示公告</a>
				<em class="em_color">></em>
				<a class="nav_menu"href="javascript:">正文</a>
			</p>
		</div>
		<div class="article">
			<div class="lfcon">
				<div class="article_con">
					<h1><?php echo ($result['inform_title']); ?></h1>
					<h2><?php echo (date("Y-m-d H:i",$result['inform_pub_time'])); ?></h2>
					<div class="default">
					<?php echo ($result['inform_content']); ?>                       
					</div>
					<div class="more_article">
						<h3>
							上一篇: <a target="" href="http://www.huamu.com/zixun/dongtai/8059.html">吴世光：苗木产业如何实现稳步提升?</a> <span>2017-02-24 10:34</span>
						</h3>
						<h3>
							下一篇: <a target="" href="http://www.huamu.com/zixun/dongtai/8155.html">全国第一个保护地友好产品的标准在京发布</a> <span>2017-03-02 10:13</span>
						</h3>
					</div>
				</div>				
			</div>
			<div class="rtcon">
				<h2 class="xwbt"><a href="javascript:">最新</a></h2>
				<ul>
                                    <?php if(is_array($all)): foreach($all as $key=>$all): ?><li>
						<p>
							<span class="sw-mod-keywordHotTop-num1"><?php echo ++$i;?></span>
                                                        <a class="xwph" href="<?php echo U('Inform/informContent',array('id'=>$all['inform_id']),'');?>"><?php echo ($all['inform_title']); ?></a>
						</p>
					</li><?php endforeach; endif; ?>
					
				<ul>
			</div>
		</div>
	</div>
	<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/footer.css"><!--引用外样式 -->
<div class="row pull-left bg_gray_bold">
    <div class="clear"></div>
    <div class="container">
        
        <div class="footer">
            <div class="us">
                <img class="logo" src="/images/footer-logo.png">
                <ul class="footer_about">
                    
                    <li><a href="/chcontents/gywm/201571/54.shtml" target="_blank">关于我们</a></li>
                   	
                    <li><a href="/chcontents/gywm/201571/55.shtml" target="_blank">联系我们</a></li>
                   	
                    <li><a href="/chcontents/gywm/201571/57.shtml" target="_blank">加入我们</a></li>
                   	
                </ul>

            </div>
            <div class="friendship">
                <h3>
                    <a href="/chcontents/yqlj/index.shtml" target="_blank" style="color: #2facbd">友情链接</a>
                </h3>

                
<a href="#" target="_blank">林业部</a>



            </div>
            <div class="foot_wxwb">
                <ul>
                    
                        <li>
                            <a href="#" target="_blank">
                            <div class="weixin_pic">&nbsp;</div>
                            <div class="names">微信</div>
                            </a>
                        </li>
                    
                    
                        <li>
                            <a href="#" target="_blank">
                            <div class="sina_pic">&nbsp;</div>
                            <div class="names">新浪微博</div>
                            </a>
                        </li>
                    
                    
                        <li>
                            <a href="#" target="_blank">
                            <div class="tencent_pic">&nbsp;</div>
                            <div class="names">腾讯微博</div>
                            </a>
                        </li>
                    

                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="copyright bg_grayblack pull-left">
    版权所有&nbsp;&nbsp;&nbsp;&nbsp;京ICP备 15033181号
</div>
</body>
</html>