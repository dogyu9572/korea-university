$(document).ready(function(){
//헤더
	$(window).scroll(function() {
		if ($(window).scrollTop() > 100) {
			$(".header").addClass("fixed");
		} else {
			$(".header").removeClass("fixed");
		}
	});
	$(".btn_menu").click(function(){
		$("html,body").stop(false,true).toggleClass("over_h");
		$(".header").stop(false,true).toggleClass("on");
		$(".sitemap").stop(false,true).fadeToggle("fast");
	});
	//mobile
	$(".header .sitemap .menu button").click(function(){
		$(this).next(".snb").stop(false,true).slideToggle("fast").parent().stop(false,true).toggleClass("open").siblings().removeClass("open").removeClass("on").children(".snb").slideUp("fast");
	});
//footer
	var speed = 500; // 스크롤속도
	$(".gotop").css("cursor", "pointer").click(function(){
		$('body, html').animate({scrollTop:0}, speed);
	});

	$(window).on("scroll resize", function () {
		let winTop = $(window).scrollTop();
		let winH = $(window).height();
		let windowBottom = winTop + winH;

		let footerTop = $(".footer").offset().top;
		let isMobile = $(window).width() <= 1023;

		if (isMobile) {
			$(".container").addClass("mo_fixed_btm");

			// btns 높이만큼 기준을 더 아래로
			let btnsH = $(".application_view .abso_info .btns").outerHeight() || 0;
			footerTop += btnsH;
		} else {
			$(".container").removeClass("mo_fixed_btm");
		}

		if (windowBottom >= footerTop) {
			$(".footer").addClass("unfixed");
		} else {
			$(".footer").removeClass("unfixed");
		}
	});


	$(".footer .family_site dt button").click(function(event){
		$(this).parent().next("dd").stop(false,true).slideToggle("fast").parent().stop(false,true).toggleClass("on").siblings().removeClass("on").children("dd").slideUp("fast");
		event.stopPropagation(); // 이벤트 전파를 막음
	});
	$(document).click(function(event){
		if(!$(event.target).closest('.footer .family_site').length) {
			$(".footer .family_site").removeClass("on").children("dd").slideUp("fast");
		}
	});
//aside
	function asideToggle() {
		if ($(window).width() <= 767) {
			// 767 이하: 아코디언 기능 활성화
			$(".aside dt").off("click").on("click", function(event) {
				$(this).next("dd").stop(false,true).slideToggle("fast")
					.parent().stop(false,true).toggleClass("on")
					.siblings().removeClass("on").children("dd").slideUp("fast");
				event.stopPropagation();
			});

			$(document).off("click.aside").on("click.aside", function(event) {
				if (!$(event.target).closest('.aside dl').length) {
					$(".aside dl").removeClass("on").children("dd").slideUp("fast");
				}
			});

		} else {
			// 768 이상: 아코디언 기능 해제
			$(".aside dt").off("click");
			$(document).off("click.aside");
			$(".aside dl").removeClass("on").children("dd").removeAttr("style");
		}
	}

	// 페이지 로드 시 실행
	asideToggle();

	// 브라우저 크기 변경 시 자동 실행
	$(window).on("resize", function() {
		asideToggle();
	});

//브라우저 사이즈
	let vh = window.innerHeight * 0.01; 
	document.documentElement.style.setProperty('--vh', `${vh}px`);
//화면 리사이즈시 변경 
	window.addEventListener('resize', () => {
		let vh = window.innerHeight * 0.01; 
		document.documentElement.style.setProperty('--vh', `${vh}px`);
	});
	window.addEventListener('touchend', () => {
		let vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', `${vh}px`);
	});
});