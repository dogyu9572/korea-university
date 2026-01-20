@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="board_view">
		<div class="tit">
			<div class="types"><span class="type c1">정기교육</span></div>
			<strong>2025년 산학협력단 직원 전문성 강화 교육(기본과정)</strong>
		</div>
	</div>
	
	<div class="application_view">
		<div class="point" id="start"></div>
		<div class="point" id="end"></div>
		<div class="imgfit"><img src="/images/img_application_view_sample3.jpg" alt=""></div>
		<div class="abso_info">
			<div class="tit">2025년 전국대학연구 산학협력관리자 협의회 추계세미나</div>
			<div class="con">
				<dl>
					<dt>구분</dt>
					<dd>추계세미나</dd>
				</dl>
				<dl>
					<dt>신청기간</dt>
					<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
				</dl>
				<dl>
					<dt>총차시/기수</dt>
					<dd>8차시/6기</dd>
				</dl>
				<dl>
					<dt>합숙여부</dt>
					<dd>합숙(숙박 선택 가능)</dd>
				</dl>
				<dl>
					<dt>정원</dt>
					<dd><strong class="c_iden">399명</strong> / 400명</dd>
				</dl>
			</div>
			<!-- <a href="javascript:void(0);" class="btn btn_end">수강신청마감</a> -->
			<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
		</div>
		
		<div class="otit mt0">첨부파일</div>
		<div class="download_area">
			<a href="#this">2025-62 2025년 산학협력단 직원 전문성 강화 교육기본과정 안내2025년 12월~1월.pdf</a>
			<a href="#this">붙임. 2025년 산학협력단 직원 전문성 강화 교육 안내12월-01월.pdf</a>
		</div>
		
		<div class="scroll_wrap">
			<ul class="scroll_tabs">
				<li class="on"><button type="button">교육개요</button></li>
				<li><button type="button">교육일정</button></li>
				<li><button type="button">참가비 및 납부 안내</button></li>
				<li><button type="button">취소 및 환불 규정</button></li>
				<li><button type="button">교과내용</button></li>
				<li><button type="button">교육안내</button></li>
			</ul>
			<div class="view_cont">
				<div class="cont">
					<div class="otit">교육개요</div>
					<div class="gbox">
						<dl>
							<dt>세미나명</dt>
							<dd>2025년 전국대학연구 산학협력관리자 협의회 추계세미나</dd>
						</dl>
						<dl>
							<dt>목적</dt>
							<dd>산학협력단 소속 직원의 역량 강화를 위해, 체계적이고 현장 중심적인 연구관리 및 산학협력 직무교육을 실시</dd>
						</dl>
						<dl>
							<dt>주최</dt>
							<dd>교육부, 한국연구재단</dd>
						</dl>
						<dl>
							<dt>주관</dt>
							<dd>전국대학 연구·산학협력관리자 협회</dd>
						</dl>
						<dl>
							<dt>교육대상</dt>
							<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
						</dl>
						<dl>
							<dt>교육형태</dt>
							<dd>집합교육(숙박 선택 가능)</dd>
						</dl>
						<dl>
							<dt>교육시간</dt>
							<dd>총 15시간</dd>
						</dl>
						<dl>
							<dt>수료기준</dt>
							<dd>교육이수율 90% 이상 + 설문조사 제출 시 수료증 발급</dd>
						</dl>
					</div>
				</div><!-- //교육개요 -->
				<div class="cont">
					<div class="otit">교육일정</div>
					<div class="over_tbl">
						<div class="scroll">
							<div class="tbl tbl_tac">
								<table>
									<colgroup>
										<col class="w80">
										<col class="w240">
										<col>
										<col class="w80">
										<col class="w160">
									</colgroup>
									<thead>
										<tr>
											<th>차수</th>
											<th>일정</th>
											<th>장소</th>
											<th>인원</th>
											<th>비고</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>6차</td>
											<td>2025.12.03.(수) ~ 12.05.(금)</td>
											<td>서울대학교 연구공원 943동 1층 세미나실</td>
											<td>40명</td>
											<td>숙박교육(희망자)</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div><!-- //교육일정 -->
				<div class="cont">
					<div class="otit">교육 참가비 및 납부 안내</div>
					<div class="over_tbl">
						<div class="scroll">
							<div class="tbl tbl_tac">
								<table>
									<colgroup>
										<col class="w20p">
										<col class="w20p">
										<col class="w20p">
										<col class="w20p">
										<col class="w20p">
									</colgroup>
									<thead>
										<tr>
											<th>구분</th>
											<th>2인 1실</th>
											<th>1인실</th>
											<th>비숙박</th>
											<th>비고</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>회원교(1인당)</td>
											<td>570,000원</td>
											<td>680,000원</td>
											<td>360,000원</td>
											<td>-</td>
										</tr>
										<tr>
											<td>비회원교(1인당)</td>
											<td>670,000원</td>
											<td>780,000원</td>
											<td>460,000원</td>
											<td>-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<ul class="glbox participation_fee">
						<li class="i1"><strong>납부방법</strong><p>계좌입금 (예시: “서울대 홍길동”)</p></li>
						<li class="i2"><strong>계좌정보</strong><p>농협은행 301-0334-8275-91 (전국대학연구산학협력관리자협의회)</p></li>
						<li class="i3"><strong>납부기한</strong><p>6차: 2025.11.26.(수) 17:00</p></li>
					</ul>
				</div><!-- //참가비 및 납부 안내 -->
				<div class="cont">
					<div class="otit">교육 취소 및 환불 규정</div>
					<div class="over_tbl">
						<div class="scroll">
							<div class="tbl tbl_tac">
								<table>
									<colgroup>
										<col class="w240">
										<col class="w330">
										<col>
									</colgroup>
									<thead>
										<tr>
											<th>구분</th>
											<th>수수료</th>
											<th>무료 취소 기한</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>2인 1실</th>
											<td>130,000원</td>
											<td>교육 시작일 기준 30일 전까지</td>
										</tr>
										<tr>
											<th>1인실</th>
											<td>260,000원</td>
											<td>교육 시작일 기준 30일 전까지</td>
										</tr>
										<tr>
											<th>비숙박</th>
											<td>참가비의 25%(75,000원)</td>
											<td>교육 시작일 기준 1주일 전까지</td>
										</tr>
										<tr>
											<th>당일 취소</th>
											<td>참가비 100%(300,000원) 부과</td>
											<td>-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<p class="ne">주의: 교육 취소 시, 참가비 입금 여부와 관계없이 수수료가 부과되오니 반드시 사전 확인 바랍니다.</p>
				</div><!-- //취소 및 환불 규정 -->
				<div class="cont">
					<div class="otit">교과내용</div>
					<ul class="gbox dots_list">
						<li>1일차: 산학협력단 개요와 창업지원, 연구협약 절차 등 기본 이론 및 실무 이해</li>
						<li>2일차: 연구비 감사·집행관리 실무와 사례분석, 대학별 사례 공유 및 토의</li>
						<li>3일차: 연구비 정산 및 사용실적 보고 절차 학습 후 수료식 진행</li>
					</ul>
				</div><!-- //교과내용 -->
				<div class="cont">
					<div class="otit">교육안내</div>
					<ul class="gbox dots_list">
						<li>차수별 참여인원: 회차별 40명 선착순 모집이며, 신청 인원이 미달될 경우 폐강될 수 있습니다. (개별 안내 예정)</li>
						<li>수료 기준: 수료 교육 이수율 90% 이상 및 설문조사 제출 시 수료증 발급</li>
						<li>과정 담당자: 김경영 (010-4228-7158, amelie0401@snu.ac.kr)</li>
					</ul>
				</div><!-- //교육안내 -->
			</div>
		</div>
		
	</div>
	
