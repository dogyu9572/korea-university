@extends('backoffice.layouts.app')

@section('title', '세미나/해외연수 목록')

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
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.seminar-trainings.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 신규등록
            </a>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.seminar-trainings.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="education_type" class="filter-label">구분</label>
                            <select id="education_type" name="education_type" class="filter-select">
                                <option value="">전체</option>
                                <option value="세미나" @selected(request('education_type') == '세미나')>세미나</option>
                                <option value="해외연수" @selected(request('education_type') == '해외연수')>해외연수</option>
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
                            <input type="text" id="name" name="name" class="filter-input" value="{{ request('name') }}">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.seminar-trainings.index') }}" class="btn btn-secondary">
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
                    <form method="GET" action="{{ route('backoffice.seminar-trainings.index') }}" class="per-page-form">
                        @foreach (['education_type', 'application_status', 'period_start', 'period_end', 'application_start', 'application_end', 'name'] as $key)
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
                            <th>구분</th>
                            <th>공개여부</th>
                            <th>접수상태</th>
                            <th>세미나/해외연수명</th>
                            <th>교육기간</th>
                            <th>접수기간</th>
                            <th>정원</th>
                            <th>결제방법</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $index => $p)
                            <tr>
                                <td>{{ $programs->firstItem() + $index }}</td>
                                <td>{{ $p->education_type }}</td>
                                <td>{{ $p->is_public ? '공개' : '비공개' }}</td>
                                <td>{{ $p->application_status }}</td>
                                <td class="text-left">{{ $p->name }}</td>
                                <td>
                                    @if ($p->period_start && $p->period_end)
                                        {{ $p->period_start->format('Y.m.d') }} ~ {{ $p->period_end->format('Y.m.d') }}
                                    @elseif ($p->period_start)
                                        {{ $p->period_start->format('Y.m.d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($p->application_start && $p->application_end)
                                        {{ $p->application_start->format('Y.m.d H:i') }} ~ {{ $p->application_end->format('Y.m.d H:i') }}
                                    @elseif ($p->application_start)
                                        {{ $p->application_start->format('Y.m.d H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($p->capacity_unlimited)
                                        제한없음
                                    @else
                                        {{ $p->capacity ?? '-' }}명
                                    @endif
                                </td>
                                <td>
                                    @if ($p->payment_methods && is_array($p->payment_methods))
                                        {{ implode(', ', $p->payment_methods) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.seminar-trainings.edit', $p) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.seminar-trainings.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 삭제하시겠습니까?');">
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
                                <td colspan="10" class="text-center">등록된 세미나/해외연수가 없습니다.</td>
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
