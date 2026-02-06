@extends('backoffice.layouts.app')

@section('title', '회원 문의')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/backoffice/inquiries.js') }}"></script>
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
            <button type="button" id="btnDeleteMultiple" class="btn btn-danger">
                <i class="fas fa-trash"></i> 선택삭제
            </button>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.inquiries.index') }}" class="filter-form" id="searchForm">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="category" class="filter-label">분류</label>
                            <select id="category" name="category" class="filter-select">
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" @selected($filters['category'] == $key)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">답변상태</label>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="status_all" name="status" value="전체" class="board-radio-input" @checked($filters['status'] == '전체' || $filters['status'] == '')>
                                    <label for="status_all">전체</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="status_completed" name="status" value="답변완료" class="board-radio-input" @checked($filters['status'] == '답변완료')>
                                    <label for="status_completed">답변완료</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="status_pending" name="status" value="답변대기" class="board-radio-input" @checked($filters['status'] == '답변대기')>
                                    <label for="status_pending">답변대기</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-group">
                            <label for="start_date" class="filter-label">등록일</label>
                            <div class="date-range">
                                <input type="date" id="start_date" name="start_date" class="filter-input"
                                    value="{{ $filters['start_date'] ?? '' }}">
                                <span class="date-separator">~</span>
                                <input type="date" id="end_date" name="end_date" class="filter-input"
                                    value="{{ $filters['end_date'] ?? '' }}">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label for="search_type" class="filter-label">검색 구분</label>
                            <select id="search_type" name="search_type" class="filter-select">
                                <option value="전체" @selected($filters['search_type'] == '전체')>전체</option>
                                <option value="제목" @selected($filters['search_type'] == '제목')>제목</option>
                                <option value="소속명" @selected($filters['search_type'] == '소속명')>소속명</option>
                                <option value="작성자" @selected($filters['search_type'] == '작성자')>작성자</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="search_term" class="filter-label">검색어</label>
                            <input type="text" id="search_term" name="search_term" class="filter-input"
                                placeholder="검색어를 입력하세요" value="{{ $filters['search_term'] ?? '' }}">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.inquiries.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 목록 개수 선택 -->
            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ $inquiries->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.inquiries.index') }}" class="per-page-form">
                        <input type="hidden" name="category" value="{{ $filters['category'] ?? '' }}">
                        <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
                        <input type="hidden" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                        <input type="hidden" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                        <input type="hidden" name="search_type" value="{{ $filters['search_type'] ?? '' }}">
                        <input type="hidden" name="search_term" value="{{ $filters['search_term'] ?? '' }}">
                        <label for="per_page" class="per-page-label">표시 개수:</label>
                        <select name="per_page" id="per_page" class="per-page-select" onchange="this.form.submit()">
                            <option value="20" @selected($perPage == 20)>20개</option>
                            <option value="50" @selected($perPage == 50)>50개</option>
                            <option value="100" @selected($perPage == 100)>100개</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th class="w5 board-checkbox-column">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th>No</th>
                            <th>분류</th>
                            <th>제목</th>
                            <th>소속명</th>
                            <th>작성자(이름)</th>
                            <th>답변상태</th>
                            <th>등록일</th>
                            <th>조회수</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $index => $inquiry)
                            <tr data-id="{{ $inquiry->id }}">
                                <td>
                                    <input type="checkbox" name="selected_inquiries[]" value="{{ $inquiry->id }}" class="form-check-input inquiry-checkbox">
                                </td>
                                <td>{{ $inquiries->total() - ($inquiries->currentPage() - 1) * $inquiries->perPage() - $index }}</td>
                                <td>{{ $inquiry->category }}</td>
                                <td class="text-left">{{ $inquiry->title }}</td>
                                <td>{{ $inquiry->member->school_name ?? '-' }}</td>
                                <td>{{ $inquiry->member->name ?? '-' }}</td>
                                <td>
                                    @if($inquiry->status === '답변완료')
                                        <span class="badge badge-success">답변완료</span>
                                    @else
                                        <span class="badge badge-warning">답변대기</span>
                                    @endif
                                </td>
                                <td>{{ $inquiry->created_at->format('Y.m.d') }}</td>
                                <td>{{ $inquiry->views }}</td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.inquiries.show', $inquiry->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.inquiries.destroy', $inquiry->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
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
                                <td colspan="10" class="text-center">등록된 문의가 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$inquiries" />
        </div>
    </div>
</div>
@endsection
