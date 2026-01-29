@extends('backoffice.layouts.app')

@section('title', '교육 신청내역')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
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
            <!-- 검색 필터 -->
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.education-applications.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="application_status" class="filter-label">접수상태</label>
                            <select id="application_status" name="application_status" class="filter-select">
                                <option value="">전체</option>
                                <option value="접수중" @selected(request('application_status') == '접수중')>접수중</option>
                                <option value="접수마감" @selected(request('application_status') == '접수마감')>접수마감</option>
                                <option value="접수예정" @selected(request('application_status') == '접수예정')>접수예정</option>
                                <option value="비공개" @selected(request('application_status') == '비공개')>비공개</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="education_type" class="filter-label">교육유형</label>
                            <select id="education_type" name="education_type" class="filter-select">
                                <option value="">전체</option>
                                <option value="정기교육" @selected(request('education_type') == '정기교육')>정기교육</option>
                                <option value="수시교육" @selected(request('education_type') == '수시교육')>수시교육</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="period_start" class="filter-label">교육기간 시작</label>
                            <input type="date" id="period_start" name="period_start" class="filter-input" value="{{ request('period_start') }}">
                        </div>
                        <div class="filter-group">
                            <label for="period_end" class="filter-label">교육기간 끝</label>
                            <input type="date" id="period_end" name="period_end" class="filter-input" value="{{ request('period_end') }}">
                        </div>
                        <div class="filter-group">
                            <label for="application_start" class="filter-label">신청기간 시작</label>
                            <input type="date" id="application_start" name="application_start" class="filter-input" value="{{ request('application_start') }}">
                        </div>
                        <div class="filter-group">
                            <label for="application_end" class="filter-label">신청기간 끝</label>
                            <input type="date" id="application_end" name="application_end" class="filter-input" value="{{ request('application_end') }}">
                        </div>
                        <div class="filter-group">
                            <label for="name" class="filter-label">교육명</label>
                            <input type="text" id="name" name="name" class="filter-input" value="{{ request('name') }}" placeholder="교육명을 입력하세요">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.education-applications.index') }}" class="btn btn-secondary">
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
                    <span class="list-count">Total : {{ $programs->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.education-applications.index') }}" class="per-page-form" id="perPageForm">
                        <input type="hidden" name="application_status" value="{{ request('application_status') }}">
                        <input type="hidden" name="education_type" value="{{ request('education_type') }}">
                        <input type="hidden" name="period_start" value="{{ request('period_start') }}">
                        <input type="hidden" name="period_end" value="{{ request('period_end') }}">
                        <input type="hidden" name="application_start" value="{{ request('application_start') }}">
                        <input type="hidden" name="application_end" value="{{ request('application_end') }}">
                        <input type="hidden" name="name" value="{{ request('name') }}">
                        <label for="per_page" class="per-page-label">표시 개수:</label>
                        <select name="per_page" id="per_page" class="per-page-select" data-action="change-per-page">
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
                            <th>교육구분</th>
                            <th>접수상태</th>
                            <th>교육유형</th>
                            <th>교육명</th>
                            <th>교육기간</th>
                            <th>정원</th>
                            <th>수강인원</th>
                            <th>교육장소</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $index => $program)
                            <tr>
                                <td>{{ $programs->firstItem() + $index }}</td>
                                <td>{{ $program->education_class ?? '-' }}</td>
                                <td>{{ $program->application_status }}</td>
                                <td>{{ $program->education_type }}</td>
                                <td class="text-left">{{ $program->name }}</td>
                                <td>
                                    @if($program->period_start && $program->period_end)
                                        {{ $program->period_start->format('Y.m.d') }} ~ {{ $program->period_end->format('Y.m.d') }}
                                    @elseif($program->period_start)
                                        {{ $program->period_start->format('Y.m.d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($program->capacity_unlimited)
                                        제한없음
                                    @else
                                        {{ $program->capacity ?? '-' }}명
                                    @endif
                                </td>
                                <td>{{ $program->enrolled_count ?? 0 }}명</td>
                                <td>{{ $program->location ?? '-' }}</td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.education-applications.show', $program) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> 보기
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">등록된 교육 신청내역이 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$programs" />
        </div>
    </div>
</div>
@endsection
