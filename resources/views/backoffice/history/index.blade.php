@extends('backoffice.layouts.app')

@section('title', '연혁 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/history.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/backoffice/history.js') }}"></script>
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
            <!-- 검색 섹션 -->
            <div class="history-search-section">
                <form action="{{ route('backoffice.history.index') }}" method="GET" id="searchForm">
                    <div class="board-form-row">
                        <div class="board-form-group">
                            <label class="board-form-label">연도</label>
                            <select name="year" class="board-form-control">
                                <option value="전체" @selected($year == '전체')>전체</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}" @selected($year == $y)>{{ $y }}년</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">내용</label>
                            <input type="text" name="search" class="board-form-control" value="{{ $search }}" placeholder="내용 검색">
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> 검색
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 등록 섹션 -->
            <div class="history-register-section">
                <h3 class="board-form-label">연혁 등록</h3>
                <form id="addHistoryForm">
                    <div class="board-form-row">
                        <div class="board-form-group">
                            <label class="board-form-label">연도</label>
                            <input type="text" id="yearInput" class="board-form-control" readonly>
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">시작일</label>
                            <input type="date" id="dateInput" name="date" class="board-form-control" required>
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">종료일</label>
                            <input type="date" id="dateEndInput" name="date_end" class="board-form-control" placeholder="미입력 시 하루">
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">제목</label>
                            <input type="text" id="titleInput" name="title" class="board-form-control" placeholder="제목 (선택)">
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">내용</label>
                            <input type="text" id="contentInput" name="content" class="board-form-control" required placeholder="연혁 내용을 입력하세요">
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">노출여부</label>
                            <select id="isVisibleInput" name="is_visible" class="board-form-control" required>
                                <option value="Y">Y</option>
                                <option value="N">N</option>
                            </select>
                        </div>
                        <div class="board-form-group">
                            <label class="board-form-label">&nbsp;</label>
                            <button type="button" id="btnAddHistory" class="btn btn-success">
                                <i class="fas fa-plus"></i> 추가
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 목록 섹션 -->
            <div class="history-list-section">
                <div class="history-list-header">
                    <span class="total-count">총 {{ $histories->count() }}건</span>
                </div>
                <table class="board-table history-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>연도</th>
                            <th>기간</th>
                            <th>제목</th>
                            <th>내용</th>
                            <th>노출여부</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        @forelse($histories as $index => $history)
                            <tr data-id="{{ $history->id }}">
                                <td>{{ $histories->count() - $index }}</td>
                                <td>{{ $history->year }}년</td>
                                <td>
                                    <div><input type="date" class="board-form-control form-control-sm history-date" name="date" value="{{ $history->date->format('Y-m-d') }}"></div>
                                    <div class="mt-1"><input type="date" class="board-form-control form-control-sm history-date-end" name="date_end" value="{{ $history->date_end?->format('Y-m-d') }}"></div>
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm history-title" name="title" value="{{ $history->title ?? '' }}" placeholder="제목">
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm history-content" name="content" value="{{ $history->content }}">
                                </td>
                                <td>
                                    <select class="board-form-control form-control-sm history-is-visible" name="is_visible">
                                        <option value="Y" @selected($history->is_visible == 'Y')>Y</option>
                                        <option value="N" @selected($history->is_visible == 'N')>N</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="board-btn-group">
                                        <button type="button" class="btn btn-primary btn-sm btn-save-history" data-id="{{ $history->id }}">
                                            <i class="fas fa-edit"></i> 수정
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-history" data-id="{{ $history->id }}">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7" class="text-center">등록된 연혁이 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
