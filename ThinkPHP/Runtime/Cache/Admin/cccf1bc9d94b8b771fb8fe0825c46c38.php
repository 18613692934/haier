<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>类似网易排行榜tab选项卡特别版 -懒人建站 - http://www.51xuediannao.com</title>
<style>
HTML,BODY {
 margin:30px;padding:0;border:none;font-size:14px
}
.wrap {
 width:300px; BORDER: #b7c5d9 1px solid;BACKGROUND: #f0f6f9
}
.a10 {
 FONT-WEIGHT: bold; COLOR: #0099cc;  TEXT-DECORATION: none;
}
.a10:hover {
 COLOR: #bb2233; TEXT-DECORATION: underline
}
.md-head {
 PADDING-LEFT: 0px; LINE-HEIGHT: 26px; HEIGHT: 26px
}
.md-head A {
 BORDER-TOP-WIDTH: 0px; BORDER-LEFT-WIDTH: 1px; FLOAT: left; BORDER-BOTTOM-WIDTH: 1px; MARGIN-LEFT: -1px; WIDTH: 76px; BACKGROUND-COLOR: #fff; TEXT-ALIGN: center; BORDER-RIGHT-WIDTH: 0px
}
.md-head A.wid {
 WIDTH: 148px
}
.md-head A.cur {
  BORDER-BOTTOM-WIDTH: 0px; COLOR: #000; BACKGROUND-COLOR: #f0f6f9; TEXT-DECORATION: none
}
.md-body {
 padding:15px;ZOOM: 1
}
a,area { blr:e-xpression(this.onFocus=this.blur()) }
:focus { -moz-outline-style: none; }
</style>
</head>

<body>

<DIV class="wrap" id=divspacerank>
     <DIV class=md-head id=blogs_spacerank>
     <A class="a10 wrap cur wid" id=blogs_spacerank_tab_0 hideFocus onClick="blogs_spacerank_ck(0);return false;" href="javascript:;">今日空间排行</A>
     <A class="a10 wrap" id=blogs_spacerank_tab_1 hideFocus onClick="blogs_spacerank_ck(1);return false;" href="javascript:;">72小时</A>
     <A class="a10 wrap" id=blogs_spacerank_tab_2 hideFocus onClick="blogs_spacerank_ck(2);return false;" href="javascript:;">一周</A> <BR class=clear>
     </DIV>
     <DIV class=md-body>
          <DIV id=blogs_spacerank_0>
               今日空间排行内容
          </DIV>
          <DIV id=blogs_spacerank_1 style="DISPLAY: none">
                72小时空间排行内容
         </DIV>
         <DIV id=blogs_spacerank_2 style="DISPLAY: none">
                一周空间排行内容
         </DIV>
 </DIV>
</DIV>
<SCRIPT>
  var blogs_spacerank_index = 0,blogs_spacerank_class = 'a10 wrap ';
  var blogs_spacerank_list = document.getElementById('blogs_spacerank').getElementsByTagName('a');
  function blogs_spacerank_ck(_idx){
  blogs_spacerank_list[blogs_spacerank_index].className = blogs_spacerank_class;
  blogs_spacerank_list[_idx].className = blogs_spacerank_class + ' cur wid';
  document.getElementById('blogs_spacerank_'+blogs_spacerank_index).style.display = 'none';
  document.getElementById('blogs_spacerank_tab_'+blogs_spacerank_index).innerHTML = document.getElementById('blogs_spacerank_tab_'+blogs_spacerank_index).innerHTML.replace("空间排行","");
  document.getElementById('blogs_spacerank_'+_idx).style.display = 'block';
  document.getElementById('blogs_spacerank_tab_'+_idx).innerHTML = document.getElementById('blogs_spacerank_tab_'+_idx).innerHTML+"空间排行";
  blogs_spacerank_index = _idx;
  }             
</SCRIPT>



<!--下面只是说明与程序代码无关-->
<div style="width:95%; height:auto; display:block; margin:0 auto; margin-top:30px; font-size:10pt; line-height:150%;">
<span>本代码由<a href="http://www.51xuediannao.com" style="color:#F00;">懒人建站网 收集整理</a> </span><br>
<a href="http://www.51xuediannao.com">懒人建站 http://www.51xuediannao.com</a><br/>
<span>我们为您提供-HTML+CSS模板，HTML+CSS教程，JS广告代码，HTML+CSS导航菜单，FLASH焦点图片，国外网页设计欣赏与模板和CSS技巧。；</span>
<span>懒人建站只收录实用和能提高用户体验的代码</span>
<span>我们只想解放出你的部分写代码时间来思考更高层次的设计，而不是要你懒惰、拼凑。</span>
</div>
</body>
</html>