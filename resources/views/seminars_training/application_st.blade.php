@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal">세미나 · 해외연수 신청</div>
	
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
						<dt>운영상태</dt>
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
		<li><a href="#this">세미나 신청(10개)</a></li>
		<li><a href="#this">해외연수 신청(10개)</a></li>
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
	
	<!-- type색상 ( c5 : 세미나, c6 : 해외연수 ) -->
	<div class="thum_list">
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c5">세미나</span>2025년 전국대학연구 산학협력관리자 협회 추계세미나</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">399명</strong> / 400명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>추계세미나</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>그랜드 하얏트 제주</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>750,000원(2인 1실), 990,000(1인실), 450,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c6">해외연수</span>2025년 연구·산학협력 벤치마킹 해외연수 안내</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>60명</strong> / 60명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.06.24.(화) 10시 ~ 06.26.(목) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.08.26.(화) ~ 09.03.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>해외연수</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>스페인, 이탈리아</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>6,300,000원(2인 1실), 7,860,000원(1인실)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c5">세미나</span>2025년 전국대학연구 산학협력관리자 협회 추계세미나</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">399명</strong> / 400명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>추계세미나</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>그랜드 하얏트 제주</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>750,000원(2인 1실), 990,000(1인실), 450,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c6">해외연수</span>2025년 연구·산학협력 벤치마킹 해외연수 안내</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>60명</strong> / 60명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.06.24.(화) 10시 ~ 06.26.(목) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.08.26.(화) ~ 09.03.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>해외연수</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>스페인, 이탈리아</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>6,300,000원(2인 1실), 7,860,000원(1인실)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c5">세미나</span>2025년 전국대학연구 산학협력관리자 협회 추계세미나</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">399명</strong> / 400명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>추계세미나</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>그랜드 하얏트 제주</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>750,000원(2인 1실), 990,000(1인실), 450,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c6">해외연수</span>2025년 연구·산학협력 벤치마킹 해외연수 안내</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>60명</strong> / 60명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.06.24.(화) 10시 ~ 06.26.(목) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.08.26.(화) ~ 09.03.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>해외연수</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>스페인, 이탈리아</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>6,300,000원(2인 1실), 7,860,000원(1인실)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c5">세미나</span>2025년 전국대학연구 산학협력관리자 협회 추계세미나</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">399명</strong> / 400명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>추계세미나</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>그랜드 하얏트 제주</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>750,000원(2인 1실), 990,000(1인실), 450,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c6">해외연수</span>2025년 연구·산학협력 벤치마킹 해외연수 안내</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>60명</strong> / 60명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.06.24.(화) 10시 ~ 06.26.(목) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.08.26.(화) ~ 09.03.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>해외연수</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>스페인, 이탈리아</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>6,300,000원(2인 1실), 7,860,000원(1인실)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c5">세미나</span>2025년 전국대학연구 산학협력관리자 협회 추계세미나</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong class="c_iden">399명</strong> / 400명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.09.24.(수) ~ 10.14.(화) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.11.05.(수) ~ 11.07.(금)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>추계세미나</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>그랜드 하얏트 제주</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>750,000원(2인 1실), 990,000(1인실), 450,000(비숙박)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="/seminars_training/application_st_apply" class="btn btn_write btn_wbb">신청하기</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="imgfit"><img src="/images/img_application_st_sample2.jpg" alt=""></div>
			<div class="txt">
				<div class="tit"><span class="type c6">해외연수</span>2025년 연구·산학협력 벤치마킹 해외연수 안내</div>
				<div class="info">
					<dl class="i1">
						<dt>정원</dt>
						<dd><strong>60명</strong> / 60명</dd>
					</dl>
					<dl class="i2">
						<dt>교육대상</dt>
						<dd>본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자</dd>
					</dl>
					<dl class="i3">
						<dt>신청기간</dt>
						<dd>2025.06.24.(화) 10시 ~ 06.26.(목) 17시</dd>
					</dl>
					<dl class="i4">
						<dt>교육기간</dt>
						<dd>2025.08.26.(화) ~ 09.03.(수)</dd>
					</dl>
					<dl class="i5">
						<dt>교육구분</dt>
						<dd>해외연수</dd>
					</dl>
					<dl class="i6">
						<dt>교육장소</dt>
						<dd>스페인, 이탈리아</dd>
					</dl>
					<dl class="i7">
						<dt>참가비</dt>
						<dd>6,300,000원(2인 1실), 7,860,000원(1인실)</dd>
					</dl>
				</div>
				<div class="btns_tal">
					<a href="javascript:void(0);" class="btn btn_end">신청마감</a>
					<a href="/seminars_training/application_st_view" class="btn btn_link btn_bwb">상세보기</a>
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
	$wrap.find('input[type="text"]').val('');
	$wrap.find('.con select').each(function () {
		this.selectedIndex = 0;
	});
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