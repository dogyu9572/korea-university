@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">아이디/비밀번호 찾기</div>
			<div class="member_area">
				
				<div class="tabs">
					<a href="/member/find_id" class="on">아이디 찾기</a>
					<a href="/member/find_pw">비밀번호 찾기</a>
				</div>
		
				<div class="gbox tac">SNS로 가입한 계정은<br/><strong class="c_blue_light">각 SNS 홈페이지에서 아이디 찾기</strong>를 진행해주세요.</div>
		
				<div class="inputs">
					<input type="text" class="text w100p number_set" placeholder="휴대폰번호를 입력해 주세요. (숫자만 입력 가능)">
					<input type="text" class="text w100p" placeholder="이름을 입력해주세요.">
				</div>
				
				<div class="btns_tac colm mt40">
					<button type="button" class="btn btn_wbb" onclick="location.href='/member/find_id_end'">확인</button>
				</div>
			</div>
			<p class="ne gray mt32">아이디를 찾을 수 없는 경우 063-220-3194로 문의해주시기 바랍니다.</p>
		</div>
	</div>
	
</main>

<script>
$('.number_set').on('input', function () {
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>

@endsection