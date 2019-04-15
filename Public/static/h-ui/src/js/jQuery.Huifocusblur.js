/* =======================================================================
 * jQuery.Huifocusblur.js 得到失去焦点
 * ========================================================================*/
!function($) {
	$.Huifocusblur = function(obj) {
		$(obj).focus(function() {
			$(this).addClass("focus").removeClass("inputError");
		});
		$(obj).blur(function() {
			$(this).removeClass("focus");
		});
	}
} (window.jQuery);
$(function() {
	/*****表单*****/
	$.Huifocusblur(".input-text,.textarea");
});