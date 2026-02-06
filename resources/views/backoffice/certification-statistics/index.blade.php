@extends('backoffice.layouts.app')

@section('title', '자격증 통계')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.certification-statistics.export', request()->query()) }}" class="btn btn-secondary">
                <i class="fas fa-file-excel"></i> 엑셀 다운로드
            </a>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.certification-statistics') }}" class="filter-form">
                    <div class="filter-row">
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
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.certification-statistics') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ count($rows) }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>자격증명</th>
                            <th>신청인원(건)</th>
                            <th>접수취소/반려 건수(건)</th>
                            <th>합격인원(건)</th>
                            <th>불합격인원(건)</th>
                            <th>입금현황 총 금액(원)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                        <tr>
                            <td class="text-left">{{ $row['certification_name'] ?? '' }}</td>
                            <td>{{ number_format($row['application_count'] ?? 0) }}</td>
                            <td>{{ number_format($row['cancelled_count'] ?? 0) }}</td>
                            <td>{{ number_format($row['pass_count'] ?? 0) }}</td>
                            <td>{{ number_format($row['fail_count'] ?? 0) }}</td>
                            <td>{{ number_format((float)($row['total_payment'] ?? 0)) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">조회된 데이터가 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
