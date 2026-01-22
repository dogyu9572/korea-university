@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<ul class="round_tabs slim">
		<li class="on"><a href="/about/history">연혁</a></li>
		<li><a href="/about/bylaws">정관</a></li>
	</ul>
	
	<div class="history_wrap">
		<div class="point" id="start"></div>
		<div class="point" id="end"></div>
		<ul class="year_tabs mo_vw">
			<li><button type="button">현재 ~ 2020년</button></li>
			<li><button type="button">2010년 ~ 2019년</button></li>
			<li><button type="button">2000년 ~ 2009년</button></li>
			<li><button type="button">1992년 ~ 1999년</button></li>
		</ul>
		<div class="years">
			<button type="button" class="on"><p>2020년</p><strong>현재</strong><img src="/images/history01.jpg" alt=""></button>
			<button type="button"><p>2010년</p><strong>2019년</strong><img src="/images/history02.jpg" alt=""></button>
			<button type="button"><p>2000년</p><strong>2009년</strong><img src="/images/history03.jpg" alt=""></button>
			<button type="button"><p>1992년</p><strong>1999년</strong><img src="/images/history04.jpg" alt=""></button>
		</div>
		<div class="daylist">
			@php
				// 10년 단위로 그룹화
				$groupedHistories = [];
				foreach ($histories as $history) {
					$year = $history->year;
					if ($year >= 2020) {
						$groupedHistories['2020-current'][] = $history;
					} elseif ($year >= 2010 && $year <= 2019) {
						$groupedHistories['2010-2019'][] = $history;
					} elseif ($year >= 2000 && $year <= 2009) {
						$groupedHistories['2000-2009'][] = $history;
					} elseif ($year >= 1992 && $year <= 1999) {
						$groupedHistories['1992-1999'][] = $history;
					}
				}
			@endphp
			
			{{-- 현재 ~ 2020년 --}}
			<div class="cont_area">
				@if(isset($groupedHistories['2020-current']))
					@foreach($groupedHistories['2020-current'] as $history)
						<div class="box"><i></i>
							<div class="day"><strong>{{ $history->date->format('Y.m.d') }}</strong><p>{{ $history->content }}</p></div>
						</div>
					@endforeach
				@endif
			</div>
			
			{{-- 2010년 ~ 2019년 --}}
			<div class="cont_area">
				@if(isset($groupedHistories['2010-2019']))
					@foreach($groupedHistories['2010-2019'] as $history)
						<div class="box"><i></i>
							<div class="day"><strong>{{ $history->date->format('Y.m.d') }}</strong><p>{{ $history->content }}</p></div>
						</div>
					@endforeach
				@endif
			</div>
			
			{{-- 2000년 ~ 2009년 --}}
			<div class="cont_area">
				@if(isset($groupedHistories['2000-2009']))
					@foreach($groupedHistories['2000-2009'] as $history)
						<div class="box"><i></i>
							<div class="day"><strong>{{ $history->date->format('Y.m.d') }}</strong><p>{{ $history->content }}</p></div>
						</div>
					@endforeach
				@endif
			</div>
			
			{{-- 1992년 ~ 1999년 --}}
			<div class="cont_area">
				@if(isset($groupedHistories['1992-1999']))
					@foreach($groupedHistories['1992-1999'] as $history)
						<div class="box"><i></i>
							<div class="day"><strong>{{ $history->date->format('Y.m.d') }}</strong><p>{{ $history->content }}</p></div>
						</div>
					@endforeach
				@endif
			</div>
			
			<div class="line"><div class="bar"></div></div>
		</div>
	</div>
	
</main>

