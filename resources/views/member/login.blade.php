@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="member_area">
			<div class="tit">LOGIN</div>
			<div class="inputs">
				<input type="text" class="text h56 w100p" placeholder="이메일을 입력해주세요.">
				<div class="btn_set">
					<input type="password" class="text h56 w100p pw" placeholder="비밀번호를 입력해주세요.">
					<button type="button" class="btn_trans_text">인풋 문구 보기</button>
				</div>
				<div class="btm">
					<label class="check"><input type="checkbox"><i></i><span>이메일 저장</span></label>
					<div class="btns">
						<a href="/member/find_id" class="btn">아이디 찾기</a>
						<a href="/member/find_pw" class="btn">비밀번호 찾기</a>
					</div>
				</div>
				<div class="btns_btm flex colm mt40">
					<button class="btn btn_wbb">로그인</button>
					<a href="/member/join" class="btn btn_bwb">회원가입</a>
				</div>
				<div class="sns mt40">
					<div class="tt flex_center"><span>SNS 로그인</span></div>
					<div class="btns flex_center">
						<a href="/member/join_easy" class="btn naver"></a>
						<a href="/member/join_easy" class="btn kakao"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</main>

<script>
$('.btn_trans_text').on('click', function () {
	const $input = $(this).siblings('input');
	if ($input.attr('type') === 'password') {
		$input.attr('type', 'text').parent().addClass("on");
		$(this).text('인풋 문구 숨기기');
	} else {
		$input.attr('type', 'password').parent().removeClass("on");
		$(this).text('인풋 문구 보기');
	}
});
$('.pw').on('input', function () {
	this.value = this.value.replace(/[^a-zA-Z0-9!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]/g, '');
});
</script>

@endsection