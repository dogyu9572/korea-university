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
		<div class="imgfit"><img src="/images/img_application_view_sample2.jpg" alt=""></div>
		<div class="abso_info">
			<div class="tit">대한연구행정전문가 2급 자격시험</div>
			<div class="con">
				<dl>
					<dt>구분</dt>
					<dd>자격증</dd>
				</dl>
				<dl>
					<dt>접수기간</dt>
					<dd>2025.11.18(화) ~ 11.29(금)</dd>
				</dl>
				<dl>
					<dt>시험일</dt>
					<dd>2026.01.13(화) 12:00</dd>
				</dl>
				<dl>
					<dt>응시자격</dt>
					<dd>연구관리 실무 경력 2년 이상 또는 연구기관 종사자</dd>
				</dl>
				<dl>
					<dt>시험방식</dt>
					<dd>필기시험 (객관식)</dd>
				</dl>
				<dl>
					<dt>정원</dt>
					<dd><strong class="c_iden">300명</strong></dd>
				</dl> 
			</div>
			<a href="/education_certification/application_ec_receipt" class="btn btn_write btn_wbb">시험 접수하기</a>
		</div>
		
		<div class="scroll_wrap">
			<ul class="scroll_tabs">
				<li class="on"><button type="button">시험개요</button></li>
				<li><button type="button">출제경향</button></li>
				<li><button type="button">시험장 정보</button></li>
			</ul>
			<div class="view_cont">
				<div class="cont">
					<div class="otit">시험개요</div>
					<div class="gbox">
						<dl>
							<dt>자격명</dt>
							<dd>대학연구행정전문가 2급 시험</dd>
						</dl>
						<dl>
							<dt>등급</dt>
							<dd>2급</dd>
						</dl>
						<dl>
							<dt>인증기관</dt>
							<dd>전국대학연구·산학협력관리자협회</dd>
						</dl>
						<dl>
							<dt>발급조건</dt>
							<dd>60점 이상 점수 충족 시 발급</dd>
						</dl>
					</div>
				</div><!-- //시험개요 -->
				<div class="cont">
					<div class="otit">출제경향</div>
					<div class="tbl tbl_tac th_bg">
						<table>
							<colgroup>
								<col class="w200">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th class="tac">1과목</th>
									<td class="tal">연구비 관리, 과제관리, 연구윤리 등 연구행정 전반</td>
								</tr>
								<tr>
									<th class="tac">2과목</th>
									<td class="tal">과제 수행 실무, 정산 및 보고 업무 이해도 평가</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div><!-- //출제경향 -->
				<div class="cont">
					<div class="otit">시험장 정보</div>
					<ul class="test_site_info flex">
						<li class="i1"><strong>시험장 위치</strong><p>서울대학교 연구공원 지하 1층 시험센터</p></li>
						<li class="i2"><strong>교통 안내</strong><p>지하철 2호선 서울대입구역, 셔틀버스 운행</p></li>
					</ul>
				</div><!-- //시험장 정보 -->
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
$(document).ready (function () {
	//탭(ul) onoff
	$('.jq_tabonoff>.jq_cont').children().css('display', 'none');
	$('.jq_tabonoff>.jq_cont .cont:first-child').css('display', 'block');
	$('.jq_tabonoff>.jq_tab > li:first-child').addClass('on');

	$('.jq_tabonoff').delegate('.jq_tab>li', 'click', function() {
		var index = $(this).parent().children().index(this);
		$(this).siblings().removeClass('on');
		$(this).addClass('on');
		$(this).parent().next('.jq_cont').children().hide().eq(index).show();
	});
});
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
	$(".view_cont .cont").each(function (i) {
		const contTop = $(this).offset().top;

		if (기준값 >= contTop) {
			$(".scroll_wrap .scroll_tabs li").removeClass("on")
				.eq(i).addClass("on");
		}
	});
}).trigger("scroll");
$(".scroll_wrap .scroll_tabs li button").on("click", function (e) {
	e.preventDefault();

	const idx = $(this).closest("li").index();
	const targetTop = $(".view_cont .cont").eq(idx).offset().top;
	const headerH = $(".header").outerHeight() || 0;
	const tabsH = $(".scroll_wrap .scroll_tabs").outerHeight() || 0;

	$("html, body").stop().animate({
		scrollTop: targetTop - headerH - tabsH
	}, 500);
});
</script>

@endsection