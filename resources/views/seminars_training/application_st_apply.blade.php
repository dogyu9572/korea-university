@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal bdb">세미나 · 해외연수 신청</div>
	
	<div class="otit">세미나 신청</div>
	<div class="glbox dl_slice">
		<dl>
			<dt>세미나명</dt>
			<dd>2025년 전국대학연구 산학협력관리자 협의회 추계세미나</dd>
		</dl>
		<dl>
			<dt>교육기간</dt>
			<dd>2025.11.05.(수) - 2025.11.07.(금)</dd>
		</dl>
		<dl>
			<dt>교육시간</dt>
			<dd>15시간</dd>
		</dl>
		<dl>
			<dt>수료기준</dt>
			<dd>교육 이수율 90% 이상 + 설문조사 제출</dd>
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
				<button type="button" class="btn" onclick="layerShow('searchSchool')">검색</button>
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
	
	<div class="otit">교육 참가비 선택</div>
	<div class="tbl th_bg mo_reverse_tbl">
		<table>
			<colgroup>
				<col class="w240">
				<col>
				<col>
				<col>
			</colgroup>
			<thead>
				<tr>
					<th>구분</th>
					<th class="tac">2인 1실</th>
					<th class="tac">1인실</th>
					<th class="tac">비숙박</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>회원교(1인당)</th>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay" checked><i></i><span>570,000원</span></label></td>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay"><i></i><span>680,000원</span></label></td>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay"><i></i><span>360,000원</span></label></td>
				</tr>
				<tr>
					<th>비회원교(1인당)</th>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay"><i></i><span>670,000원</span></label></td>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay"><i></i><span>780,000원</span></label></td>
					<td class="tac"><label class="radio"><input type="radio" name="eduPay"><i></i><span>460,000원</span></label></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="obtit">룸메이트 선택</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>룸메이트 휴대폰번호 입력</dt>
			<dd class="inbtn">
				<input type="text" class="text" placeholder="010-1234-5678">
				<button type="button" class="btn" onclick="layerShow('confirmInfo')">조회하기</button>
				<!-- <button type="button" class="btn" onclick="layerShow('noInfo')">조회하기</button> 없을 때 -->
				<p class="ne w100p mt0 hide">룸메이트가 성공적으로 신청되었습니다.</p>
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
					<th>입금기한</th>
					<td>접수 마감일 2025.11.29 17:00까지</td>
				</tr>
				<tr>
					<th>환불 규정</th>
					<td class="intbl">
						<table class="tbl_default tbl_tac">
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
									<td class="refund01">130,000원</td>
									<td class="refund02">교육 시작일 기준 30일 전까지</td>
								</tr>
								<tr>
									<th>1인실</th>
									<td class="refund01">260,000원</td>
									<td class="refund02">교육 시작일 기준 30일 전까지</td>
								</tr>
								<tr>
									<th>비숙박</th>
									<td class="refund01">참가비의 25%(75,000원)</td>
									<td class="refund02">교육 시작일 기준 1주일 전까지</td>
								</tr>
								<tr>
									<th>당일 취소</th>
									<td class="refund01">참가비 100%(300,000원) 부과</td>
									<td class="refund02 blank">-</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="ne">시험 취소는 시험일 3일 전까지 가능하며, 이후에는 환불이 제한됩니다.</p>
		<p class="ne mt0">무통장입금 시 환불은 영업일 기준 3~5일 내 처리됩니다.</p>
	</div>
	
	<div class="otit">증빙서류 발행 여부</div>
	<div class="glbox dl_slice in_inputs dt_long">
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
		
		<dl class="mt40">
			<dt>세금계산서 발행</dt>
			<dd class="radios">
				<label class="radio"><input type="radio" name="tax" value="Y" checked><i></i><span>발행</span></label>
				<label class="radio"><input type="radio" name="tax" value="N"><i></i><span>미발행</span></label>
			</dd>
		</dl>
		<div class="gbox">
			<dl>
				<dt>사업자등록번호</dt>
				<dd><input type="text" class="w1" placeholder="사업자등록번호를 입력해주세요."></dd>
			</dl>
			<dl>
				<dt>상호명</dt>
				<dd><input type="text" class="w1" placeholder="상호명을 입력해주세요."></dd>
			</dl>
			<dl>
				<dt>담당자 정보</dt>
				<dd class="colm">
					<input type="text" class="w1" placeholder="담당자명 입력해주세요.">
					<input type="text" class="w1" placeholder="이메일을 입력해주세요.">
					<input type="text" class="w1" placeholder="연락처를 입력해주세요.">
				</dd>
			</dl>
			<dl>
				<dt>사업자등록증 첨부</dt>
				<dd class="file_inputs">
					<label class="file"><input type="file"><span>파일선택</span></label>
					<div class="file_input">선택된 파일 없음</div>
				</dd>
			</dl>
		</div>
		<p class="ne">세금계산서는 입금 확인 후 3영업일 이내 이메일로 발송됩니다.</p>
	</div>

	<div class="btns_tac">
		<button type="button" class="btn btn_bwb" onclick="history.back();">취소</button>
		<button type="button" class="btn btn_wbb" onclick="location.href='/seminars_training/application_st_apply_end'">수강 신청</button>
	</div>
	
	<!-- 룸메이트 정보 있을 때 -->
	<div class="popup" id="confirmInfo">
		<div class="dm" onclick="layerHide('confirmInfo')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('confirmInfo')">닫기</button>
			<div class="tit check">입력하신 번호로 <br/>등록된 회원이 확인되었습니다.</div>
			<div class="con tac">개인정보 보호를 위해 상세 정보는 표시되지 않습니다. <br/><strong>룸메이트로 신청하시겠습니까?</strong></div>
			<div class="btns_tac">
				<button type="button" class="btn btn_bwb" onclick="layerHide('confirmInfo')">취소</button>
				<button type="button" class="btn btn_wbb" id="btnRoommateApply">룸메이트 신청</button>
			</div>
		</div>
	</div>
	
	<!-- 룸메이트 정보 없을 때 -->
	<div class="popup" id="noInfo">
		<div class="dm" onclick="layerHide('noInfo')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('noInfo')">닫기</button>
			<div class="tit exclamation">입력하신 번호로 조회되는 <br/>회원 정보가 없습니다.</div>
			<div class="con tac">룸메이트 분이 아직 회원가입을 하지 않으신 경우, <br/><strong>원활한 룸메이트 배정을 위해 회원가입을 권장드립니다.</strong></div>
			<div class="btns_tac">
				<button type="button" class="btn btn_wbb" onclick="layerHide('noInfo')">확인</button>
			</div>
		</div>
	</div>
	
</main>

@include('member.pop_search_school')

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

//팝업
function layerShow(id) {
	$("#" + id).fadeIn(300);
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
}
$("#btnRoommateApply").click(function(){
	layerHide('confirmInfo');
	$(".inbtn .ne").removeClass("hide");
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
	function toggleTax() {
		const isPublish = $('input[name="tax"]:checked').val() === "Y";
		const $box = $('input[name="tax"]').closest("dl").next(".gbox");
		const $inputs = $box.find("input");

		if (!isPublish) {
			// radio 체크 해제
			$inputs.prop("checked", false);

			// 📌 파일 초기화 + UI 원복
			$box.find(".file_inputs").each(function () {
				const $wrap = $(this);
				$wrap.find("input[type='file']").val("");
				$wrap.find(".file_input").removeClass("w100p").text("선택된 파일 없음");
			});
		}

		$inputs.prop("disabled", !isPublish);
	}

	toggleBill();
	toggleTax();
	$('input[name="bill"]').on("change", toggleBill);
	$('input[name="tax"]').on("change", toggleTax);
});
</script>

@endsection