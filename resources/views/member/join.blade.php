@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">

	<div class="inner" @if($errors->any()) data-join-errors="1" @endif>
		<form action="{{ route('member.join.store') }}" method="POST" id="joinForm" novalidate>
			@csrf
			@if($snsJoinData ?? null)
			<input type="hidden" name="join_type" value="{{ $snsJoinData['join_type'] }}">
			<input type="hidden" name="login_id" value="{{ $snsJoinData['login_id'] }}">
			@endif
			<input type="hidden" name="email_verified" id="email_verified" value="{{ old('email_verified', '0') }}">
			<input type="hidden" name="phone_verified" id="phone_verified" value="{{ old('phone_verified', '0') }}">
			@error('join_type')<p class="join_field_error" style="color:#c00;font-size:1rem;margin-bottom:0.5rem;">{{ $message }}</p>@enderror

			<div class="dl_slice in_inputs member_area">
				<div class="tit">회원가입</div>
				<div class="ntit"><span>1</span>기본정보 입력 <p class="abso"><span class="c_blue_light">*</span>는 필수입력 항목 입니다.</p></div>
				<div class="inputs">
					<dl>
						<dt>이메일<span class="c_blue_light">*</span></dt>
						<dd class="inbtn">
							<input type="text" name="email" placeholder="이메일을 입력해주세요." value="{{ old('email', ($snsJoinData ?? [])['email'] ?? '') }}" required>
							<button type="button" class="btn" id="btnCheckEmail">중복확인</button>
						</dd>
						<dd id="emailCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
						@error('email')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
						@error('email_verified')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					@if(!($snsJoinData ?? null))
					<dl>
						<dt>비밀번호<span class="c_blue_light">*</span></dt>
						<dd><input type="password" name="password" class="w100p" placeholder="영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)" value="{{ old('password') }}" required></dd>
						<dd id="passwordCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
						@error('password')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					<dl>
						<dt>비밀번호 확인<span class="c_blue_light">*</span></dt>
						<dd><input type="password" name="password_confirmation" class="w100p" placeholder="비밀번호를 한 번 더 입력해주세요." value="{{ old('password_confirmation') }}" required></dd>
						<dd id="passwordConfirmCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
						@error('password_confirmation')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					@endif
					<dl>
						<dt>휴대폰번호<span class="c_blue_light">*</span></dt>
						<dd class="inbtn">
							<input type="text" name="phone_number" placeholder="휴대폰번호를 입력해주세요." value="{{ \App\Models\Member::formatPhoneForDisplay(old('phone_number', ($snsJoinData ?? [])['phone_number'] ?? '')) }}" required>
							<button type="button" class="btn" id="btnCheckPhone">중복확인</button>
						</dd>
						<dd id="phoneCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
						@error('phone_number')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
						@error('phone_verified')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					<dl>
						<dt>이름<span class="c_blue_light">*</span></dt>
						<dd><input type="text" name="name" class="w100p" placeholder="이름을 입력해주세요. (최대 8글자)" value="{{ old('name', ($snsJoinData ?? [])['name'] ?? '') }}" maxlength="8" required></dd>
						@error('name')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					<dl>
						<dt>주소</dt>
						<dd class="inbtn">
							<input type="text" name="address_postcode" class="text" placeholder="우편번호를 검색해주세요." value="{{ old('address_postcode') }}" readonly id="address_postcode">
							<button type="button" class="btn" id="btnSearchAddress">우편번호 검색</button>
							<input type="text" name="address_base" class="w1" value="{{ old('address_base') }}" readonly id="address_base">
							<input type="text" name="address_detail" class="w1" placeholder="상세주소를 입력해주세요." value="{{ old('address_detail') }}" id="address_detail">
						</dd>
					</dl>
					<div class="sbtit">수신동의</div>
					<div class="check_area colm">
						<label class="check"><input type="checkbox" name="kakao_marketing_consent" value="1" @checked(old('kakao_marketing_consent'))><i></i><span>카카오 알림톡 수신동의</span></label>
						<label class="check"><input type="checkbox" name="email_marketing_consent" value="1" @checked(old('email_marketing_consent'))><i></i><span>이메일 수신동의</span></label>
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
							<input type="text" name="school_name" class="input_school" placeholder="학교명을 검색해주세요." value="{{ old('school_name') }}" required id="school_name">
							<button type="button" class="btn" onclick="layerShow('searchSchool')">검색</button>
						</dd>
												<dd><input type="text" class="w100p" placeholder="학교명을 직접 입력해주세요." id="school_name_direct" @if(old('school_name')) disabled @endif></dd>
						<dd id="schoolNameCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
						@error('school_name')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
					<dl>
						<dt><label class="check"><input type="checkbox" name="is_school_representative" value="1" @checked(old('is_school_representative'))><i></i><span>협회와의 대표 소통자입니다.</span></label></dt>
						@error('is_school_representative')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
					</dl>
				</div>
			</div>

			<div class="dl_slice in_inputs member_area" data-join-section="terms">
				<div class="ntit"><span>3</span>약관동의 <p class="ne abso mt0">필수약관에 미동의 시 회원가입이 불가합니다.</p></div>
				<div class="aco_area">
					<div class="all"><label class="check"><input type="checkbox" id="allCheck"><i></i><span>약관에 모두 동의합니다.</span></label></div>
					<dl class="aco">
						<dt><label class="check"><input type="checkbox" name="terms_privacy" value="1" @checked(old('terms_privacy'))><i></i><span><strong>(필수)</strong>개인정보 수집 및 이용에 대한 동의</span></label><button type="button" class="btn">열기</button></dt>
						<dd class="gbox">
							<div class="scroll">@include('terms.txt_privacy_policy')</div>
						</dd>
					</dl>
					<dl class="aco">
						<dt><label class="check"><input type="checkbox" name="terms_service" value="1" @checked(old('terms_service'))><i></i><span><strong>(필수)</strong>서비스 이용약관</span></label><button type="button" class="btn">열기</button></dt>
						<dd class="gbox">
							<div class="scroll">@include('terms.txt_terms')</div>
						</dd>
					</dl>
				</div>
				<p id="termsCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></p>
				@error('terms_privacy')<p class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
				@error('terms_service')<p class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
			</div>

			<div class="btns_tac">
				<button type="button" class="btn btn_bwb" onclick="history.back();">취소</button>
				<button type="submit" class="btn btn_wbb">회원가입</button>
			</div>
		</form>

	</div>

</main>

@include('member.pop_search_school')

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="{{ asset('js/member-join.js') }}?v={{ filemtime(public_path('js/member-join.js')) }}"></script>
@endsection
