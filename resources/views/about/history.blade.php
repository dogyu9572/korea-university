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
		@if($yearRanges)
		<ul class="year_tabs mo_vw">
			@foreach($yearRanges as $index => $range)
			<li class="{{ $loop->first ? 'on' : '' }}"><button type="button">{{ $range['label_end'] }} ~ {{ $range['label_start'] }}</button></li>
			@endforeach
		</ul>
		<div class="years">
			@foreach($yearRanges as $index => $range)
			<button type="button" class="{{ $loop->first ? 'on' : '' }}"><p>{{ $range['label_start'] }}</p><strong>{{ $range['label_end'] }}</strong><img src="/images/{{ $range['img'] }}" alt=""></button>
			@endforeach
		</div>
		<div class="daylist">
			@foreach($yearRanges as $range)
			<div class="cont_area">
				@foreach($range['histories'] as $history)
				<div class="box"><i></i>
					<div class="day"><strong>{{ $history->date_display }}</strong><p>{{ $history->title ?: $history->content }}</p></div>
					@if($history->content)
					<ul class="gbox dots_list">
						@foreach(array_filter(preg_split('/\r\n|\r|\n/', $history->content)) as $line)
						<li>{{ trim($line) }}</li>
						@endforeach
					</ul>
					@endif
				</div>
				@endforeach
			</div>
			@endforeach
			<div class="line"><div class="bar"></div></div>
		</div>
		@endif
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
	// 스크롤 위치 기준으로 버튼 상태 업데이트 (하단 구간에서는 마지막 구간 고정으로 흔들림 방지)
	function updateYearsByScroll() {
		const scrollTop = $(window).scrollTop();
		const headerH = $(".header").outerHeight() || 0;
		const winH = $(window).height();
		const $daylist = $history.find(".daylist");
		const listBottom = $daylist.length ? $daylist.offset().top + $daylist.outerHeight() : 0;
		const viewportBottom = scrollTop + winH;
		const bottomStickyZone = 220;
		if (viewportBottom >= listBottom - bottomStickyZone) {
			$yearsBtns.last().addClass("on").siblings().removeClass("on");
			updateBeforeAfterClass();
			return;
		}
		let currentYear = null;
		$boxes.each(function () {
			const boxTop = $(this).offset().top - headerH;
			if (scrollTop >= (boxTop - 60)) {
				currentYear = parseInt($(this).find(".day strong").text().substring(0, 4), 10);
			}
		});
		if (!currentYear) return;
		$yearsBtns.each(function () {
			const $btn = $(this);
			const { startYear, endYear } = getYearRange($btn);
			if (currentYear >= startYear && currentYear <= endYear) {
				$btn.addClass("on").siblings().removeClass("on");
				updateBeforeAfterClass();
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
	const historyTop = $history.offset().top;
	const historyHeight = $history.outerHeight();
	const yearsH = $years.outerHeight();
	const startPoint = historyTop - headerH;
	const endPoint = historyTop + historyHeight - yearsH;
	const buffer = 80;
	const isEnd = $history.hasClass("end");
	if (scrollTop < startPoint) {
		$history.removeClass("start end");
	} else if (isEnd) {
		if (scrollTop < endPoint - buffer) {
			$history.addClass("start").removeClass("end");
		}
	} else {
		if (scrollTop >= endPoint + buffer) {
			$history.addClass("end").removeClass("start");
		} else if (scrollTop >= startPoint && scrollTop < endPoint + buffer) {
			$history.addClass("start").removeClass("end");
		}
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