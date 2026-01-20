@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="dl_slice in_inputs member_area">
			<div class="tit">회원가입</div>
			<div class="ntit"><span>1</span>기본정보 입력 <p class="abso"><span class="c_blue_light">*</span>는 필수입력 항목 입니다.</p></div>
			<div class="inputs">
				<dl>
					<dt>이메일<span class="c_blue_light">*</span></dt>
					<dd><input type="text" class="w100p" placeholder="이메일을 입력해주세요." value="useremail@example.com" readonly></dd>
				</dl>
				<dl>
					<dt>휴대폰번호<span class="c_blue_light">*</span></dt>
					<dd><input type="text" class="w100p" placeholder="휴대폰번호를 입력해주세요." value="010-1234-5678" readonly></dd>
				</dl>
				<dl>
					<dt>이름<span class="c_blue_light">*</span></dt>
					<dd><input type="text" class="w100p" placeholder="이름을 입력해주세요. (최대 8글자)" value="홍길동" readonly></dd>
				</dl>
				<dl>
					<dt>주소</dt>
					<dd class="inbtn">
						<input type="text" class="text" placeholder="우편번호를 검색해주세요." readonly>
						<button type="button" class="btn">우편번호 검색</button>
						<input type="text" class="w1" readonly>
						<input type="text" class="w1" placeholder="상세주소를 입력해주세요.">
					</dd>
				</dl>
				<div class="sbtit">수신동의</div>
				<div class="check_area colm">
					<label class="check"><input type="checkbox"><i></i><span>카카오 알림톡 수신동의</span></label>
					<label class="check"><input type="checkbox"><i></i><span>이메일 수신동의</span></label>
				</div>
				<p class="ne">수신동의를 하지 않을 경우 관련정보(수강신청, 참석관련)를 제공받지 못할 수 있습니다.</p>
			</div>
		</div>
		
		<div class="dl_slice in_inputs member_area">
			<div class="ntit"><span>2</span>소속정보 <p class="abso"><span class="c_blue_light">*</span>는 필수입력 항목 입니다.</p></div>
			<div class="inputs">
				<dl>
					<dt>학교명<span class="c_blue_light">*</span></dt>
					<dd class="inbtn">
						<input type="text" class="input_school" placeholder="학교명을 검색해주세요." readonly>
						<button type="button" class="btn" onclick="layerShow('searchSchool')">검색</button>
					</dd>
				</dl>
				<dl>
					<dt>학교명 직접 입력</dt>
					<dd><input type="text" class="w100p" placeholder="학교명을 직접 입력해주세요."></dd>
				</dl>
				<dl>
					<dt><label class="check"><input type="checkbox"><i></i><span>협회와의 대표 소통자입니다.</span></label></dt>
				</dl>
			</div>
		</div>
		
		<div class="dl_slice in_inputs member_area">
			<div class="ntit"><span>3</span>약관동의 <p class="ne abso mt0">필수약관에 미동의 시 회원가입이 불가합니다.</p></div>
			<div class="aco_area">
				<div class="all"><label class="check"><input type="checkbox" id="allCheck"><i></i><span>약관에 모두 동의합니다.</span></label></div>
				<dl class="aco">
					<dt><label class="check"><input type="checkbox" name="check"><i></i><span><strong>(필수)</strong>개인정보 수집 및 이용에 대한 동의</span></label><button type="button" class="btn">열기</button></dt>
					<dd class="gbox">
						<div class="scroll">@include('terms.txt_privacy_policy')</div>
					</dd>
				</dl>
				<dl class="aco">
					<dt><label class="check"><input type="checkbox" name="check"><i></i><span><strong>(필수)</strong>서비스 이용약관</span></label><button type="button" class="btn">열기</button></dt>
					<dd class="gbox">
						<div class="scroll">@include('terms.txt_terms')</div>
					</dd>
				</dl>
			</div>
		</div>

		<div class="btns_tac">
			<button type="button" class="btn btn_bwb" onclick="history.back();">취소</a>
			<button type="button" class="btn btn_wbb" onclick="location.href='/member/join_end'">회원가입</button>
		</div>

	</div>
	
</main>

@include('member.pop_search_school')

<script>
var $allCheck = $('#allCheck');
$allCheck.change(function () {
    var $this = $(this);
    var checked = $this.prop('checked');
    $('input[name="check"]').prop('checked', checked);
});
var boxes = $('input[name="check"]');
boxes.change(function () {
    var boxLength = boxes.length;
    var checkedLength = $('input[name="check"]:checked').length;
    var selectallCheck = (boxLength == checkedLength);
    $allCheck.prop('checked', selectallCheck);
});

$(".aco_area .aco dt .btn").on('click', function () {
    const $aco = $(this).closest('.aco');
    const isOpen = $aco.hasClass('on');
    $aco.siblings('.aco').removeClass('on').children('dd').slideUp('fast').end().find('.btn').text('열기');
    $aco.toggleClass('on').children('dd').stop(false, true).slideToggle('fast');
    $(this).text(isOpen ? '열기' : '닫기');
});

</script>

@endsection