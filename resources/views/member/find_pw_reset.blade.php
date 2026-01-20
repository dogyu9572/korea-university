@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">비밀번호 재설정</div>
			<div class="member_area end_area icon_lock">
		
				<div class="tt">새로운 비밀번호를 설정해 주세요.</div>
				
				<div class="inputs">
					<div class="btn_set">
						<input type="password" class="text h56 w100p pw" placeholder="새 비밀번호">
						<button type="button" class="btn_trans_text">인풋 문구 보기</button>
					</div>
					<div class="btn_set">
						<input type="password" class="text h56 w100p pw" placeholder="새 비밀번호 확인">
						<button type="button" class="btn_trans_text">인풋 문구 보기</button>
					</div>
					<p class="ne gray tal">영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)</p>
				</div>
				
				<div class="btns_tac colm mt40">
					<button type="button" class="btn btn_wbb" onclick="location.href='/member/find_pw_end'">비밀번호 변경</button>
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