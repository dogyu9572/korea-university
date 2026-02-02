@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<form action="{{ route('member.login.post') }}" method="POST" id="memberLoginForm">
			@csrf
			<div class="member_area">
				<div class="tit">LOGIN</div>
				<div class="inputs">
					@if (session('success'))<p class="join_field_error" style="color:#0a0;font-size:0.875rem;margin-bottom:0.25rem;">{{ session('success') }}</p>@endif
					@error('email')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-bottom:0.25rem;">{{ $message }}</p>@enderror
					<input type="text" name="email" class="text h56 w100p" placeholder="이메일을 입력해주세요." value="{{ old('email', $remembered_email_value ?? '') }}" autocomplete="username">
					<div class="btn_set">
						<input type="password" name="password" class="text h56 w100p pw" placeholder="비밀번호를 입력해주세요." autocomplete="current-password">
						<button type="button" class="btn_trans_text">인풋 문구 보기</button>
					</div>
					@error('password')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
					<div class="btm">
						<label class="check"><input type="checkbox" name="remember" value="1" @checked(old('remember', !empty($remembered_email_value)))><i></i><span>이메일 저장</span></label>
						<div class="btns">
							<a href="{{ route('member.find_id') }}" class="btn">아이디 찾기</a>
							<a href="{{ route('member.find_pw') }}" class="btn">비밀번호 찾기</a>
						</div>
					</div>
					<div class="btns_btm flex colm mt40">
						<button type="submit" class="btn btn_wbb">로그인</button>
						<a href="{{ route('member.join') }}" class="btn btn_bwb">회원가입</a>
					</div>
					<div class="sns mt40">
						<div class="tt flex_center"><span>SNS 로그인</span></div>
						<div class="btns flex_center">
							<a href="{{ route('member.join_easy') }}" class="btn naver"></a>
							<a href="{{ route('member.join_easy') }}" class="btn kakao"></a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	
</main>

<script src="{{ asset('js/member-login.js') }}"></script>
@endsection