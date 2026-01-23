@extends('backoffice.layouts.app')

@section('title', '회원 등록')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/members.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="{{ asset('js/backoffice/members.js') }}"></script>
<script>
// 가입 구분에 따라 비밀번호 필드 표시/숨김
document.addEventListener('DOMContentLoaded', function() {
    const joinTypeInputs = document.querySelectorAll('input[name="join_type"]');
    const passwordRow = document.getElementById('passwordGroup');
    const passwordConfirmationRow = document.getElementById('passwordConfirmationGroup');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    function togglePasswordFields() {
        const selectedType = document.querySelector('input[name="join_type"]:checked')?.value;
        if (selectedType === 'email') {
            if (passwordRow) passwordRow.style.display = '';
            if (passwordConfirmationRow) passwordConfirmationRow.style.display = '';
            if (passwordInput) passwordInput.required = true;
            if (passwordConfirmationInput) passwordConfirmationInput.required = true;
        } else {
            if (passwordRow) passwordRow.style.display = 'none';
            if (passwordConfirmationRow) passwordConfirmationRow.style.display = 'none';
            if (passwordInput) {
                passwordInput.required = false;
                passwordInput.value = '';
            }
            if (passwordConfirmationInput) {
                passwordConfirmationInput.required = false;
                passwordConfirmationInput.value = '';
            }
        }
    }
    
    joinTypeInputs.forEach(input => {
        input.addEventListener('change', togglePasswordFields);
    });
    
    // 초기 상태 설정
    togglePasswordFields();
    
    // Form 제출 디버깅
    const memberForm = document.getElementById('memberForm');
    const formSubmitBtn = memberForm ? memberForm.querySelector('button[type="submit"]') : null;
    
    console.log('회원 등록 폼 초기화');
    console.log('Form:', memberForm);
    console.log('Form submit button:', formSubmitBtn);
    
    // form 안의 버튼 클릭 이벤트
    if (formSubmitBtn && memberForm) {
        formSubmitBtn.addEventListener('click', function(e) {
            console.log('Form 내부 저장 버튼 클릭됨');
        });
    }
    
    // form 제출 이벤트 리스너
    if (memberForm) {
        memberForm.addEventListener('submit', function(e) {
            console.log('Form 제출 시도');
            const formData = new FormData(memberForm);
            console.log('Form 데이터:');
            for (let [key, value] of formData.entries()) {
                // 비밀번호는 로그에 표시하지 않음
                if (key === 'password' || key === 'password_confirmation') {
                    console.log(key + ':', '***');
                } else {
                    console.log(key + ':', value);
                }
            }
        });
    }
});
</script>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger board-hidden-alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.members.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <form action="{{ route('backoffice.members.store') }}" method="POST" id="memberForm">
                @csrf

                <!-- 기본 정보 -->
                <div class="member-form-section">
                    <h3 class="member-section-title">기본 정보</h3>
                    
                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">가입 구분 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="board-radio-group">
                                    <div class="board-radio-item">
                                        <input type="radio" id="join_type_email" name="join_type" value="email" class="board-radio-input" @checked(old('join_type') == 'email') required>
                                        <label for="join_type_email">이메일</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="join_type_kakao" name="join_type" value="kakao" class="board-radio-input" @checked(old('join_type') == 'kakao')>
                                        <label for="join_type_kakao">카카오</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="join_type_naver" name="join_type" value="naver" class="board-radio-input" @checked(old('join_type') == 'naver')>
                                        <label for="join_type_naver">네이버</label>
                                    </div>
                                </div>
                                @error('join_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">아이디 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control @error('login_id') is-invalid @enderror" 
                                       id="login_id" name="login_id" value="{{ old('login_id') }}" required>
                                @error('login_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row" id="passwordGroup">
                            <label class="member-form-label">비밀번호 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="password" class="board-form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="영문/숫자/특수문자 조합 두가지 이상(8~10자 이내 입력)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row" id="passwordConfirmationGroup">
                            <label class="member-form-label">비밀번호 확인 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="password" class="board-form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" placeholder="비밀번호를 한 번 더 입력해주세요.">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">이름 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" maxlength="8" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">휴대폰번호 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="input-with-button">
                                    <input type="text" class="board-form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCheckPhone">중복확인</button>
                                </div>
                                <div id="phoneCheckResult" class="check-result"></div>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">이메일</label>
                            <div class="member-form-field">
                                <div class="input-with-button">
                                    <input type="email" class="board-form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCheckEmail">중복확인</button>
                                </div>
                                <div id="emailCheckResult" class="check-result"></div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">주소</label>
                            <div class="member-form-field">
                                <div class="input-with-button" style="margin-bottom: 10px;">
                                    <input type="text" class="board-form-control" id="address_postcode" name="address_postcode" 
                                           value="{{ old('address_postcode') }}" placeholder="우편번호를 검색해주세요." readonly>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnSearchAddress">우편번호 검색</button>
                                </div>
                                <input type="text" class="board-form-control" id="address_base" name="address_base" 
                                       value="{{ old('address_base') }}" placeholder="기본주소" readonly style="margin-bottom: 10px;">
                                <input type="text" class="board-form-control" id="address_detail" name="address_detail" 
                                       value="{{ old('address_detail') }}" placeholder="상세주소를 입력해주세요.">
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">학교명 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="input-with-button" style="margin-bottom: 10px;">
                                    <input type="text" class="board-form-control @error('school_name') is-invalid @enderror" 
                                           id="school_name" name="school_name" value="{{ old('school_name') }}" required placeholder="학교명을 검색해주세요.">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnSearchSchool">검색</button>
                                </div>
                                <input type="text" class="board-form-control" id="school_name_direct" 
                                       placeholder="학교명을 직접 입력해주세요." style="max-width: 400px;">
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">학교 대표자</label>
                            <div class="member-form-field">
                                <div class="board-radio-group">
                                    <div class="board-radio-item">
                                        <input type="radio" id="is_school_representative_y" name="is_school_representative" value="1" class="board-radio-input" @checked(old('is_school_representative') == '1')>
                                        <label for="is_school_representative_y">Y</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="is_school_representative_n" name="is_school_representative" value="0" class="board-radio-input" @checked(old('is_school_representative', '0') == '0')>
                                        <label for="is_school_representative_n">N</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">수신동의</label>
                            <div class="member-form-field">
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="email_marketing_consent" value="1" @checked(old('email_marketing_consent'))>
                                        <span>이메일 수신동의</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="kakao_marketing_consent" value="1" @checked(old('kakao_marketing_consent'))>
                                        <span>카카오 알림톡 수신동의</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.members.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

@include('member.pop_search_school')
@endsection
