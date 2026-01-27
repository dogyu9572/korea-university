@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '교육 안내 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/education.css') }}">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('js/backoffice/education.js') }}"></script>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success board-hidden-alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger board-hidden-alert">
        {{ session('error') }}
    </div>
@endif

<div class="board-container">
    <div class="board-card">
        <div class="board-card-body">
            <form action="{{ route('backoffice.education.update') }}" method="POST" id="educationForm">
                @csrf

                <div class="board-form-group">
                    <label for="education_guide" class="board-form-label">교육 안내</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="education_guide" name="education_guide" rows="6">{{ old('education_guide', $contents->education_guide ?? '') }}</textarea>
                    @error('education_guide')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="certification_guide" class="board-form-label">자격증 안내</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="certification_guide" name="certification_guide" rows="6">{{ old('certification_guide', $contents->certification_guide ?? '') }}</textarea>
                    @error('certification_guide')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="expert_level_1" class="board-form-label">대학연구행정전문가 1급</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="expert_level_1" name="expert_level_1" rows="6">{{ old('expert_level_1', $contents->expert_level_1 ?? '') }}</textarea>
                    @error('expert_level_1')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="expert_level_2" class="board-form-label">대학연구행정전문가 2급</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="expert_level_2" name="expert_level_2" rows="6">{{ old('expert_level_2', $contents->expert_level_2 ?? '') }}</textarea>
                    @error('expert_level_2')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="seminar_guide" class="board-form-label">세미나 안내</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="seminar_guide" name="seminar_guide" rows="6">{{ old('seminar_guide', $contents->seminar_guide ?? '') }}</textarea>
                    @error('seminar_guide')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="overseas_training_guide" class="board-form-label">해외연수 안내</label>
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="overseas_training_guide" name="overseas_training_guide" rows="6">{{ old('overseas_training_guide', $contents->overseas_training_guide ?? '') }}</textarea>
                    @error('overseas_training_guide')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">저장</button>
                    <a href="{{ route('backoffice.dashboard') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
