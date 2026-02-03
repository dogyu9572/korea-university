/**
 * 교육·자격증·온라인 상세 페이지 공통 스크립트
 */
(function () {
	'use strict';

	function init() {
		$(window).on('load resize', function () {
			var absoH = $('.application_view .abso_info').outerHeight() || 0;
			var headerH = $('.header').outerHeight() || 0;
			$('#end').css('bottom', absoH + headerH + 'px');
		}).trigger('resize');

		$(window).on('scroll resize', function () {
			var scrollTop = $(window).scrollTop();
			var startTop = $('#start').offset().top;
			var endTop = $('#end').offset().top;
			var $view = $('.application_view');
			if (scrollTop >= endTop) {
				$view.addClass('end').removeClass('start');
			} else if (scrollTop >= startTop) {
				$view.addClass('start').removeClass('end');
			} else {
				$view.removeClass('start end');
			}
		}).trigger('scroll');

		$(window).on('scroll resize', function () {
			var scrollTop = $(window).scrollTop();
			var headerH = $('.header').outerHeight() || 0;
			var wrapTop = $('.scroll_wrap').offset().top;
			var tabsH = $('.scroll_wrap .scroll_tabs').outerHeight() || 0;
			var 기준값 = scrollTop + headerH + tabsH + 10;
			if (scrollTop + headerH >= wrapTop) {
				$('.scroll_wrap').addClass('fixed');
			} else {
				$('.scroll_wrap').removeClass('fixed');
			}
			var activeIdx = -1;
			$('.view_cont .cont').each(function (i) {
				if (기준값 >= $(this).offset().top) {
					activeIdx = i;
				}
			});
			if (activeIdx > -1) {
				var $tabs = $('.scroll_wrap .scroll_tabs li');
				var $active = $tabs.removeClass('on').eq(activeIdx).addClass('on');
				scrollTabIntoView($active);
			}
		}).trigger('scroll');

		$('.scroll_wrap .scroll_tabs li button').on('click', function (e) {
			e.preventDefault();
			var $li = $(this).closest('li');
			var idx = $li.index();
			var targetTop = $('.view_cont .cont').eq(idx).offset().top;
			var headerH = $('.header').outerHeight() || 0;
			var tabsH = $('.scroll_wrap .scroll_tabs').outerHeight() || 0;
			$('html, body').stop().animate({ scrollTop: targetTop - headerH - tabsH }, 500);
			scrollTabIntoView($li);
		});

		$('.container').addClass('mo_fixed_btm');
	}

	function scrollTabIntoView($tab) {
		var $container = $('.scroll_wrap .scroll_tabs');
		if (!$container.length) return;
		var containerLeft = $container.scrollLeft();
		var containerWidth = $container.outerWidth();
		var tabLeft = $tab.position().left + containerLeft;
		var tabWidth = $tab.outerWidth();
		if (tabLeft < containerLeft) {
			$container.stop().animate({ scrollLeft: tabLeft - 20 }, 300);
		} else if (tabLeft + tabWidth > containerLeft + containerWidth) {
			$container.stop().animate({ scrollLeft: tabLeft + tabWidth - containerWidth + 20 }, 300);
		}
	}

	$(function () {
		if ($('.application_view').length) {
			init();
		}
	});
})();
