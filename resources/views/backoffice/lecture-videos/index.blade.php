@extends('backoffice.layouts.app')

@section('title', '강의 영상 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/lecture-videos.css') }}">
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
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.lecture-videos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 신규등록
            </a>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.lecture-videos.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="period_start" class="filter-label">교육기간 시작</label>
                            <input type="date" id="period_start" name="period_start" class="filter-input" value="{{ request('period_start') }}">
                        </div>
                        <div class="filter-group">
                            <label for="period_end" class="filter-label">교육기간 끝</label>
                            <input type="date" id="period_end" name="period_end" class="filter-input" value="{{ request('period_end') }}">
                        </div>
                        <div class="filter-group">
                            <label for="search_type" class="filter-label">검색어</label>
                            <select id="search_type" name="search_type" class="filter-select">
                                <option value="전체" @selected(request('search_type', '전체') == '전체')>전체</option>
                                <option value="강의제목" @selected(request('search_type') == '강의제목')>강의제목</option>
                                <option value="강사명" @selected(request('search_type') == '강사명')>강사명</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="search_term" class="filter-label">검색어</label>
                            <input type="text" id="search_term" name="search_term" class="filter-input" value="{{ request('search_term') }}">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.lecture-videos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ $lectureVideos->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.lecture-videos.index') }}" class="per-page-form">
                        @foreach (['period_start', 'period_end', 'search_type', 'search_term'] as $key)
                            <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
                        @endforeach
                        <label for="per_page" class="per-page-label">표시 개수:</label>
                        <select name="per_page" id="per_page" class="per-page-select" onchange="this.form.submit()">
                            <option value="10" @selected(request('per_page', 20) == 10)>10개</option>
                            <option value="20" @selected(request('per_page', 20) == 20)>20개</option>
                            <option value="50" @selected(request('per_page', 20) == 50)>50개</option>
                            <option value="100" @selected(request('per_page', 20) == 100)>100개</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>썸네일</th>
                            <th>강의 제목</th>
                            <th>강의시간(분)</th>
                            <th>강사명</th>
                            <th>강의 사용여부</th>
                            <th>등록일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lectureVideos as $index => $video)
                            <tr>
                                <td>{{ $lectureVideos->firstItem() + $index }}</td>
                                <td>
                                    @if ($video->thumbnail_path)
                                        <img src="{{ $video->thumbnail_path }}" alt="썸네일" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-left">{{ $video->title }}</td>
                                <td>{{ $video->lecture_time }}</td>
                                <td>{{ $video->instructor_name }}</td>
                                <td>{{ $video->is_active ? 'Y' : 'N' }}</td>
                                <td>{{ $video->created_at->format('Y.m.d') }}</td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.lecture-videos.edit', $video) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.lecture-videos.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> 삭제
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">등록된 강의 영상이 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$lectureVideos" />
        </div>
    </div>
</div>
@endsection
