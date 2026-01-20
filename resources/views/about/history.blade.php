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
			<div class="cont_area">
				<div class="box"><i></i>
					<div class="day"><strong>2025.08.26</strong><p>2025년도 연구산학협력 벤치마킹 해외연수</p></div>
					<ul class="gbox dots_list">
						<li>연수국가 : 스페인, 이탈리아(바르셀로나 바이오메디컬연구단지, 카탈루냐 기술센터, 로마 토르 베리가타 대학)</li>
						<li>연수기간 : 2025. 8. 26. ~ 9. 5.</li>
						<li>참석인원 : 대학 회원 담당자 69명 참석</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2025.03.26</strong><p>제 60차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2025. 3. 26. ~ 3. 28.</li>
						<li>장소 : 제주신화월드 랜딩관</li>
						<li>내용 : 산학협력 주요 정책 방향, 대학 산학협력단 기능 고도화 지원 방안, 대학 간접비 고시비율 산출 안내 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2024.11.13</strong><p>제 59차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2024. 11. 13. ~ 11. 15.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : AI 시대의 연구윤리, 연구관리실무와 부가가치세, 연구지원체계평가에 대한 이해 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2024.07.09</strong><p>2024년도 연구산학협력 벤치마킹 해외연수</p></div>
					<ul class="gbox dots_list">
						<li>연수국가 : 스페인, 독일(바르셀로나 바이오메디컬연구단지, 바르셀로나 대학, 카탈루냐 공과대학, 하이델베르크 대학)</li>
						<li>연수기간 : 2024. 7. 9. ~ 7. 17.</li>
						<li>참석인원 : 대학 회원 담당자 72명 참석</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2024.03.27</strong><p>제 58차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2024. 3. 27. ~ 3. 29.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 사단법인 설립 총회, 정부연구비 사용 관련 변화된 제도와 정산 지적 사례, 2024년부터 달라지는 혁신법 개선 사항, 연구비 관리 우수 사례 발표 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2023.11.22</strong><p>제 57차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2023. 11. 22. ~ 11. 24.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 산학협력 주요 정책 방향, 산학협력 회계 실태점검 사례 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2023.06.29</strong><p>2023년도 연구산학협력 벤치마킹 해외연수</p></div>
					<ul class="gbox dots_list">
						<li>연수국가: 스페인, 프랑스, 독일(바르셀로나 대학, 소르본 대학, 하이델베르그 테크놀로지파크)</li>
						<li>연수기간 : 2023. 6. 29. ~ 7. 7.</li>
						<li>참석인원 : 대학 회원 담당자 72명 참석</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2023.03.28</strong><p>제 56차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2023. 3. 28. ~ 3. 31.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 정부연구비 사용 관련 변화된 제도와 정산 지적 사례, 연구비 관리 우수 사례 발표 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2022.11.21</strong><p>제16대 회장 서울대학교 박준철</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2022.11.21</strong><p>제 55차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2022. 11. 21. ~ 11. 23.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 산학협력단 시범패키지 컨설팅 방향, 대학 연구지원 체계평가 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2022.03.30</strong><p>제 54차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2022. 3. 30. ~ 4. 1.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 연구비관리스템 발전 방향, 대학 연구지원 체계평가 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2021.11.29</strong><p>제 53차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2021. 11. 29. ~ 12. 1.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 산학협력단 간접비 원가계산 등 회계 이슈, 연구비관리 교육 등</li>
					</ul>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2021.04.07</strong><p>제 52차 세미나 개최</p></div>
					<ul class="gbox dots_list">
						<li>일자 : 2021. 4. 7. ~ 4. 9.</li>
						<li>장소 : 라마다프라자 제주호텔</li>
						<li>내용 : 정부 산학협력 정책, 국가연구개발혁신법 등 연구제도 이슈</li>
					</ul>
				</div>
			</div>
			<div class="cont_area">
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
				<div class="box"><i></i>
					<div class="day"><strong>2019.01.01</strong><p>sample</p></div>
				</div>
			</div>
			<div class="cont_area">
				<div class="box"><i></i>
					<div class="day"><strong>2009.01.01</strong><p>sample</p></div>
				</div>
			</div>
			<div class="cont_area">
				<div class="box"><i></i>
					<div class="day"><strong>1999.01.01</strong><p>sample</p></div>
				</div>
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