/* =======================================================================
 * jquery.togglePassword.js 隐藏显示密码
 * type="password"
 * ========================================================================*/
!function($) {
	$.fn.togglePassword = function(options) {
		var s = $.extend($.fn.togglePassword.defaults, options),
		input = $(this);

		$(s.el).on(s.ev,
		function() {
			"password" == $(input).attr("type") ? $(input).attr("type", "text") : $(input).attr("type", "password");
		});
	};

	$.fn.togglePassword.defaults = {
		ev: "click"
	};
} (window.jQuery);