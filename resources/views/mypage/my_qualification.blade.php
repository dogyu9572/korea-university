@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="stitle tal">나의 자격 현황</div>
	
	<div class="board_top">
		<div class="left">
			<p>TOTAL <strong>20</strong></p>
		</div>
	</div>
	
	<ul class="application_list">
 		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state complete">접수완료</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state pass">합격</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state fail">불합격</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state complete">접수완료</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state pass">합격</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state fail">불합격</i></span>
				<span class="tit">대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
		<li>
			<a href="/mypage/my_qualification_view" class="link">
				<span class="statebox"><i class="state pend">신청대기</i></span>
				<span class="tit">대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급</span>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>2031-01-10</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
				<button type="button" class="btn btn_cancel" onclick="layerShow('classCancel')">수강취소</button>
			</div>
		</li>
	</ul>
	</div>

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
	
	<!-- 신청취소 -->
	<div class="popup" id="ClassCancel">
		<div class="dm" onclick="layerHide('ClassCancel')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('ClassCancel')">닫기</button>
			<div class="tit">신청취소</div>
			<div class="con gbox">
				<dl>
					<dt>교육명</dt>
					<dd>대학연구행정전문가 2급 대학연구행정전문가 2급 대학연구행정전문가 2급</dd>
				</dl>
				<dl>
					<dt>시험일자</dt>
					<dd>2025-01-20 10:00</dd>
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
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
}
</script>

@endsection