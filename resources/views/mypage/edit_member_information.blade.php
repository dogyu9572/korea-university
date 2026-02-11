@extends('layouts.app')
@section('content')
<main class="sub_wrap inner" @if($errors->has('secession_agreed')) data-secession-errors="1" @endif @if($errors->any()) data-join-errors="1" @endif @if(session('success')) data-success-message="{{ e(session('success')) }}" @endif>

	<div class="stitle tal bdb">회원정보 수정</div>

	<form action="{{ route('mypage.edit_member_information.update') }}" method="POST" id="mypageMemberForm" novalidate>
		@csrf
		@method('PUT')

		<div class="otit">기본정보 수정</div>
		<div class="glbox dl_slice in_inputs">
			<dl>
				<dt>이메일</dt>
				<dd><input type="text" class="w1" name="email" value="{{ old('email', $member->email) }}" readonly></dd>
				@error('email')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>현재 비밀번호</dt>
				<dd><input type="password" class="w1" name="current_password" placeholder="비밀번호 변경 시에만 입력"></dd>
				<dd id="currentPasswordCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
				@error('current_password')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>새 비밀번호</dt>
				<dd><input type="password" class="w1" name="password" placeholder="영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)"></dd>
				<dd id="passwordCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
				@error('password')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>새 비밀번호 확인</dt>
				<dd><input type="password" class="w1" name="password_confirmation" placeholder="비밀번호를 한 번 더 입력해주세요."></dd>
				<dd id="passwordConfirmCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
				@error('password_confirmation')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>휴대폰번호</dt>
				<dd><input type="text" class="w1" name="phone_number" value="{{ \App\Models\Member::formatPhoneForDisplay(old('phone_number', $member->phone_number)) }}" readonly></dd>
				@error('phone_number')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>이름</dt>
				<dd><input type="text" class="w1" name="name" value="{{ old('name', $member->name) }}" maxlength="8" readonly></dd>
				@error('name')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>주소</dt>
				<dd class="inbtn">
					<input type="text" class="text" name="address_postcode" id="address_postcode" placeholder="우편번호를 검색해주세요." value="{{ old('address_postcode', $member->address_postcode) }}" readonly>
					<button type="button" class="btn" id="btnSearchAddress">우편번호 검색</button>
					<input type="text" class="w1" name="address_base" id="address_base" value="{{ old('address_base', $member->address_base) }}" readonly>
					<input type="text" class="w1" name="address_detail" id="address_detail" placeholder="상세주소를 입력해주세요." value="{{ old('address_detail', $member->address_detail) }}">
				</dd>
			</dl>
			<dl>
				<dt>카카오 알림톡 수신동의</dt>
				<dd class="flex_aic gap16">
					<label class="check"><input type="checkbox" name="kakao_marketing_consent" value="1" @checked(old('kakao_marketing_consent', $member->kakao_marketing_consent))><i></i><span>동의</span></label>
					<p>수신동의일자: {{ $member->kakao_marketing_consent_at?->format('Y-m-d') ?? '-' }}</p>
				</dd>
			</dl>
			<dl>
				<dt>이메일 수신동의</dt>
				<dd class="flex_aic gap16">
					<label class="check"><input type="checkbox" name="email_marketing_consent" value="1" @checked(old('email_marketing_consent', $member->email_marketing_consent))><i></i><span>동의</span></label>
					<p>수신동의일자: {{ $member->email_marketing_consent_at?->format('Y-m-d') ?? '-' }}</p>
				</dd>
			</dl>
		</div>

		<div class="otit">소속정보 수정</div>
		<div class="glbox dl_slice in_inputs">
			<dl>
				<dt>소속기관</dt>
				<dd style="display:block;">
					<div class="inbtn">
						<input type="text" class="text input_school" name="school_name" id="school_name" value="{{ old('school_name', $member->school_name) }}" placeholder="학교명을 검색해주세요.">
						<button type="button" class="btn" id="btnSearchSchool">검색</button>
					</div>
					<div style="margin-top:8px;"><input type="text" class="w1" id="school_name_direct" placeholder="학교명을 직접 입력해주세요." @if(old('school_name', $member->school_name)) disabled @endif></div>
				</dd>
				<dd id="schoolNameCheckMsg" class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;display:none;"></dd>
				@error('school_name')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>학교 대표자 여부</dt>
				<dd class="flex_aic gap16">
					<label class="check"><input type="checkbox" name="is_school_representative" value="1" @checked(old('is_school_representative', $member->is_school_representative))><i></i><span>협회와의 대표 소통자입니다.</span></label>
				</dd>
				@error('is_school_representative')<dd class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
		</div>

		<div class="btns_tac">
			<button type="submit" class="btn btn_wbb">회원정보 저장하기</button>
			<button type="button" class="btn_abso" id="btnSecession">회원 탈퇴</button>
		</div>
	</form>

	<!-- 회원 탈퇴 -->
	<div class="popup" id="secession" style="display: none;">
		<div class="dm" data-action="layer-close" data-layer="secession"></div>
		<div class="inbox">
			<button type="button" class="btn_close" data-action="layer-close" data-layer="secession">닫기</button>
			<div class="tit exclamation">회원 탈퇴를 진행하시겠습니까?</div>
			<p class="tac"><strong>탈퇴 시 회원의 모든 정보가 삭제되며 복구가 불가합니다.</strong><br/>다시 이용하려면 신규가입이 필요합니다.</p>
			<form action="{{ route('mypage.secession') }}" method="POST" id="secessionForm">
				@csrf
				<div class="con gbox flex_center mt">
					<label class="check"><input type="checkbox" name="secession_agreed" value="1"><i></i><span>위의 내용을 모두 읽었으며, 내용에 동의합니다.</span></label>
				</div>
				@error('secession_agreed')<p class="join_field_error" style="color:#c00;font-size:1rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
				<div class="btns_tac">
					<button type="submit" class="btn btn_bwb">탈퇴하기</button>
				</div>
			</form>
		</div>
	</div>

</main>

@include('member.pop_search_school')

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="{{ asset('js/mypage/edit-member-information.js') }}?v={{ filemtime(public_path('js/mypage/edit-member-information.js')) }}"></script>
@endsection
