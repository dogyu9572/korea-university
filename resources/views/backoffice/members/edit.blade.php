@extends('backoffice.layouts.app')

@section('title', '회원 정보 수정')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/members.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
// edit 페이지에서 비밀번호 필드 강제 표시 (즉시 실행)
(function() {
    function forceShowPasswordFields() {
        const passwordGroup = document.getElementById('passwordGroup');
        const passwordConfirmationGroup = document.getElementById('passwordConfirmationGroup');
        if (passwordGroup) {
            passwordGroup.style.display = 'block';
            passwordGroup.style.visibility = 'visible';
        }
        if (passwordConfirmationGroup) {
            passwordConfirmationGroup.style.display = 'block';
            passwordConfirmationGroup.style.visibility = 'visible';
        }
    }
    
    // 즉시 실행
    forceShowPasswordFields();
    
    // DOMContentLoaded 후에도 실행
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', forceShowPasswordFields);
    } else {
        forceShowPasswordFields();
    }
    
    // jQuery 로드 후에도 실행
    if (typeof jQuery !== 'undefined') {
        jQuery(function($) {
            forceShowPasswordFields();
            // 다른 스크립트가 숨기려고 하면 다시 표시
            setInterval(function() {
                if ($('#passwordGroup').is(':hidden') || $('#passwordGroup').css('display') === 'none') {
                    forceShowPasswordFields();
                }
            }, 100);
        });
    }
})();
</script>
<script src="{{ asset('js/backoffice/members.js') }}"></script>
<script>
// 비밀번호 변경 검증
document.addEventListener('DOMContentLoaded', function() {
    const memberForm = document.getElementById('memberForm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('passwordError');
    const passwordConfirmationError = document.getElementById('passwordConfirmationError');
    
    // Form 제출 디버깅
    console.log('회원 수정 폼 초기화');
    console.log('Form:', memberForm);
    
    const formSubmitBtn = memberForm ? memberForm.querySelector('button[type="submit"]') : null;
    const btnMemberFormSubmit = document.getElementById('btnMemberFormSubmit');
    console.log('Form submit button:', formSubmitBtn || btnMemberFormSubmit);

    // 저장 버튼이 폼 밖에 있을 때: 클릭 시 폼 제출(검증 이벤트 포함)
    if (btnMemberFormSubmit && memberForm) {
        btnMemberFormSubmit.addEventListener('click', function() {
            memberForm.requestSubmit();
        });
    }
    
    // 비밀번호 입력 시 실시간 검증
    if (passwordInput && passwordConfirmationInput) {
        passwordInput.addEventListener('input', validatePassword);
        passwordConfirmationInput.addEventListener('input', validatePassword);
        
        // 폼 제출 시 검증
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
                
                if (!validatePasswordOnSubmit()) {
                    console.log('비밀번호 유효성 검사 실패');
                    e.preventDefault();
                    return false;
                }
                console.log('비밀번호 유효성 검사 통과');
            });
        }
    }
    
    function validatePassword() {
        const password = passwordInput.value.trim();
        const passwordConfirmation = passwordConfirmationInput.value.trim();
        
        // 비밀번호가 비어있으면 검증하지 않음
        if (!password) {
            passwordError.style.display = 'none';
            passwordConfirmationError.style.display = 'none';
            passwordInput.classList.remove('is-invalid');
            passwordConfirmationInput.classList.remove('is-invalid');
            return true;
        }
        
        // 비밀번호가 입력되었을 때만 검증
        let isValid = true;
        
        // 비밀번호 형식 검증 (영문/숫자/특수문자 조합 두가지 이상, 8~10자)
        if (password.length < 8 || password.length > 10) {
            passwordError.textContent = '비밀번호는 8~10자 이내로 입력해주세요.';
            passwordError.style.display = 'block';
            passwordInput.classList.add('is-invalid');
            isValid = false;
        } else {
            // 영문, 숫자, 특수문자 중 2가지 이상 포함 확인
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const typeCount = [hasLetter, hasNumber, hasSpecial].filter(Boolean).length;
            
            if (typeCount < 2) {
                passwordError.textContent = '영문/숫자/특수문자 조합 두가지 이상을 입력해주세요.';
                passwordError.style.display = 'block';
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordError.style.display = 'none';
                passwordInput.classList.remove('is-invalid');
            }
        }
        
        // 비밀번호 확인 검증
        if (password && !passwordConfirmation) {
            passwordConfirmationError.textContent = '비밀번호 확인을 입력해주세요.';
            passwordConfirmationError.style.display = 'block';
            passwordConfirmationInput.classList.add('is-invalid');
            isValid = false;
        } else if (password && password !== passwordConfirmation) {
            passwordConfirmationError.textContent = '비밀번호가 일치하지 않습니다.';
            passwordConfirmationError.style.display = 'block';
            passwordConfirmationInput.classList.add('is-invalid');
            isValid = false;
        } else if (password && password === passwordConfirmation) {
            passwordConfirmationError.style.display = 'none';
            passwordConfirmationInput.classList.remove('is-invalid');
        }
        
        return isValid;
    }
    
    function validatePasswordOnSubmit() {
        const password = passwordInput.value.trim();
        const passwordConfirmation = passwordConfirmationInput.value.trim();
        
        // 비밀번호가 비어있으면 검증 통과 (변경하지 않음)
        if (!password && !passwordConfirmation) {
            return true;
        }
        
        // 비밀번호가 입력되었을 때만 검증
        if (password) {
            // 비밀번호 형식 검증
            if (password.length < 8 || password.length > 10) {
                alert('비밀번호는 8~10자 이내로 입력해주세요.');
                passwordInput.focus();
                return false;
            }
            
            // 영문, 숫자, 특수문자 중 2가지 이상 포함 확인
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const typeCount = [hasLetter, hasNumber, hasSpecial].filter(Boolean).length;
            
            if (typeCount < 2) {
                alert('영문/숫자/특수문자 조합 두가지 이상을 입력해주세요.');
                passwordInput.focus();
                return false;
            }
            
            // 비밀번호 확인 검증
            if (!passwordConfirmation) {
                alert('비밀번호 확인을 입력해주세요.');
                passwordConfirmationInput.focus();
                return false;
            }
            
            if (password !== passwordConfirmation) {
                alert('비밀번호가 일치하지 않습니다.');
                passwordConfirmationInput.focus();
                return false;
            }
        } else if (passwordConfirmation) {
            // 비밀번호는 없는데 비밀번호 확인만 있는 경우
            alert('비밀번호를 입력해주세요.');
            passwordInput.focus();
            return false;
        }
        
        return true;
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
            <form action="{{ route('backoffice.members.update', $member->id) }}" method="POST" id="memberForm">
                @csrf
                @method('PUT')

                <!-- 기본 정보 -->
                <div class="member-form-section">
                    <h3 class="member-section-title">기본 정보</h3>
                    
                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">가입 구분</label>
                            <div class="member-form-field">
                                <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; display: inline-block;">
                                    @if($member->join_type === 'email')
                                        이메일
                                    @elseif($member->join_type === 'kakao')
                                        카카오
                                    @else
                                        네이버
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">아이디</label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control" 
                                       id="login_id" value="{{ $member->login_id }}" readonly style="background-color: #f8f9fa;">
                            </div>
                        </div>

                        <div class="member-form-row" id="passwordGroup">
                            <label class="member-form-label">비밀번호</label>
                            <div class="member-form-field">
                                <input type="password" class="board-form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="변경 시에만 입력해주세요.">
                                <small class="form-text text-muted" style="display: block; margin-top: 5px; font-size: 12px;">비밀번호를 변경하지 않으려면 비워두세요.</small>
                                <div id="passwordError" class="invalid-feedback" style="display: none;"></div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row" id="passwordConfirmationGroup">
                            <label class="member-form-label">비밀번호 확인</label>
                            <div class="member-form-field">
                                <input type="password" class="board-form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" placeholder="변경 시에만 입력해주세요.">
                                <div id="passwordConfirmationError" class="invalid-feedback" style="display: none;"></div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">이름 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $member->name) }}" maxlength="8" required>
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
                                           id="phone_number" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}" required>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCheckPhone" data-exclude-id="{{ $member->id }}">중복확인</button>
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
                                           id="email" name="email" value="{{ old('email', $member->email) }}">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCheckEmail" data-exclude-id="{{ $member->id }}">중복확인</button>
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
                                           value="{{ old('address_postcode', $member->address_postcode) }}" placeholder="우편번호를 검색해주세요." readonly>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnSearchAddress">우편번호 검색</button>
                                </div>
                                <input type="text" class="board-form-control" id="address_base" name="address_base" 
                                       value="{{ old('address_base', $member->address_base) }}" placeholder="기본주소" readonly style="margin-bottom: 10px;">
                                <input type="text" class="board-form-control" id="address_detail" name="address_detail" 
                                       value="{{ old('address_detail', $member->address_detail) }}" placeholder="상세주소를 입력해주세요.">
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">학교명 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="input-with-button" style="margin-bottom: 10px;">
                                    <input type="text" class="board-form-control @error('school_name') is-invalid @enderror" 
                                           id="school_name" name="school_name" value="{{ old('school_name', $member->school_name) }}" required placeholder="학교명을 검색해주세요.">
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
                                        <input type="radio" id="is_school_representative_y" name="is_school_representative" value="1" class="board-radio-input" @checked(old('is_school_representative', $member->is_school_representative) == 1)>
                                        <label for="is_school_representative_y">Y</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="is_school_representative_n" name="is_school_representative" value="0" class="board-radio-input" @checked(old('is_school_representative', $member->is_school_representative) == 0)>
                                        <label for="is_school_representative_n">N</label>
                                    </div>
                                </div>
                                @error('is_school_representative')
                                    <div class="invalid-feedback" style="display:block;color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">가입일시</label>
                            <div class="member-form-field">
                                <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; display: inline-block;">
                                    {{ $member->created_at->format('Y.m.d H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">수신동의</label>
                            <div class="member-form-field">
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="email_marketing_consent" value="1" @checked(old('email_marketing_consent', $member->email_marketing_consent))>
                                        <span>이메일 수신동의</span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="kakao_marketing_consent" value="1" @checked(old('kakao_marketing_consent', $member->kakao_marketing_consent))>
                                        <span>카카오 알림톡 수신동의</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="board-form-actions">
                <button type="button" id="btnMemberFormSubmit" class="btn btn-primary">
                    <i class="fas fa-save"></i> 저장
                </button>
                <a href="{{ route('backoffice.members.index') }}" class="btn btn-secondary">취소</a>
                <form action="{{ route('backoffice.members.destroy', $member->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('정말로 삭제하시겠습니까?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> 삭제
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('member.pop_search_school')
@endsection
