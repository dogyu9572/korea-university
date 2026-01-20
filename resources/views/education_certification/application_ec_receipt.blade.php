@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal bdb">교육 · 자격증 신청</div>
	
	<div class="otit">시험 정보</div>
	<div class="glbox dl_slice">
		<dl>
			<dt>시험명</dt>
			<dd>대학연구행정전문가 2급 자격시험</dd>
		</dl>
		<dl>
			<dt>응시자격</dt>
			<dd>연구기관 종사자 또는 관련 업무 경력 2년 이상</dd>
		</dl>
		<dl>
			<dt>시험일</dt>
			<dd>2026.01.13(화) 12:00</dd>
		</dl>
		<dl>
			<dt>시험장 선택</dt>
			<dd>
				<select name="" id="" class="w1">
					<option value="">서울대학교 사범대학</option>
				</select>
			</dd>
		</dl>
	</div>

	<div class="otit">신청자 정보 입력</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>성명</dt>
			<dd><input type="text" class="text w1" placeholder="이름을 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>소속기관</dt>
			<dd class="inbtn">
				<input type="text" class="text" placeholder="학교명을 검색해주세요.">
				<button type="button" class="btn">검색</button>
				<input type="text" class="text w1" placeholder="학교명을 직접 입력해주세요.">
			</dd>
		</dl>
		<dl>
			<dt>휴대폰번호</dt>
			<dd><input type="text" class="text w1" placeholder="휴대폰번호를 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>이메일</dt>
			<dd><input type="text" class="text w1" placeholder="이메일을 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>생년월일</dt>
			<dd><input type="text" class="text w1" placeholder="생년월일을 입력해주세요."></dd>
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
			<dd class="colm">
				<input type="text" class="text w1" placeholder="예금주명을 입력해주세요.">
				<select name="" id="" class="text w1">
					<option value="">은행을 선택해주세요.</option>
				</select>
				<input type="text" class="text w1" placeholder="계좌번호를 입력해주세요.">
			</dd>
		</dl>
	</div>
	
	<div class="otit">결제 및 환불 안내</div>
	<div class="tbl th_bg">
		<table>
			<colgroup>
				<col class="w240">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th>결제방법</th>
					<td>무통장입금 (입금자명 확인 필수)</td>
				</tr>
				<tr>
					<th>입금계좌</th>
					<td>농협 301-0334-6275-91 (한국연구산학협력단연합회)</td>
				</tr>
				<tr>
					<th>응시료</th>
					<td><p class="big"><strong class="c_blue">20,000원</strong></p></td>
				</tr>
				<tr>
					<th>환불 규정</th>
					<td>
						<ul class="dots_list">
							<li>시험 3일 전까지 취소 시 전액 환불 가능</li>
							<li>시험 2일 전~시험 전일 취소 시 50% 환불</li>
							<li>당일 취소 및 시험 불참 시 환불 불가</li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="otit">증빙서류 발행 여부</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>현금영수증 발행</dt>
			<dd class="radios">
				<label class="radio"><input type="radio" name="bill" value="Y" checked><i></i><span>발행</span></label>
				<label class="radio"><input type="radio" name="bill" value="N"><i></i><span>미발행</span></label>
			</dd>
		</dl>
		<div class="gbox">
			<dl>
				<dt>용도 선택</dt>
				<dd class="radios">
					<label class="radio"><input type="radio" name="billType"><i></i><span>소득공제용</span></label>
					<label class="radio"><input type="radio" name="billType"><i></i><span>사업자 지출증빙용</span></label>
				</dd>
			</dl>
			<dl>
				<dt>발행번호</dt>
				<dd><input type="text" class="w1" value="010-1234-5678/123-45-67890"></dd>
			</dl>
		</div>
		<p class="ne">입금 확인 후 국세청으로 발행 처리됩니다.</p>
	</div>

	<div class="btns_tac">
		<button type="button" class="btn btn_bwb" onclick="history.back();">취소</button>
		<button type="button" class="btn btn_wbb" onclick="location.href='/education_certification/application_ec_receipt_end'">접수 신청</button>
	</div>
	
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

//증빙서류
$(function () {
	function toggleBill() {
		const isPublish = $('input[name="bill"]:checked').val() === "Y";
		const $box = $('input[name="bill"]').closest("dl").next(".gbox");
		const $inputs = $box.find("input");
		if (!isPublish) {
			$inputs.prop("checked", false);
		}
		$inputs.prop("disabled", !isPublish);
	}

	toggleBill();
	$('input[name="bill"]').on("change", toggleBill);
});
</script>

@endsection