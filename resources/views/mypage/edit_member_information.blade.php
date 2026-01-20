@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="stitle tal bdb">회원정보 수정</div>
    
	<div class="otit">기본정보 수정</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>이메일</dt>
			<dd><input type="text" class="w1" value="useremail@example.com" readonly></dd>
		</dl>
		<dl>
			<dt>현재 비밀번호</dt>
			<dd><input type="password" class="w1"></dd>
		</dl>
		<dl>
			<dt>새 비밀번호</dt>
			<dd><input type="password" class="w1" placeholder="영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)"></dd>
		</dl>
		<dl>
			<dt>새 비밀번호 확인</dt>
			<dd><input type="password" class="w1" placeholder="비밀번호를 한 번 더 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>휴대폰번호</dt>
			<dd><input type="text" class="w1" value="010-1234-5678" readonly></dd>
		</dl>
		<dl>
			<dt>이름</dt>
			<dd><input type="text" class="w1" value="홍길동" readonly></dd>
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
		<dl>
			<dt>카카오 알림톡 수신동의</dt>
			<dd class="flex_aic gap16">
				<label class="check"><input type="checkbox"><i></i><span>동의</span></label>
				<p>수신동의일자: YYYY-MM-DD</p>
			</dd>
		</dl>
		<dl>
			<dt>이메일 수신동의</dt>
			<dd class="flex_aic gap16">
				<label class="check"><input type="checkbox"><i></i><span>동의</span></label>
				<p>수신동의일자: YYYY-MM-DD</p>
			</dd>
		</dl>
	</div>

	<div class="otit">소속정보 수정</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>소속기관</dt>
			<dd class="inbtn">
				<input type="text" class="text" value="학교명을 검색해주세요." readonly>
				<button type="button" class="btn" onclick="layerShow('searchSchool')">검색</button>
				<input type="text" class="w1" placeholder="학교명을 직접 입력해주세요.">
			</dd>
		</dl>
		<dl>
			<dt>학교 대표자 여부</dt>
			<dd class="flex_aic gap16">
				<label class="check"><input type="checkbox"><i></i><span>협회와의 대표 소통자입니다. </span></label>
			</dd>
		</dl>
	</div>

	<div class="btns_tac">
		<button type="button" class="btn btn_wbb">회원정보 저장하기</button>
		<button type="button" class="btn_abso" onclick="layerShow('secession')">회원 탈퇴</button>
	</div>
	
	<!-- 회원 탈퇴 -->
	<div class="popup" id="secession">
		<div class="dm" onclick="layerHide('secession')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('secession')">닫기</button>
			<div class="tit exclamation">회원 탈퇴를 진행하시겠습니까?</div>
			<p class="tac"><strong>탈퇴 시 회원의 모든 정보가 삭제되며 복구가 불가합니다.</strong><br/>다시 이용하려면 신규가입이 필요합니다.</p>
			<div class="con gbox flex_center mt">
				<label class="check"><input type="checkbox"><i></i><span>위의 내용을 모두 읽었으며, 내용에 동의합니다.</span></label>
			</div>
			<div class="btns_tac">
				<button type="button" class="btn btn_bwb">탈퇴하기</button>
			</div>
		</div>
	</div>
	
</main>

@include('member.pop_search_school')

<script>
//팝업
function layerShow(id) {
	$("#" + id).fadeIn(300);
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
}
</script>

@endsection