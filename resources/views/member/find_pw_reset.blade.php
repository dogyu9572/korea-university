@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">

	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">비밀번호 재설정</div>
			<div class="member_area end_area icon_lock">

				<div class="tt">새로운 비밀번호를 설정해 주세요.</div>

				<form action="{{ route('member.find_pw_reset.post') }}" method="POST" id="findPwResetForm">
					@csrf
					<div class="inputs">
						<div class="btn_set">
							<input type="password" name="password" class="text h56 w100p pw" placeholder="새 비밀번호" autocomplete="new-password">
							<button type="button" class="btn_trans_text">인풋 문구 보기</button>
						</div>
						@error('password')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
						<div class="btn_set">
							<input type="password" name="password_confirmation" class="text h56 w100p pw" placeholder="새 비밀번호 확인" autocomplete="new-password">
							<button type="button" class="btn_trans_text">인풋 문구 보기</button>
						</div>
						@error('password_confirmation')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
						<p class="ne gray tal">영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)</p>
					</div>

					<div class="btns_tac colm mt40">
						<button type="submit" class="btn btn_wbb">비밀번호 변경</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</main>

<script src="{{ asset('js/member-login.js') }}"></script>
@endsection
