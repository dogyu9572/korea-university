@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="stitle tal bdb">교육 신청 현황
		<div class="btns_abso pc_vw">
			<a href="/print/admission_ticket" target="_blank" class="btn btn_wbb">수험표 출력</a>
			<a href="/print/receipt" target="_blank" class="btn btn_wbb">영수증 출력</a>
			<a href="/print/certificate" target="_blank" class="btn btn_bwb">자격증 출력</a>
			<a href="/print/certificate_qualification" target="_blank" class="btn btn_bwb">합격확인서 출력</a>
		</div>
		<div class="btns_abso mo_vw">
			<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
		</div>
	</div>
	
	<div class="otit">응시 및 자격 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>시험명</dt>
			<dd>대학연구행정전문가 2급</dd>
		</dl>
		<dl>
			<dt>시험일자</dt>
			<dd>2025-01-20 (토) 10:00</dd>
		</dl>
		<dl>
			<dt>접수번호</dt>
			<dd>RCPT20250120045</dd>
		</dl>
		<dl>
			<dt>상태</dt>
			<dd>신청대기</dd>
		</dl>
		<dl>
			<dt>시험장 정보</dt>
			<dd>서울대학교 사범대학</dd>
		</dl>
		<dl>
			<dt>취득일자</dt>
			<dd>2025-02-05</dd>
		</dl>
		<dl>
			<dt>유효기간</dt>
			<dd>2031-01-10</dd>
		</dl>
		<dl>
			<dt>인증기관</dt>
			<dd>전국대학연구·산학협력관리자 협회</dd>
		</dl>
		<dl>
			<dt>점수</dt>
			<dd><strong>85점</strong></dd>
		</dl>
	</div>

	<div class="otit">응시자 정보 입력</div>
	<div class="glbox dl_tbl dl_inputs">
		<dl>
			<dt>성명</dt>
			<dd><input type="text" placeholder="성명을 입력해주세요." value="홍길동"></dd>
		</dl>
		<dl>
			<dt>소속기관</dt>
			<dd class="flex school">
				<input type="text" class="slice" placeholder="학교명을 검색해주세요." readonly>
				<button type="button" class="btn">검색</button>
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
			<dt>생년월일</dt>
			<dd><input type="text" placeholder="생년월일을 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>증명사진</dt>
			<dd class="file_inputs">
				<label class="file"><input type="file"><span>파일선택</span></label>
				<div class="file_input">선택된 파일 없음</div>
			</dd>
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
	
	<div class="otit">결제/입금 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>결제상태</dt>
			<dd>
				<i class="deposit completed">Y</i>
				<!-- <i class="deposit not">N</i> -->
			</dd>
		</dl>
		<dl>
			<dt>결제금액</dt>
			<dd>20,000원</dd>
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

	<div class="otit">시험 응시 유의사항</div>
	<ul class="gbox dots_list">
		<li>시험 당일 수험표와 신분증을 반드시 지참해야 합니다.</li>
		<li>시험 시작 20분 전까지 입실해야 합니다.</li>
		<li>시험 중 휴대폰 사용 시 즉시 실격 처리됩니다.</li>
	</ul>

	<div class="btns_tac">
		<a href="/mypage/my_qualification" class="btn btn_bwb">목록</a>
		<button type="button" class="btn btn_wbb">저장하기</button>
	</div>
	
	<!-- 출력 -->
	@include('print.pop_print')
	
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