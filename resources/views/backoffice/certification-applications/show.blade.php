@extends('backoffice.layouts.app')

@section('title', '자격증 신청내역(상세)')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/members.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/education-programs.css') }}">
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

@php
    $dow = ['일','월','화','수','목','금','토'];
@endphp
<div class="board-container education-applications certification-applications" data-program-id="{{ $program->id }}" data-base-path="/backoffice/certification-applications" data-print-base-url="{{ url('/backoffice/print') }}" data-csrf-token="{{ csrf_token() }}">
    <div class="board-header">
        <a href="{{ route('backoffice.certification-applications.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <form id="saveForm" method="POST" action="{{ route('backoffice.certification-applications.update-status', $program) }}">
        @csrf
        @method('PUT')

        <div class="board-card">
            <div class="board-card-body">
                <div class="member-form-section">
                    <h3 class="member-section-title">자격증 정보</h3>

                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">구분</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->education_class ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">자격증명</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->name }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">시험일</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    @if($program->period_start)
                                        {{ $program->period_start->format('Y.m.d') }}({{ $dow[$program->period_start->dayOfWeek] ?? '-' }})
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">정원</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    @if($program->capacity_unlimited ?? false)
                                        제한없음
                                    @else
                                        {{ $program->capacity ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">신청 인원</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    자동 입력 ({{ $program->applications()->whereNull('cancelled_at')->count() }}명)
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">접수상태</label>
                            <div class="member-form-field">
                                <select name="application_status" id="application_status" class="board-form-control select-full-width" data-auto-save-url="{{ route('backoffice.certification-applications.update-status', $program) }}">
                                    <option value="접수중" @selected($program->application_status == '접수중')>접수중</option>
                                    <option value="접수마감" @selected($program->application_status == '접수마감')>접수마감</option>
                                    <option value="접수예정" @selected($program->application_status == '접수예정')>접수예정</option>
                                    <option value="비공개" @selected($program->application_status == '비공개')>비공개</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

        <div class="board-card" id="applications-list">
            <div class="board-card-body">
                <div class="member-form-section">
                    <h3 class="member-section-title">2. 신청 명단</h3>

                    <div class="board-filter member-search-modal-filter">
                        <form method="GET" action="{{ route('backoffice.certification-applications.show', $program) }}" class="filter-form">
                            <div class="filter-row">
                                <div class="filter-group">
                                    <label for="payment_status" class="filter-label">결제상태</label>
                                    <select id="payment_status" name="payment_status" class="filter-select">
                                        <option value="전체" @selected(request('payment_status', '전체') == '전체')>전체</option>
                                        <option value="미입금" @selected(request('payment_status') == '미입금')>미입금</option>
                                        <option value="입금완료" @selected(request('payment_status') == '입금완료')>입금완료</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label for="search" class="filter-label">검색</label>
                                    <input type="text" id="search" name="search" class="filter-input" value="{{ request('search') }}" placeholder="신청자명, 아이디">
                                </div>
                                <div class="filter-group">
                                    <div class="filter-buttons">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> 검색
                                        </button>
                                        <a href="{{ route('backoffice.certification-applications.show', $program) }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> 초기화
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="action-buttons-right">
                        <button type="button" class="btn btn-success btn-sm" data-action="batch-save-scores">
                            <i class="fas fa-save"></i> 성적 저장
                        </button>
                        <a href="{{ route('backoffice.certification-applications.export.get', $program) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-file-excel"></i> 엑셀 다운로드
                        </a>
                        <a href="{{ route('backoffice.certification-applications.create', ['program' => $program->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 신청 추가
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="board-table certification-application-list-table">
                            <colgroup>
                                <col style="width: 4%">
                                <col style="width: 8%">
                                <col style="width: 8%">
                                <col style="width: 6%">
                                <col style="width: 7%">
                                <col style="width: 6%">
                                <col style="width: 6%">
                                <col style="width: 8%">
                                <col style="width: 6%">
                                <col style="width: 5%">
                                <col style="width: 6%">
                                <col style="width: 5%">
                                <col style="width: 5%">
                                <col style="width: 5%">
                                <col style="width: 5%">
                                <col style="width: 8%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>신청번호</th>
                                    <th>학교명</th>
                                    <th>신청자명</th>
                                    <th>신청자 ID</th>
                                    <th>휴대폰 번호</th>
                                    <th>결제상태</th>
                                    <th>신청일시</th>
                                    <th>세금계산서</th>
                                    <th>성적</th>
                                    <th>합격여부</th>
                                    <th>수험표 발급</th>
                                    <th>합격확인서 발급</th>
                                    <th>자격증 발급</th>
                                    <th>영수증 발급</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $index => $application)
                                    <tr>
                                        <td>{{ $applications->total() - ($applications->firstItem() + $index) + 1 }}</td>
                                        <td>{{ $application->application_number }}</td>
                                        <td>{{ $application->affiliation ?? '' }}</td>
                                        <td>{{ $application->applicant_name }}</td>
                                        <td>{{ $application->member->login_id ?? '' }}</td>
                                        <td>{{ $application->phone_number ?? '' }}</td>
                                        <td>{{ $application->payment_status ?? '' }}</td>
                                        <td>{{ $application->application_date ? $application->application_date->format('Y.m.d H:i') : '' }}</td>
                                        <td>{{ $application->tax_invoice_status ?? '' }}</td>
                                        <td>
                                            <input type="number" class="board-form-control score-input" data-application-id="{{ $application->id }}" value="{{ $application->score !== null ? $application->score : '' }}" min="0" step="1" style="width: 4.5rem;">
                                        </td>
                                        <td>{{ $application->score !== null ? ($application->qualification_display_status ?? '') : '' }}</td>
                                        <td>
                                            @if($application->certification_id)
                                            <button type="button" class="btn btn-sm btn-success" data-action="issue-exam-ticket" data-application-id="{{ $application->id }}">
                                                발급
                                            </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->is_qualification_passed)
                                            <button type="button" class="btn btn-sm btn-success" data-action="issue-pass-confirmation" data-application-id="{{ $application->id }}">
                                                발급
                                            </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->is_qualification_passed)
                                            <button type="button" class="btn btn-sm btn-success" data-action="issue-certificate" data-application-id="{{ $application->id }}">
                                                발급
                                            </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->payment_status === '입금완료')
                                            <button type="button" class="btn btn-sm btn-info" data-action="issue-receipt" data-application-id="{{ $application->id }}">
                                                발급
                                            </button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="board-btn-group">
                                                <a href="{{ route('backoffice.certification-applications.edit', $application) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> 수정
                                                </a>
                                                <form action="{{ route('backoffice.certification-applications.destroy', $application) }}" method="POST" class="d-inline" data-action="confirm-delete">
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
                                        <td colspan="16" class="text-center">등록된 신청 내역이 없습니다.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <x-pagination :paginator="$applications" />
                </div>
            </div>
        </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/education-applications.js') }}"></script>
