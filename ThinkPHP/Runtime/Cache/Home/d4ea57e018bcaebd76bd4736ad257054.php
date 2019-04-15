<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/common.css">
<link rel="stylesheet" type="text/css" href="/Tpl/Home/Public/css/index.css">
<!-- 首先引入jQuery和unslider -->
<script src="/Public/js/jquery-1.11.1.min.js"></script>
<script src="/Public/js/unslider.min.js"></script>
<title>林业物联网公共服务平台</title>
</head>
	
    <body style="background-color: #f6f8f6">
    
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
        <div class="container border-bottom" id="top_stop" style="position: relative; width: 1200px; height: 125px;">
            <div class="head-logo" >
                <a href="/">
                    <img style="height: 120px; width: 120px;" src="/Tpl/Home/Public/images/linye.png" alt="网站LOGO"></a>
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
                    <ul>
                        <li><a href="#" target="_blank">森林资源</a></li>
                        <li><a href="#" target="_blank">有害生物检测</a></li>
                        <li><a href="#" target="_blank">生产管理</a></li>
                        <li><a href="#" target="_blank">湿地保护</a>	</li>
                        <li><a href="#" target="_blank">森林火灾</a></li>
                        <li><a href="#" target="_blank">系统设置</a></li>
                    </ul>
                    <!--<a href="/index.php/Home/Inform/informList" target="">公示公告</a>-->
                    
                    <!--<a href="/chcontents/rwjx/index.shtml" target="_blank">环境监测</a>-->
                    
                    
                    		
                    		
                    			
                </div>
                <div class="cwa-nav-small"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</header>
    <div id=tu>
	<!-- 把要轮播的地方写上来 -->
		<div class="lbt">
			<div class="banner" id="b04">
			    <ul>
			        <li><a href="haha"><img src="/Tpl/Home/Public/images/lbt2.gif" alt="" width="100%" height="450" ></a></li>
			        <li><img src="/Tpl/Home/Public/images/lbt2.gif" alt="" width="100%" height="450" ></li>
			        <li><img src="/Tpl/Home/Public/images/lbt2.gif" alt="" width="100%" height="450" ></li>
			        <li><img src="/Tpl/Home/Public/images/lbt2.gif" alt="" width="100%" height="450" ></li>
			        <li><img src="/Tpl/Home/Public/images/lbt2.gif" alt="" width="100%" height="450" ></li>
			    </ul>
			    <a href="javascript:void(0);" class="unslider-arrow04 prev"><img class="arrow" id="al" src="/Tpl/Home/Public/images/arrowl.png" alt="prev" width="20" height="35"></a>
			    <a href="javascript:void(0);" class="unslider-arrow04 next"><img class="arrow" id="ar" src="/Tpl/Home/Public/images/arrowr.png" alt="next" width="20" height="37"></a>
			</div>
		</div>
		<!-- 轮播图最后用js控制 -->
				<script>
				$(document).ready(function(e) {
				    var unslider04 = $('#b04').unslider({
				        dots: true
				    }),
				    data04 = unslider04.data('unslider');
				     
				    $('.unslider-arrow04').click(function() {
				        var fn = this.className.split(' ')[1];
				        data04[fn]();
				    });	
				});
				</script>
		
		
	</div>
<!--	<div id=content>
		<div class=wrapper>
			<div id=condiv2>
				<p class=p1>服务与支持</p>
				<p class=p2><a href="#"><img src="/Tpl/Home/Public/images/dh4.png"/></a></p>
				<p class=p3>全面服务为客户提供保障</p>
			</div>
			<div id=condiv3>
				<p class=p1>公司产品</p>
				<p class=p2><a href="#"><img src="/Tpl/Home/Public/images/dh5.png"/></a></p>
				<p class=p3>管理系统系列产品</p>
			</div>
			<div id=condiv4>
				<p class=p1>方案与案例</p>
				<p class=p2><a href="#"><img src="/Tpl/Home/Public/images/dh6.png"/></a></p>
				<p class=p3>信息资源分析挖掘</p>
			</div>
			<div id=condiv5>
				<p class=p1>优质工程师</p>
				<p class=p2><a href="#"><img src="/Tpl/Home/Public/images/dh7.png"/></a></p>
				<p class=p3>具有实战经验的人员配置</p>
			</div>
		</div>
            
	</div>-->
        <div class="clear"></div>
        公告信息star
        <div class="news_box">
            <div class="news_column news_line">
                <h1>公示公告</h1>
                <div class="column">
                <?php if(is_array($info)): foreach($info as $key=>$info): ?><a style="color: #666;" href="<?php echo U('Inform/informContent',array('id'=>$info['inform_id']),'');?>" title="<?php echo ($info['inform_title']); ?>">
                    <div class="time_box">
                        <h3><?php echo (date("Y-m-d",$info['inform_pub_time'])); ?></h3>
                       
                        </div>
                    <div class="text_box">
                        <h3><?php echo (msubstr($info['inform_title'],0,20)); ?></h3>
                        </div>
                    <div class="clear"></div>
                    </a><?php endforeach; endif; ?>

                    

                </div>
        </div>

<div class="news_line"></div>


<div class="news_column">
    <h1>通知</h1>
    <?php if(is_array($result)): foreach($result as $key=>$re): ?><div class="column">
            <a style="color: #666;" href="<?php echo U('Inform/informContent',array('id'=>$re['inform_id']),'');?>" title="<?php echo ($re['inform_title']); ?>">
                <div class="time_box">
                    <h3><?php echo (date("Y-m-d ",$re['inform_pub_time'])); ?></h3>

                    </div>
                <div class="text_box">
                    <h3><?php echo (msubstr($re['inform_title'],0,20)); ?></h3>

                    </div>
                <div class="clear"></div>
            </a>
        </div><?php endforeach; endif; ?>
    
</div>
    <div class="zc_more"><a href="<?php echo U('Inform/informList');?>">更多信息</a></div>
</div>
        <!--公告信息end-->
        
        <!--运营服务star-->
    
        <div style="background-color:#d0d0d0;" class="sevice_box">
            <div class="sj_title"><h2>平台<span class="plm">服务</span></h2></div>
            <div class="sevice_img">
                <ul>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-area.png">森林资源</a></li>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-pest.png">有害生物监测</a></li>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-fire.png">森林火灾</a></li>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-sapling.png">生产管理</a></li>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-environ.png">湿地保护</a></li>
                    <li><a href="/service.html"><img src="/Tpl/Home/Public/images/iot-set.png">系统设置</a></li>
                    <div class="clear"></div>  
                </ul>
            </div>     
        </div>
     
        <!--运营服务end-->
        <div style="width:90%;height: 30px;"></div>
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