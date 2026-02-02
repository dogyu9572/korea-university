@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">

	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">아이디/비밀번호 찾기</div>
			<div class="member_area">

				<div class="tabs">
					<a href="{{ route('member.find_id') }}">아이디 찾기</a>
					<a href="{{ route('member.find_pw') }}" class="on">비밀번호 찾기</a>
				</div>

				<div class="gbox tac">SNS로 가입한 계정은<br/><strong class="c_blue_light">각 SNS 홈페이지에서 비밀번호 찾기</strong>를 진행해주세요.</div>

				<form action="{{ route('member.find_pw.post') }}" method="POST" id="findPwForm">
					@csrf
					<div class="inputs">
						<input type="text" name="email" class="text w100p" placeholder="이메일을 입력해 주세요." value="{{ old('email') }}" autocomplete="email">
						@error('email')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
						<input type="text" name="phone_number" class="text w100p" placeholder="휴대폰번호를 입력해 주세요." value="{{ \App\Models\Member::formatPhoneForDisplay(old('phone_number')) }}" autocomplete="tel">
						@error('phone_number')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
						<input type="text" name="name" class="text w100p" placeholder="이름을 입력해주세요." value="{{ old('name') }}" maxlength="8" autocomplete="name">
						@error('name')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
					</div>

					<div class="btns_tac colm mt40">
						<button type="submit" class="btn btn_wbb">확인</button>
					</div>
				</form>
			</div>
			<p class="ne gray mt32">아이디를 찾을 수 없는 경우 063-220-3194로 문의해주시기 바랍니다.</p>
			<p class="ne gray mt0">SNS 간편가입(카카오/네이버) 계정은 비밀번호를 재설정할 수 없습니다.</p>
		</div>
	</div>

</main>
@endsection
