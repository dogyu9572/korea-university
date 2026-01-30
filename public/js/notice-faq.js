/**
 * 알림마당 FAQ 아코디언 (퍼블 원본 jQuery 그대로 사용)
 */
$(function () {
	$(".faq_wrap dt button").click(function(){
		$(this).parent().next("dd").stop(false,true).slideToggle("fast").parent().stop(false,true).toggleClass("on").siblings().removeClass("on").children("dd").slideUp("fast");
	});
});
