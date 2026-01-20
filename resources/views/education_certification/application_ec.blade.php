@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal">교육 · 자격증 신청</div>
	
	<div class="search_wrap">
		<dl>
			<dt>프로그램명</dt>
			<dd><input type="text" class="text w100p" placeholder="프로그램명을 입력해주세요."></dd>
		</dl>
		<div class="filter_opcl on">
			<button type="button" class="btn_opcl">검색 필터</button>
			<div class="con" style="display:block;">
				<div class="flex">
					<dl>
						<dt>과정구분</dt>
						<dd>
							<select name="" id="" class="w100p">
								<option value="">전체</option>
							</select>
						</dd>
					</dl>
					<dl>
						<dt>기간구분</dt>
						<dd class="dates">
							<select name="" id="">
								<option value="">신청기간</option>
							</select>
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

	<ul class="round_tabs mb80">
		<li class="on"><a href="#this">전체(20개)</a></li>
		<li><a href="#this">교육 신청(15개)</a></li>
		<li><a href="#this">자격증 접수(2개)</a></li>
		<li><a href="#this">온라인 교육(3개)</a></li>
	</ul>
	
	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>20</strong></p>
			<p>PAGE <span><strong>1</strong>/2</span></p>
		</div>
		<div class="right list_filter">
			<a href="#this" class="on">최신 등록순</a>
			<a href="#this">신청 기간순</a>
		</div>
	</div>
	
	<!-- type색상 ( c1 : 정기교육, c2 : 수시교육, c3 : 자격증, c4 : 온라인교육 ) -->
	<div class="thum_list">
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c1">정기교육</span>2025년 산학협력단 직원 전문성 강화 교육(기본과정)</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">30명</strong> / 40명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.11.04.(화) ~ 11.28.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.12.03.(수) ~ 12.05.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>6차</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>서울대학교 연구공원 943동 1층 세미나실</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>570,000원(2인 1실), 680,000(1인실), 360,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/education_certification/application_ec_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c2">수시교육</span>2025년 연구현장 소통 콘서트 연구관리자를 위한 연구비 교육 </div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>40명</strong> / 40명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>재단 지원과제 수행기관 연구관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.10.14.(화) ~ 10.17.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.10.22.(수) 14:00~16:00</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>-</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>동국대학교(서울캠퍼스) 본관 3층 남산홀</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c3">자격증</span>대학연구행정전문가 자격시험(2급)</div>
				<div class="info">
					<dl class="i1">
						<dt>응시자격</dt>
						<dd>대학·정부출연연구기관·산학협력단 등에서 연구행정 또는 연구관리 업무를 수행 중인 실무자</dd>
					</dl>
					<dl class="i3">
						<dt>접수기간</dt>
						<dd>2026.11.18.(화) ~ 11.29.(토) (예정)</dd>
					</dl>
					<dl class="i4">
						<dt>시험일</dt>
						<dd>2026.12.07.(일) (예정)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/education_certification/application_ec_receipt" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/education_certification/application_ec_view_type2" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c3">자격증</span>대학연구행정전문가 자격시험(1급)</div>
				<div class="info">
					<dl class="i1">
						<dt>응시자격</dt>
						<dd>2급 자격 취득 후 2년 이상 연구행정 경력을 보유한 자, 또는 이에 준하는 실무 경력자</dd>
					</dl>
					<dl class="i3">
						<dt>접수기간</dt>
						<dd>2026.11.18.(화) ~ 11.29.(토) (예정)</dd>
					</dl>
					<dl class="i4">
						<dt>시험일</dt>
						<dd>2026.12.07.(일) (예정)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_wkk">개설예정</a>
					<a href="/education_certification/application_ec_view_type2" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c4">온라인교육</span>산학협력단 연구행정 기초과정(E-learning)</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">464명</strong> / 무제한</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 신규직원 및 연구행정 입문자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.10.14.(화) ~ 10.17.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.01.01.(수) ~ 12.31.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>5차시(인정시간: 5시간)</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>온라인 학습</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c1">정기교육</span>2025년 산학협력단 직원 전문성 강화 교육(기본과정)</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">30명</strong> / 40명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.11.04.(화) ~ 11.28.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.12.03.(수) ~ 12.05.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>6차</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>서울대학교 연구공원 943동 1층 세미나실</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>570,000원(2인 1실), 680,000(1인실), 360,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/education_certification/application_ec_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c2">수시교육</span>2025년 연구현장 소통 콘서트 연구관리자를 위한 연구비 교육 </div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>40명</strong> / 40명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>재단 지원과제 수행기관 연구관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.10.14.(화) ~ 10.17.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.10.22.(수) 14:00~16:00</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>-</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>동국대학교(서울캠퍼스) 본관 3층 남산홀</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c3">자격증</span>대학연구행정전문가 자격시험(2급)</div>
				<div class="info">
					<dl class="i1">
						<dt>응시자격</dt>
						<dd>대학·정부출연연구기관·산학협력단 등에서 연구행정 또는 연구관리 업무를 수행 중인 실무자</dd>
					</dl>
					<dl class="i3">
						<dt>접수기간</dt>
						<dd>2026.11.18.(화) ~ 11.29.(토) (예정)</dd>
					</dl>
					<dl class="i4">
						<dt>시험일</dt>
						<dd>2026.12.07.(일) (예정)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/education_certification/application_ec_receipt" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/education_certification/application_ec_view_type2" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c3">자격증</span>대학연구행정전문가 자격시험(1급)</div>
				<div class="info">
					<dl class="i1">
						<dt>응시자격</dt>
						<dd>2급 자격 취득 후 2년 이상 연구행정 경력을 보유한 자, 또는 이에 준하는 실무 경력자</dd>
					</dl>
					<dl class="i3">
						<dt>접수기간</dt>
						<dd>2026.11.18.(화) ~ 11.29.(토) (예정)</dd>
					</dl>
					<dl class="i4">
						<dt>시험일</dt>
						<dd>2026.12.07.(일) (예정)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_wkk">개설예정</a>
					<a href="/education_certification/application_ec_view_type2" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_ec_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c4">온라인교육</span>산학협력단 연구행정 기초과정(E-learning)</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">464명</strong> / 무제한</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 신규직원 및 연구행정 입문자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.10.14.(화) ~ 10.17.(금)</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.01.01.(수) ~ 12.31.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>5차시(인정시간: 5시간)</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>온라인 학습</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/education_certification/application_ec_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
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
	
</main>

<script>
$(".filter_opcl .btn_opcl").click(function(){
	$(this).next(".con").slideToggle("fast").parent().toggleClass("on");
});
$('.btn_reset').on('click', function () {
	const $wrap = $(this).closest('.search_wrap');

	// 프로그램명 input 초기화
	$wrap.find('input[type="text"]').val('');

	// 필터 영역 select 초기화 (첫 번째 option으로)
	$wrap.find('.con select').each(function () {
		this.selectedIndex = 0;
	});

	// 혹시 체크박스/라디오가 추가될 경우 대비
	$wrap.find('.con input[type="checkbox"], .con input[type="radio"]').prop('checked', false);
});

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