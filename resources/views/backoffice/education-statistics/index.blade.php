@extends('backoffice.layouts.app')

@section('title', '교육 통계')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.education-statistics.export', request()->query()) }}" class="btn btn-secondary">
                <i class="fas fa-file-excel"></i> 엑셀 다운로드
            </a>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.education-statistics') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="category" class="filter-label">구분</label>
                            <select id="category" name="category" class="filter-select">
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['category'] ?? '') === (string)$value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="year" class="filter-label">연도</label>
                            <select id="year" name="year" class="filter-select">
                                @foreach($years as $y)
                                    <option value="{{ $y }}" @selected(($filters['year'] ?? now()->year) == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="month" class="filter-label">월</label>
                            <select id="month" name="month" class="filter-select">
                                @foreach($months as $m => $label)
                                    <option value="{{ $m }}" @selected(($filters['month'] ?? now()->month) == $m)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="start_date" class="filter-label">기간(신청일) 시작</label>
                            <input type="date" id="start_date" name="start_date" class="filter-input" value="{{ isset($filters['start_date']) ? \Carbon\Carbon::parse($filters['start_date'])->format('Y-m-d') : '' }}">
                        </div>
                        <div class="filter-group">
                            <label for="end_date" class="filter-label">기간(신청일) 끝</label>
                            <input type="date" id="end_date" name="end_date" class="filter-input" value="{{ isset($filters['end_date']) ? \Carbon\Carbon::parse($filters['end_date'])->format('Y-m-d') : '' }}">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">
                                <input type="checkbox" name="fiscal_year" value="1" @checked($filters['fiscal_year'] ?? false)> 회계년도 조회
                            </label>
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.education-statistics') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 목록 개수 -->
            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ count($rows) }}</span>
                </div>
            </div>

            <!-- 결과 테이블 -->
            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>구분</th>
                            <th>교육구분</th>
                            <th>교육명</th>
                            <th>신청건수(건)</th>
                            <th>접수취소/반려 건수(건)</th>
                            <th>수료/이수 건 수(건)</th>
                            <th>입금현황 총 금액(원)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['category_label'] ?? '' }}</td>
                            <td>{{ $row['education_type_label'] ?? '' }}</td>
                            <td class="text-left">{{ $row['program_name'] ?? '' }}</td>
                            <td>{{ number_format($row['application_count'] ?? 0) }}</td>
                            <td>{{ number_format($row['cancelled_count'] ?? 0) }}</td>
                            <td>{{ number_format($row['completed_count'] ?? 0) }}</td>
                            <td>{{ number_format((float)($row['total_payment'] ?? 0)) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">조회된 데이터가 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
