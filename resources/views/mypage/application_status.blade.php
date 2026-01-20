@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="stitle tal">교육 신청 현황</div>
	
	<div class="search_wrap">
		<dl>
			<dt>교육명</dt>
			<dd><input type="text" class="text w100p" placeholder="교육명을 입력해주세요."></dd>
		</dl>
		<div class="filter_opcl on">
			<button type="button" class="btn_opcl">검색 필터</button>
			<div class="con" style="display:block;">
				<div class="flex">
					<dl>
						<dt>교육구분</dt>
						<dd>
							<select name="" id="" class="w100p">
								<option value="">전체</option>
							</select>
						</dd>
					</dl>
					<dl>
						<dt>교육기간</dt>
						<dd class="dates half">
							<select name="" id="">
								<option value="">2025</option>
							</select>
							<select name="" id="">
								<option value="">전체</option>
							</select>
						</dd>
					</dl>
					<dl>
						<dt>진행상태</dt>
						<dd>
							<select name="" id="" class="w100p">
								<option value="">신청하기</option>
							</select>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="btns_tac mt0">
			<button type="button" class="btn btn_search btn_wbb btn_w160">검색</button>
			<button type="button" class="btn btn_reset btn_bwb btn_w160">초기화</button>
		</div>
	</div>
	
	<ul class="application_status">
		<li class="i1"><strong>오프라인 교육 수료 기준</strong><p>교육이수 기간 90% 이상(지정좌석제 운영) 참석 및 <br class="pc_vw">설문조사 제출시 수료증 부여</p></li>
		<li class="i2"><strong>온라인 교육 수료 기준</strong><p>전체 강의의 80% 이상 시청 및 설문조사 제출시 수료증 부여</p></li>
	</ul>
	
	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>20</strong></p>
		</div>
	</div>
	
	<ul class="application_list">
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">2025 산학협력단 직원 전문성 강화 교육 (기본과정)(8차시)</span>
				<dl>
					<dt>교육구분</dt>
					<dd>정기교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state appli">신청완료</i></span>
				<span class="tit">2025 연구현장 소통 콘서트 연구관리자를 위한 연구비 교육</span>
				<dl>
					<dt>교육구분</dt>
					<dd>수시교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state complete">수료</i></span>
				<span class="tit">2025 전국대학연구 산학협력관리자 협의회 추계세미나</span>
				<dl>
					<dt>교육구분</dt>
					<dd>세미나</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view2" class="link">
				<span class="statebox"><i class="state incomplete">미수료</i></span>
				<span class="tit">2025 연구관리 실무자 온라인 기본교육</span>
				<dl>
					<dt>교육구분</dt>
					<dd>온라인교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">2025 산학협력단 직원 전문성 강화 교육 (기본과정)(8차시)</span>
				<dl>
					<dt>교육구분</dt>
					<dd>정기교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state appli">신청완료</i></span>
				<span class="tit">2025 연구현장 소통 콘서트 연구관리자를 위한 연구비 교육</span>
				<dl>
					<dt>교육구분</dt>
					<dd>수시교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state complete">수료</i></span>
				<span class="tit">2025 전국대학연구 산학협력관리자 협의회 추계세미나</span>
				<dl>
					<dt>교육구분</dt>
					<dd>세미나</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view2" class="link">
				<span class="statebox"><i class="state incomplete">미수료</i></span>
				<span class="tit">2025 연구관리 실무자 온라인 기본교육</span>
				<dl>
					<dt>교육구분</dt>
					<dd>온라인교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/application_status_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">2025 산학협력단 직원 전문성 강화 교육 (기본과정)(8차시)</span>
				<dl>
					<dt>교육구분</dt>
					<dd>정기교육</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
	</ul>

	<div class="board_bottom">
		<div class="paging">
			<a href="#this" class="arrow two first">맨끝</a>
			<a href="#this" class="arrow one prev">이전</a>
			<a href="#this" class="on">1</a>
			<a href="#this">2</a>
			<a href="#this">3</a>
			<a href="#this">4</a>
			<a href="#this">5</a>
			<a href="#this" class="arrow one next">다음</a>
			<a href="#this" class="arrow two last">맨끝</a>
		</div>
	</div> <!-- //board_bottom -->
	
	<!-- 수강취소 -->
	<div class="popup" id="classCancel">
		<div class="dm" onclick="layerHide('classCancel')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('classCancel')">닫기</button>
			<div class="tit">수강취소</div>
			<div class="con gbox">
				<dl>
					<dt>교육구분</dt>
					<dd>정기교육</dd>
				</dl>
				<dl>
					<dt>교육명</dt>
					<dd>2025 산학협력단 직원 전문성 강화 교육(기본과정)(8차시)</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>2025.12.03(수) - 2025.12.05(금)</dd>
				</dl>
				<dl>
					<dt>환불금액</dt>
					<dd>360,000원</dd>
				</dl>
			</div>
			<div class="btns_tac">
				<button type="button" class="btn btn_wbb">취소하기</button>
			</div>
		</div>
	</div>
	
	<!-- 출력 -->
	@include('print.pop_print')
    
</main>

<script>
$(".filter_opcl .btn_opcl").click(function(){
	$(this).next(".con").slideToggle("fast").parent().toggleClass("on");
});
$('.btn_reset').on('click', function () {
	const $wrap = $(this).closest('.search_wrap');
	$wrap.find('input[type="text"]').val('');
	$wrap.find('.con select').each(function () {
		this.selectedIndex = 0;
	});
	$wrap.find('.con input[type="checkbox"], .con input[type="radio"]').prop('checked', false);
});

//팝업
function layerShow(id) {
	$("#" + id).fadeIn(300);
	$("html,body").addClass("over_h");
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
	$("html,body").removeClass("over_h");
}

//검색 PC 열림, 모바일 닫힘
function toggleFilterOn() {
	if ($(window).width() >= 768) {
		$('.filter_opcl').addClass('on');
		$(".search_wrap .filter_opcl .con").show();
	} else {
		$('.filter_opcl').removeClass('on');
		$(".search_wrap .filter_opcl .con").hide();
	}
}
toggleFilterOn();
$(window).on('resize', toggleFilterOn);
</script>

@endsection