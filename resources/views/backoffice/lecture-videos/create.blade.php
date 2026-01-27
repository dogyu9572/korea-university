@extends('backoffice.layouts.app')

@section('title', '강의 영상 등록')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/members.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/lecture-videos.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/lecture-videos.js') }}"></script>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success board-hidden-alert">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger board-hidden-alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="board-alert board-alert-danger">
        <ul>
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.lecture-videos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>
    <div class="board-card">
        <div class="board-card-body">
            @include('backoffice.lecture-videos._form', ['lectureVideo' => new \App\Models\LectureVideo()])
        </div>
    </div>
</div>
@endsection
