/* =======================================================================
 * jQuery.Huiselect.js 选择
 * ========================================================================*/
!function($) {
	$.Huiselect = function(divselectid, inputselectid) {
		var inputselect = $(inputselectid);
		$(divselectid + " cite").click(function() {
			var ul = $(divselectid + " ul");
			ul.slideToggle();
		});
		$(divselectid + " ul li a").click(function() {
			var txt = $(this).text();
			$(divselectid + " cite").html(txt);
			var value = $(this).attr("selectid");
			inputselect.val(value);
			$(divselectid + " ul").hide();
		});
		$(document).click(function() {
			$(divselectid + " ul").hide();
		});
	};
} (window.jQuery);