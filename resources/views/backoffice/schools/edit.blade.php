@extends('backoffice.layouts.app')

@section('title', '학교 수정')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
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
        <a href="{{ route('backoffice.schools.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <form action="{{ route('backoffice.schools.update', $school->id) }}" method="POST" id="schoolForm">
                @csrf
                @method('PUT')

                <div class="member-form-section">
                    <h3 class="member-section-title">학교 정보</h3>
                    
                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">지회 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <select class="board-form-control @error('branch') is-invalid @enderror" 
                                        id="branch" name="branch" required>
                                    <option value="">선택</option>
                                    @foreach($branches as $key => $value)
                                        <option value="{{ $key }}" @selected(old('branch', $school->branch) == $key)>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('branch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">회원교 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="board-radio-group">
                                    <div class="board-radio-item">
                                        <input type="radio" id="is_member_school_y" name="is_member_school" value="Y" class="board-radio-input" @checked(old('is_member_school', $school->is_member_school ? 'Y' : 'N') == 'Y') required>
                                        <label for="is_member_school_y">Y</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="is_member_school_n" name="is_member_school" value="N" class="board-radio-input" @checked(old('is_member_school', $school->is_member_school ? 'Y' : 'N') == 'N')>
                                        <label for="is_member_school_n">N</label>
                                    </div>
                                </div>
                                @error('is_member_school')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">학교명 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control @error('school_name') is-invalid @enderror" 
                                       id="school_name" name="school_name" value="{{ old('school_name', $school->school_name) }}" required>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">연도 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <select class="board-form-control @error('year') is-invalid @enderror" 
                                        id="year" name="year" required>
                                    <option value="">선택</option>
                                    @foreach($years as $key => $value)
                                        <option value="{{ $key }}" @selected(old('year', $school->year) == $key)>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">URL</label>
                            <div class="member-form-field">
                                <input type="url" class="board-form-control @error('url') is-invalid @enderror" 
                                       id="url" name="url" value="{{ old('url', $school->url) }}" placeholder="https://example.com">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.schools.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const schoolForm = document.getElementById('schoolForm');
    const submitBtn = document.querySelector('button[type="submit"][form="schoolForm"]');
    const formSubmitBtn = schoolForm ? schoolForm.querySelector('button[type="submit"]') : null;
    
    console.log('학교 수정 폼 초기화');
    console.log('Form:', schoolForm);
    console.log('Header submit button:', submitBtn);
    console.log('Form submit button:', formSubmitBtn);
    
    // 헤더의 버튼이 있으면 클릭 이벤트 추가
    if (submitBtn && schoolForm) {
        submitBtn.addEventListener('click', function(e) {
            console.log('헤더 저장 버튼 클릭됨');
            console.log('Form ID:', schoolForm.id);
            console.log('Form action:', schoolForm.action);
            console.log('Form method:', schoolForm.method);
            
            // form 유효성 검사
            if (schoolForm.checkValidity()) {
                console.log('Form 유효성 검사 통과');
                schoolForm.submit();
            } else {
                console.log('Form 유효성 검사 실패');
                schoolForm.reportValidity();
            }
        });
    }
    
    // form 안의 버튼 클릭 이벤트
    if (formSubmitBtn && schoolForm) {
        formSubmitBtn.addEventListener('click', function(e) {
            console.log('Form 내부 저장 버튼 클릭됨');
        });
    }
    
    // form 제출 이벤트 리스너
    if (schoolForm) {
        schoolForm.addEventListener('submit', function(e) {
            console.log('Form 제출 시도');
            const formData = new FormData(schoolForm);
            console.log('Form 데이터:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
        });
    }
});
</script>
@endsection
