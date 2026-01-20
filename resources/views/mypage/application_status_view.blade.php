@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="stitle tal bdb">교육 신청 현황
		<div class="btns_abso pc_vw">
			<a href="/print/receipt" target="_blank" class="btn btn_wbb">영수증 출력</a>
			<a href="/print/certificate_completion" target="_blank" class="btn btn_bwb">수료증 출력</a>
			<a href="/print/certificate_finish" target="_blank" class="btn btn_bwb">이수증 출력</a>
		</div>
		<div class="btns_abso mo_vw">
			<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
		</div>
	</div>
	
	<!-- 공통사항 -->
	<div class="otit">교육 신청 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>교육구분</dt>
			<dd>
				<!-- 정기교육일 경우 -->
				<i class="type regular">정기교육</i>
				<!-- 세미나일 경우 -->
				<!-- <i class="type semina">세미나</i> -->
			</dd>
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
			<dt>신청일</dt>
			<dd>YYYY.MM.DD</dd>
		</dl>
		<dl>
			<dt>신청번호</dt>
			<dd>EDU202511100023</dd>
		</dl>
		<dl>
			<dt>수료일</dt>
			<dd>YYYY.MM.DD</dd>
		</dl>
		<dl>
			<dt>상태</dt>
			<dd>
				<i class="state pend">신청대기</i>
				<!-- <i class="state appli">신청완료</i>
				<i class="state complete">수료</i>
				<i class="state incomplete">미수료</i> -->
			</dd>
		</dl>
	</div>

	<div class="otit">교육 신청자 정보</div>
	<div class="glbox dl_tbl dl_inputs">
		<dl>
			<dt>성명</dt>
			<dd><input type="text" placeholder="성명을 입력해주세요." value="홍길동"></dd>
		</dl>
		<dl>
			<dt>소속기관</dt>
			<dd class="flex school">
				<input type="text" class="slice" placeholder="학교명을 검색해주세요." readonly>
				<button type="button" class="btn" onclick="layerShow('searchSchool')">검색</button>
				<input type="text" placeholder="학교명을 직접 입력해주세요.">
			</dd>
		</dl>
		<dl>
			<dt>휴대폰번호</dt>
			<dd><input type="text" placeholder="휴대폰번호를 입력해주세요." value="010-1234-5678"></dd>
		</dl>
		<dl>
			<dt>이메일</dt>
			<dd><input type="text" placeholder="이메일을 입력해주세요." value="abc1234@google.com"></dd>
		</dl>
		<dl>
			<dt>환불 계좌 정보</dt>
			<dd class="flex colm">
				<input type="text" placeholder="계좌명을 입력해주세요." value="홍길동">
				<select name="" id="">
					<option value="">은행을 선택해주세요</option>
					<option value="" selected>국민은행</option>
				</select>
				<input type="text" placeholder="계좌번호를 입력해주세요." value="302-12345678-98">
			</dd>
		</dl>
	</div>
	<!-- //공통사항 -->
	
	<!-- 세미나만 노출 -->
	<div class="otit">세미나/해외연수 룸메이트 정보</div>
	<div class="roommate_info">
		<dl class="i1">
			<dt>받은 룸메이트 요청</dt>
			<dd>홍** 님이 회원님을 룸메이트로 요청했습니다. <div class="affili"><strong>소속기관</strong><p>전국대학교</p></div>
				<div class="abso btns">
					<button type="button" class="btn btn_wkk">수락하기</button>
					<button type="button" class="btn btn_gwg">거절하기</button>
				</div>
			</dd>
		</dl>
		<dl class="i2">
			<dt>내가 보낸 룸메이트</dt>
			<dd>김** <div class="state">확정</div><p class="abso ne">상대방이 수락 시 자동으로 "룸메이트 확정"으로 변경됩니다.</p></dd>
		</dl>
	</div>
	<!-- //세미나만 노출 -->
	
	<!-- 공통사항 -->
	<div class="otit">결제/입금 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>결제상태</dt>
			<dd>
				<i class="deposit completed">입금완료</i>
				<!-- <i class="deposit not">미입금</i> -->
			</dd>
		</dl>
		<dl>
			<dt>결제금액</dt>
			<dd>360,000원</dd>
		</dl>
		<dl>
			<dt>입금계좌</dt>
			<dd>농협 301-0334-4275-01</dd>
		</dl>
		<dl>
			<dt>입금자명</dt>
			<dd>홍길동</dd>
		</dl>
		<dl>
			<dt>입금일자</dt>
			<dd>YYYY.MM.DD</dd>
		</dl>
	</div>
	
	<div class="otit mb0">증빙서류 발행 여부</div>
	<div class="obtit">현금영수증 발행 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>용도</dt>
			<dd>소득공제용</dd>
		</dl>
		<dl>
			<dt>발행 번호</dt>
			<dd>010-1234-5678</dd>
		</dl>
		<dl>
			<dt>발행 상태</dt>
			<dd>발행전/발행완료</dd>
		</dl>
	</div>
	<div class="obtit">세금계산서 발행 정보</div>
	<div class="glbox dl_tbl dt_long">
		<dl>
			<dt>사업자등록번호</dt>
			<dd>123-45-67890</dd>
		</dl>
		<dl>
			<dt>발행 번호</dt>
			<dd>010-1234-5678</dd>
		</dl>
		<dl>
			<dt>상호명</dt>
			<dd>홍길동상사</dd>
		</dl>
		<dl>
			<dt>대표자명</dt>
			<dd>홍길동</dd>
		</dl>
		<dl>
			<dt>업태 / 종목</dt>
			<dd>서비스 / 교육</dd>
		</dl>
		<dl>
			<dt>주소</dt>
			<dd>서울특별시 OO구 OO로 123</dd>
		</dl>
		<dl>
			<dt>담당자명</dt>
			<dd>김담당</dd>
		</dl>
		<dl>
			<dt>담당자 이메일</dt>
			<dd>example@snu.ac.kr</dd>
		</dl>
		<dl>
			<dt>담당자 연락처</dt>
			<dd>010-1111-2222</dd>
		</dl>
		<dl>
			<dt>발행 상태</dt>
			<dd>발행전/발행완료</dd>
		</dl>
		<dl>
			<dt>발행일</dt>
			<dd>2025-01-19</dd>
		</dl>
		<dl>
			<dt>발송 이메일</dt>
			<dd>example@snu.ac.kr</dd>
		</dl>
		<dl>
			<dt>사업자등록증 첨부</dt>
			<dd class="file_inputs">
				<label class="file"><input type="file"><span>파일선택</span></label>
				<div class="file_input">선택된 파일 없음</div>
			</dd>
		</dl>
	</div>
	<!-- //공통사항 -->
	
	<!-- 세미나만 노출 -->
	<div class="otit">교육 설문 제출</div>
	<div class="glbox dl_tbl">
		<dl class="aic">
			<dt>설문 참여 링크</dt>
			<dd><a href="https://docs.google.com/forms/d/12cQU10QDcfnoQAz2xpB8S7-E2MLgrxQLg4SUPVIzPGI/edit" target="" class="btn_outlink">https://docs.google.com/forms/d/12cQU10QDcfnoQAz2xpB8S7-E2MLgrxQLg4SUPVIzPGI/edit</a></dd>
		</dl>
	</div>
	<!-- //세미나만 노출 -->

	<div class="btns_tac">
		<a href="/mypage/application_status" class="btn btn_bwb">목록</a>
		<button type="button" class="btn btn_wbb">저장하기</button>
	</div>
	
	<!-- 출력 -->
	@include('print.pop_print')
	<!-- 학교검색 -->
	@include('member.pop_search_school')
	
</main>

<script>
$(document).on("change", ".file_inputs input[type='file']", function () {
	const $input = $(this);
	const $wrap = $input.closest(".file_inputs");
	const $fileInput = $wrap.find(".file_input");
	const file = this.files[0];

	if (file) {
		$fileInput
			.addClass("w100p")
			.empty()
			.append(`<button type="button">${file.name}</button>`);
	}
});

$(document).on("click", ".file_input button", function () {
	const $btn = $(this);
	const $wrap = $btn.closest(".file_inputs");
	const $input = $wrap.find("input[type='file']");
	const $fileInput = $wrap.find(".file_input");

	// 파일 초기화
	$input.val("");

	// UI 원복
	$fileInput
		.removeClass("w100p")
		.text("선택된 파일 없음");
});
</script>

@endsection