<script src="{{ asset('js/backoffice/certification-applications.js') }}"></script>
<script>
(function() {
    var params = new URLSearchParams(location.search);
    if (params.has('payment_status') || params.has('search')) {
        var el = document.getElementById('applications-list');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
    }

    var select = document.getElementById('application_status');
    var form = document.getElementById('saveForm');
    if (select && form) {
        var url = select.getAttribute('data-auto-save-url');
        var token = form.querySelector('input[name="_token"]');
        select.addEventListener('change', function() {
            var body = new FormData();
            body.append('_token', token ? token.value : '');
            body.append('_method', 'PUT');
            body.append('application_status', select.value);
            fetch(url, {
                method: 'POST',
                body: body,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.success && data.message) {
                    var alert = document.querySelector('.board-hidden-alert.alert-success');
                    if (alert) {
                        alert.textContent = data.message;
                        alert.classList.remove('board-hidden-alert');
                        alert.style.display = 'block';
                        setTimeout(function() { alert.style.display = 'none'; }, 3000);
                    } else {
                        var div = document.createElement('div');
                        div.className = 'alert alert-success';
                        div.textContent = data.message;
                        document.querySelector('.board-container').insertBefore(div, document.querySelector('.board-container').firstChild);
                        setTimeout(function() { div.remove(); }, 3000);
                    }
                }
            })
            .catch(function() {
                alert('접수상태 저장에 실패했습니다.');
            });
        });
    }
})();
</script>
@endsection
