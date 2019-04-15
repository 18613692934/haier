/* =======================================================================
 * jQuery.Huihover.js 得到失去焦点
 * ========================================================================*/
!function($) {
	$.Huihover = function(obj) {
		$(obj).hover(function() {
			$(this).addClass("hover");
		},
		function() {
			$(this).removeClass("hover");
		});
	}
} (window.jQuery);