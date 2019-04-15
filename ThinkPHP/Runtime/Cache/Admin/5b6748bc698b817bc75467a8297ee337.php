<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>yahoo.com首页的tab选项卡切换效果 -懒人建站 - http://www.51xuediannao.com</title>
<style type="text/css">
<!--
* { margin:0; padding:0 }
a{color:#333;}
div, dl, dt, dd { display:inline-block; }
div, dl, dt, dd { display:block }
h2{ font: 800 12px/20px "宋体"; }
#tabs { border:1px solid #ccc; margin:40px; width:382px; padding:4px 0 }
#tabs div { padding:39px 4px 0px; position:relative; }
#tabs dt { text-align:center; font: 12px/30px "宋体"; }
#tabs dd { font: 12px/20px "宋体"; padding:10px; }
#tab1 dt,#tab4 dt,#tab7 dt,#tab10 dt,#tab13 dt { left: 4px; top: 4px }
#tab2 dt,#tab5 dt,#tab8 dt,#tab11 dt,#tab14 dt { left:130px; top:4px }
#tab3 dt,#tab6 dt,#tab9 dt,#tab12 dt,#tab15 dt { left:256px; top:4px }
#tab5 dt { left:130px; top:4px }
#tab6 dt { left:256px; top:4px }
.close dt { height:30px; width:120px; background:#FAFAFA; position:absolute; border:1px solid #ccc }
.close dd { display:none }
.open dt { height:35px; width:120px; background:#EBEBEB; position:absolute; border:1px solid #ccc; border-bottom:none; }
.open dd { background:#EBEBEB; border:1px solid #ccc; }
-->
</style>
<script type="text/javascript">
window.onload = 
function(){
alltabs = document.getElementById('tabs').getElementsByTagName('dl')
for(i = 0; i < alltabs.length; i++){
   alltabs[i].className = "close"
   alltabs[6].className = "open";
   alltabs[i].onmouseover = function(){
    for(j = 0; j < alltabs.length; j++){
     alltabs[j].className = "close"
    }
    this.className = "open"
   }
}
}
</script>
</head>
<body>
<div id="tabs">
<h2>·呵呵 可以实现了，比你给我看的代码要好的多。</h2>
<div>
<dl id="tab1">
   <dt>懒人建站</dt>
   <dd><a href="http://www.51xuediannao.com/">懒人建站</a></dd>
</dl>
<dl id="tab2">
   <dt>JS代码</dt>
   <dd><a href="http://www.51xuediannao.com/js/">JS代码</a></dd>
</dl>
<dl id="tab3">
   <dt>建站技巧</dt>
   <dd>3</dd>
</dl>
</div>
<div>
<dl id="tab4">
   <dt>51xuediannao.com</dt>
   <dd>4</dd>
</dl>
<dl id="tab5">
   <dt>CSS技巧</dt>
   <dd>5</dd>
</dl>
<dl id="tab6">
   <dt>51xuediannao.com</dt>
   <dd><a href="http://www.51xuediannao.com/">懒人建站</a></dd>
</dl>
</div>

<div>
<dl id="tab7">
   <dt>懒人建站</dt>
   <dd><a href="http://www.51xuediannao.com/">懒人建站</a></dd>
</dl>
<dl id="tab8">
   <dt>JS代码</dt>
   <dd>8</dd>
</dl>
<dl id="tab9">
   <dt>建站技巧</dt>
   <dd><a href="http://www.51xuediannao.com/jiqiao/">建站技巧</a></dd>
</dl>
</div>
<div>
<dl id="tab10">
   <dt>懒人建站</dt>
   <dd>10</dd>
</dl>
<dl id="tab11">
   <dt>JS代码</dt>
   <dd><a href="http://www.51xuediannao.com/js/">JS代码</a></dd>
</dl>
<dl id="tab12">
   <dt>建站技巧</dt>
   <dd>12</dd>
</dl>
</div>

</div>

<pre>
    说明：样式中的 font: 12px/30px "宋体";  是一个样式缩写：字号/行高 字体；
    注意.open dt .close dt #tabs div 和其他样式中的宽高，和间距设置，
    适当的控制可以打造出你想要的效果。
    JS代码中的 alltabs[6].className = "open"; 是用来控制初始状态的，6 就是第七个为初始状态
    如果把这一句删除，就是没有初始状态。
    经测试 IE6、IE7、IE8、火狐 均正常
</pre>
</body>
</html>