<script>
$(function () {
	const $history = $(".history_wrap");
	const $yearsBtns = $history.find(".years button");
	const $boxes = $history.find(".daylist .box");
	if (!$history.length) return;
	// 연도 범위 추출
	function getYearRange($btn) {
		const startYear = parseInt($btn.find("p").text(), 10);
		const strongText = $btn.find("strong").text().trim();
		const endYear = strongText === "현재" ? 9999 : parseInt(strongText, 10);
		return { startYear, endYear };
	}
	// 버튼 기준 해당 구간 첫 box로 이동
	function moveToYear($btn, animate = true) {
		const headerH = $(".header").outerHeight() || 0;
		const { startYear, endYear } = getYearRange($btn);
		let $targetBox = null;
		$boxes.each(function () {
			const year = parseInt($(this).find(".day strong").text().substring(0, 4), 10);
			if (year >= startYear && year <= endYear) {
				$targetBox = $(this);
				return false;
			}
		});
		if ($targetBox) {
			const top = $targetBox.offset().top - headerH;
			if (animate) {
				$("html, body").stop().animate({ scrollTop: top }, 600);
			} else {
				$(window).scrollTop(top);
			}
		}
	}
	// 스크롤 위치 기준으로 버튼 상태 업데이트
	function updateYearsByScroll() {
		const scrollTop = $(window).scrollTop();
		const headerH = $(".header").outerHeight() || 0;
		let currentYear = null;
		$boxes.each(function () {
			const boxTop = $(this).offset().top - headerH;

			if (scrollTop >= (boxTop - 20)) {
				currentYear = parseInt($(this).find(".day strong").text().substring(0, 4), 10);
			}
		});
		if (!currentYear) return;
		$yearsBtns.each(function () {
			const $btn = $(this);
			const { startYear, endYear } = getYearRange($btn);

			if (currentYear >= startYear && currentYear <= endYear) {
				$btn.addClass("on").siblings().removeClass("on");
				updateBeforeAfterClass(); // ⭐ 추가
				return false;
			}
		});
	}
	// 버튼 클릭
	$yearsBtns.on("click", function () {
		const $btn = $(this);
		$btn.addClass("on").siblings().removeClass("on");
		moveToYear($btn, true);
		updateBeforeAfterClass();
	});
	function updateBeforeAfterClass() {
		const $btns = $(".history_wrap .years button");
		const onIndex = $btns.filter(".on").index();
		if (onIndex < 0) return;
		$btns.each(function (idx) {
			const $btn = $(this);
			const diff = Math.abs(idx - onIndex);
			$btn.removeClass("before_after01 before_after02 before_after03");
			if (diff > 0 && diff <= 3) {
				$btn.addClass("before_after0" + diff);
			}
		});
	}
	$(window).on("scroll resize", function () {
		updateYearsByScroll();
	}).trigger("scroll");
});

$(window).on("scroll resize", function () {
	const $history = $(".history_wrap");
	const $years = $history.find(".years");
	const headerH = $(".header").outerHeight() || 0;
	if (!$history.length) return;
	const scrollTop = $(window).scrollTop();
	const winH = $(window).height();
	const winBottom = scrollTop + winH;
	const historyTop = $history.offset().top;
	const historyHeight = $history.outerHeight();
	const yearsH = $years.outerHeight();
	const startPoint = historyTop - headerH;
	const endPoint = historyTop + historyHeight - yearsH;
	if (scrollTop >= startPoint && scrollTop < endPoint) {
		$history.addClass("start").removeClass("end");
	} else if (scrollTop >= endPoint) {
		$history.addClass("end").removeClass("start");
	} else {
		$history.removeClass("start end");
	}
}).trigger("scroll");

$(window).on("scroll resize", function () {
	const $daylist = $(".history_wrap .daylist");
	const $bar = $daylist.find(".line .bar");
	if (!$daylist.length) return;
	const winCenter = $(window).scrollTop() + ($(window).height() / 2);
	const listTop = $daylist.offset().top;
	const listHeight = $daylist.outerHeight();
	let barHeight = winCenter - listTop;
	barHeight = Math.max(0, Math.min(barHeight, listHeight));
	$bar.css("height", barHeight);
}).trigger("scroll");

//탭(ul) onoff
$('.history_wrap > .year_tabs > li:first-child').addClass('on');
$('.history_wrap .years button:first-child').addClass('mo_on');
$('.history_wrap .daylist .cont_area:first-child').addClass('on');
$('.history_wrap').on('click', '.year_tabs > li > button', function() {
    var index = $(this).parent().index();
    $(this).parent().siblings().removeClass('on');
    $(this).parent().addClass('on');
    $(this).closest('.history_wrap').find('.years > button').removeClass('mo_on').eq(index).addClass('mo_on');
    $(this).closest('.history_wrap').find('.daylist .cont_area').removeClass('on').eq(index).addClass('on');
});
function scrollTabIntoView($tab) {
	const $container = $(".history_wrap .year_tabs");

	const containerLeft = $container.scrollLeft();
	const containerWidth = $container.outerWidth();

	const tabLeft = $tab.position().left + containerLeft;
	const tabWidth = $tab.outerWidth();

	// 왼쪽으로 벗어난 경우
	if (tabLeft < containerLeft) {
		$container.stop().animate({
			scrollLeft: tabLeft - 20
		}, 300);
	}
	// 오른쪽으로 벗어난 경우
	else if (tabLeft + tabWidth > containerLeft + containerWidth) {
		$container.stop().animate({
			scrollLeft: tabLeft + tabWidth - containerWidth + 20
		}, 300);
	}
}
$(".history_wrap .year_tabs li button").on("click", function (e) {
	e.preventDefault();
	const $li = $(this).closest("li");
	const idx = $li.index();
	const targetTop = $(".years").offset().top;
	const headerH = $(".header").outerHeight() || 0;
	const tabsH = $(".history_wrap .year_tabs").outerHeight() || 0;
	$("html, body").stop().animate({
		scrollTop: targetTop - headerH - tabsH
	}, 500);
	scrollTabIntoView($li);
});
</script>
@endsection