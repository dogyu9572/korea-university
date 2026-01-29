@extends('backoffice.layouts.app')

@section('title', '자격증 신청내역')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/education-applications.css') }}">
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
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.certification-applications.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="education_class" class="filter-label">구분</label>
                            <select id="education_class" name="education_class" class="filter-select">
                                <option value="">전체</option>
                                <option value="1급 자격증" @selected(request('education_class') == '1급 자격증')>1급 자격증</option>
                                <option value="2급 자격증" @selected(request('education_class') == '2급 자격증')>2급 자격증</option>
                            </select>
                        </div>
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
                            <label for="period_start" class="filter-label">시험일 시작</label>
                            <input type="date" id="period_start" name="period_start" class="filter-input" value="{{ request('period_start') }}">
                        </div>
                        <div class="filter-group">
                            <label for="period_end" class="filter-label">시험일 끝</label>
                            <input type="date" id="period_end" name="period_end" class="filter-input" value="{{ request('period_end') }}">
                        </div>
                        <div class="filter-group">
                            <label for="name" class="filter-label">자격증명</label>
                            <input type="text" id="name" name="name" class="filter-input" value="{{ request('name') }}" placeholder="자격증명을 입력하세요">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.certification-applications.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ $programs->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.certification-applications.index') }}" class="per-page-form" id="perPageForm">
                        <input type="hidden" name="education_class" value="{{ request('education_class') }}">
                        <input type="hidden" name="application_status" value="{{ request('application_status') }}">
                        <input type="hidden" name="period_start" value="{{ request('period_start') }}">
                        <input type="hidden" name="period_end" value="{{ request('period_end') }}">
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
                            <th>구분</th>
                            <th>접수상태</th>
                            <th>교육유형</th>
                            <th>자격증명</th>
                            <th>시험일</th>
                            <th>정원</th>
                            <th>신청인원</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $index => $program)
                            <tr>
                                <td>{{ $programs->firstItem() + $index }}</td>
                                <td>{{ $program->education_class ?? '-' }}</td>
                                <td>{{ $program->application_status }}</td>
                                <td>-</td>
                                <td class="text-left">{{ $program->name }}</td>
                                <td>
                                    @if($program->period_start)
                                        @php
                                            $dow = ['일','월','화','수','목','금','토'];
                                        @endphp
                                        {{ $program->period_start->format('Y.m.d') }}({{ $dow[$program->period_start->dayOfWeek] ?? '-' }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($program->capacity_unlimited ?? false)
                                        제한없음
                                    @else
                                        {{ $program->capacity ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $program->enrolled_count ?? 0 }}</td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.certification-applications.show', $program) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> 보기
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">등록된 자격증 신청내역이 없습니다.</td>
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