</main>

<script>
$(window).on("load resize", function () {
	const absoH = $(".application_view .abso_info").outerHeight() || 0;
	const headerH = $(".header").outerHeight() || 0;
	$("#end").css("bottom", absoH + headerH + "px");
}).trigger("resize");
$(window).on("scroll resize", function () {
	const scrollTop = $(window).scrollTop();
	const startTop = $("#start").offset().top;
	const endTop = $("#end").offset().top;
	const $view = $(".application_view");
	if (scrollTop >= endTop) {
		$view.addClass("end").removeClass("start");
	} else if (scrollTop >= startTop) {
		$view.addClass("start").removeClass("end");
	} else {
		$view.removeClass("start end");
	}
}).trigger("scroll");
//탭
$(window).on("scroll resize", function () {
	const scrollTop = $(window).scrollTop();
	const headerH = $(".header").outerHeight() || 0;
	const wrapTop = $(".scroll_wrap").offset().top;
	const tabsH = $(".scroll_wrap .scroll_tabs").outerHeight() || 0;
	const $wrap = $(".scroll_wrap");
	const 기준값 = scrollTop + headerH + tabsH + 10;
	if (scrollTop + headerH >= wrapTop) {
		$wrap.addClass("fixed");
	} else {
		$wrap.removeClass("fixed");
	}
	let activeIdx = -1;
	$(".view_cont .cont").each(function (i) {
		if (기준값 >= $(this).offset().top) {
			activeIdx = i;
		}
	});
	if (activeIdx > -1) {
		const $tabs = $(".scroll_wrap .scroll_tabs li");
		const $active = $tabs.removeClass("on").eq(activeIdx).addClass("on");
		scrollTabIntoView($active);
	}
}).trigger("scroll");
function scrollTabIntoView($tab) {
	const $container = $(".scroll_wrap .scroll_tabs");

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
$(".scroll_wrap .scroll_tabs li button").on("click", function (e) {
	e.preventDefault();
	const $li = $(this).closest("li");
	const idx = $li.index();
	const targetTop = $(".view_cont .cont").eq(idx).offset().top;
	const headerH = $(".header").outerHeight() || 0;
	const tabsH = $(".scroll_wrap .scroll_tabs").outerHeight() || 0;
	$("html, body").stop().animate({
		scrollTop: targetTop - headerH - tabsH
	}, 500);
	scrollTabIntoView($li);
});

//over_tbl
$(window).on('scroll resize', function() {
	$(".over_tbl").each(function() {
		var elementTop = $(this).offset().top;
		var elementHeight = $(this).outerHeight();
		var elementBottom = elementTop + elementHeight;

		var viewportTop = $(window).scrollTop();
		var viewportMiddle = viewportTop + ($(window).height() / 2);

		// 요소가 브라우저 중간 지점에 도달했을 때 클래스 추가
		if (elementTop < viewportMiddle && elementBottom > viewportTop) {
			$(this).addClass("on");
		}
	});
});
// 터치 이벤트 추가: 손가락으로 스와이프할 때 클래스 추가
$(".over_tbl").each(function() {
	let startX = 0, moveX = 0;
	$(this).on('touchstart', function(e) {
		startX = e.originalEvent.touches[0].pageX;
	});
	$(this).on('touchmove', function(e) {
		moveX = e.originalEvent.touches[0].pageX;
		let distance = Math.abs(moveX - startX);

		if (distance > 30) {
			$(this).addClass("on");
		}
	});
});
</script>

@endsection