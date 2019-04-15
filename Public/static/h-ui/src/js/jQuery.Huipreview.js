/* =======================================================================
 * jQuery.Huipreview.js 图片预览
 * ========================================================================*/
!function($) {
	$.Huipreview = function(obj) {
		/*图片预览*/
		$(obj).hover(function() {
			$(this).addClass("active");
			$("#tooltip-preview").remove();
			var winW = $(window).width();
			var winW5 = winW / 2;
			this.myTitle = this.title;
			this.title = "";
			var midimg = $(this).attr('data-preview');
			if (midimg == '') {
				return false;
			} else {
				var imgT = $(this).parents(".imgItem").offset().top;
				var imgL = $(this).parents(".imgItem").offset().left;
				var imgW = $(this).parents(".imgItem").width();
				var imgH = $(this).parents(".imgItem").height();
				var ww = (imgL + imgW / 2);
				if (ww < winW5) {
					var tooltipLeft = (imgW + imgL) + "px";
				} else {
					var tooltipRight = (winW - imgL) + "px";
				}
				var tooltip_keleyi_com = "<div id='tooltip-preview' style='top:" + imgT + "px;right:" + tooltipRight + ";left:" + tooltipLeft + "'><span id='tooltip-keleyi-div' class='loading' style='width:50px; height:50px'></span></div>";
				$("body").append(tooltip_keleyi_com);
				var midimgW = $(this).attr('data-width');
				var midimgH = $(this).attr('data-height');
				var imgTitle = this.myTitle ? "<br />" + this.myTitle + " 产品预览图": "";
				/*图片预加载*/
				var image = new Image();
				/*创建一个Image对象*/
				image.onload = function() {
					if ($('a.preview.active').attr('data-preview') == midimg) {
						var midingW2 = this.width;
						var midingH2 = this.height;
						$("#tooltip-keleyi-div").css({
							"width": midingW2 + "px",
							"height": midingH2 + "px"
						});
						$('#tooltip-keleyi-div').append(this);
					}
				};
				image.src = midimg;
			}
		},
		function() {
			$(this).removeClass("active");
			this.title = this.myTitle;
			$("#tooltip-preview").remove();
		});
	}
} (window.jQuery);