/* =======================================================================
 * jQuery.Huitags.js 标签
 * ========================================================================*/
!function($) {
	/*tag标签*/
	var time1;
	$(".Hui-tags-lable").show();
	$(".Hui-tags-input").val("");
	$(document).on("blur", ".Hui-tags-input",function() {
		time1 = setTimeout(function() {
			$(this).parents(".Hui-tags").find(".Hui-tags-list").slideUp();
		},
		400);
	});
	$(document).on("focus", ".Hui-tags-input",function() {
		clearTimeout(time1);
	});
	$(document).on("click", ".Hui-tags-input",function() {
		$(this).find(".Hui-tags-input").focus();
		$(this).find(".Hui-tags-list").slideDown();
	});
	function gettagval(obj) {
		var str = "";
		var token = $(obj).parents(".Hui-tags").find(".Hui-tags-token");
		if (token.length < 1) {
			$(obj).parents(".Hui-tags").find(".Hui-tags-val").val("");
			return false;
		}
		for (var i = 0; i < token.length; i++) {
			str += token.eq(i).text() + ",";
		}
		str = unique(str, 1);
		str=str.join();
		$(obj).parents(".hui-tags").find(".tags-val").val(str);
	}
	$(document).on("keydown", ".Hui-tags-input",function(event) {
		$(this).next().hide();
		var $this = $(this).parents(".Hui-tags");
		var v = $(this).val().replace(/\s+/g, "");
		var reg = /^,|,$/gi;
		v = v.replace(reg, "");
		v = $.trim(v);
		var token = $this.find(".Hui-tags-token");
		if (v != '') {
			if (event.keyCode == 13 || event.keyCode == 108 || event.keyCode == 32) {
				$(this).change();
			}
		} else {
			if (event.keyCode == 8) {
				if (token.length >= 1) {
					$this.find(".Hui-tags-token:last").remove();
					gettagval(this);
				} else {
					$this.find(".Hui-tags-lable").show();
					return false;
				}

			}
		}
	});
	$(document).on("change",".Hui-tags-input",function(){
		$(this).next().hide();
		var $this = $(this).parents(".Hui-tags");
		var v1 = $(this).val();
		var v2 = $this.find(".Hui-tags-val").val();
		var v = v2+','+v1;
		if(v!=''){
			var str='<i class="Hui-tags-icon Hui-iconfont">&#xe64b;</i>';
			var result = unique(v, 1);
			if(result.length>0){
				for(var j=0;j<result.length;j++){
					str+='<span class="Hui-tags-token">'+result[j]+'</span>';
				}
				str+='<div class="Hui-tags-iptwrap"><input type="text" class="Hui-tags-input" maxlength="20" value=""><lable class="Hui-tags-label">添加关键词，用空格分隔</lable></div>';
				$this.find(".Hui-tags-icon,.hui-tags-token,.Hui-tags-input").remove();
				$this.find(".Hui-tags-editor").html(str);
				$this.find(".Hui-tags-iptwrap .Hui-tags-input").val("").focus();
				$(".Hui-tags").find(".Hui-tags-lable").hide();
				$this.find(".Hui-tags-val").val(result);
			}				
		}	
	});
	
	$(document).on("click",".Hui-tags-has span",function(){
		var taghasV = $(this).text();
		taghasV=taghasV.replace(/(^\s*)|(\s*$)/g,"");
		$('<span class="Hui-tags-token">'+taghasV+'</span>').insertBefore($(this).parents(".Hui-tags").find(".Hui-tags-iptwrap"));
		gettagval(this);
		$(this).parents(".Hui-tags").find(".Hui-tags-input").focus();
	});

	
	$(document).on("click",".Hui-tags-token",function(){
		var token =$(this).parents(".Hui-tags").find(".Hui-tags-token");
		var it = $(this).parents(".Hui-tags");
		$(this).remove();
		switch(token.length){
			case 1 : it.find(".Hui-tags-lable").show();
			break;
		}
		var str ="";
		var token =it.find(".Hui-tags-token");
		if(token.length<1){
			it.find(".Hui-tags-val").val("");
			return false;
		}else{
			for(var i = 0;i< token.length;i++){
				str += token.eq(i).text() + ",";
			}
			str = str.substring(0,str.length-1);
			it.find(".Hui-tags-val").val(str);
		}
	});
	
	/*将字符串逗号分割成数组并去重*/
	function unique(o, type){
		//去掉前后空格
		o=o.replace(/(^\s*)|(\s*$)/g,"");
		if(type == 1) {
			//把所有的空格和中文逗号替换成英文逗号
			o=o.replace(/(\s)|(，)/g, ",");
		} else {
			//把所有的中文逗号替换成英文逗号
			o=o.replace(/(，)/g, ",");
		}
		//去掉前后英文逗号
		o=o.replace(/^,|,$/g, "");
		//去重连续的英文逗号
		o=o.replace(/,+/g,',');
		o=o.split(",");
		var n = [o[0]]; //结果数组
		for(var i = 1; i < o.length; i++){
			if (o.indexOf(o[i]) == i) {
				if(o[i] == "")
					continue;
				n.push(o[i]);
			}
		}
		return n;
	}
	$(".Hui-tags-input").change();
} (window.jQuery);