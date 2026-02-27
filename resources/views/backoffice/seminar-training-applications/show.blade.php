@extends('backoffice.layouts.app')

@section('title', '세미나/해외연수 신청내역(개인/상세)')

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

<div class="board-container education-applications" data-program-id="{{ $program->id }}" data-base-path="/backoffice/seminar-training-applications">
    <div class="board-header">
        <a href="{{ route('backoffice.seminar-training-applications.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <form id="saveForm" method="POST" action="{{ route('backoffice.seminar-training-applications.update-status', $program) }}">
        @csrf
        @method('PUT')

        <!-- 교육 정보 (PN35: 구분, 세미나/해외연수명, 교육기간, 교육장소, 교육대상, 정원, 수강 인원, 접수상태) -->
        <div class="board-card">
            <div class="board-card-body">
                <div class="member-form-section">
                    <h3 class="member-section-title">교육 정보</h3>

                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">구분</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->education_type ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">세미나/해외연수명</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->name }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">교육기간</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    @if($program->period_start && $program->period_end)
                                        {{ $program->period_start->format('Y.m.d') }} ~ {{ $program->period_end->format('Y.m.d') }}
                                    @elseif($program->period_start)
                                        {{ $program->period_start->format('Y.m.d') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">교육장소</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->location ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">교육대상</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->target ?? '-' }}
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
                            <label class="member-form-label">수강 인원</label>
                            <div class="member-form-field">
                                <div class="board-form-control readonly-field-full">
                                    {{ $program->applications()->whereNull('cancelled_at')->count() }}명
                                </div>
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">접수상태</label>
                            <div class="member-form-field">
                                <select name="application_status" id="application_status" class="board-form-control select-full-width" data-auto-save-url="{{ route('backoffice.seminar-training-applications.update-status', $program) }}">
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

        <!-- 신청 명단 (PN35: No, 신청번호, 학교명, 신청자명, 신청자 ID, 휴대폰 번호, 룸메이트, 세금계산서, 결제상태, 신청일시, 이수 여부, 이수증/수료증 발급, 영수증 발급, 관리) -->
        <div class="board-card" id="applications-list">
            <div class="board-card-body">
                <div class="member-form-section">
                    <h3 class="member-section-title">신청 명단</h3>

                    <div class="board-filter member-search-modal-filter">
                        <form method="GET" action="{{ route('backoffice.seminar-training-applications.show', $program) }}" class="filter-form">
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
                                    <label for="affiliation" class="filter-label">학교명</label>
                                    <input type="text" id="affiliation" name="affiliation" class="filter-input" value="{{ request('affiliation') }}" placeholder="학교명">
                                </div>
                                <div class="filter-group">
                                    <label for="accommodation" class="filter-label">숙박형태</label>
                                    <select id="accommodation" name="accommodation" class="filter-select">
                                        <option value="">전체</option>
                                        <option value="2인1실" @selected(request('accommodation') == '2인1실')>2인1실</option>
                                        <option value="1인실" @selected(request('accommodation') == '1인실')>1인실</option>
                                        <option value="비숙박" @selected(request('accommodation') == '비숙박')>비숙박</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label for="receipt_status" class="filter-label">접수상태</label>
                                    <select id="receipt_status" name="receipt_status" class="filter-select">
                                        <option value="">전체</option>
                                        <option value="신청완료" @selected(request('receipt_status') == '신청완료')>신청완료</option>
                                        <option value="수료" @selected(request('receipt_status') == '수료')>수료</option>
                                        <option value="미수료" @selected(request('receipt_status') == '미수료')>미수료</option>
                                        <option value="접수취소" @selected(request('receipt_status') == '접수취소')>접수취소</option>
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
                                        <a href="{{ route('backoffice.seminar-training-applications.show', $program) }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> 초기화
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="action-buttons-right">
                        <button type="button" class="btn btn-success btn-sm" data-action="batch-payment-complete">
                            <i class="fas fa-check"></i> 일괄 입금완료
                        </button>
                        <button type="button" class="btn btn-info btn-sm" data-action="batch-complete">
                            <i class="fas fa-check-circle"></i> 일괄 이수
                        </button>
                        <a href="{{ route('backoffice.seminar-training-applications.export.get', ['program' => $program->id]) }}" class="btn btn-secondary btn-sm" data-action="excel-download">
                            <i class="fas fa-file-excel"></i> 엑셀 다운로드
                        </a>
                        <a href="{{ route('backoffice.seminar-training-applications.create', ['program' => $program->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 신청 추가
                        </a>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="board-table online-application-list-table" style="min-width: 1600px;">
                            <colgroup>
                                <col style="width: 3%">
                                <col style="width: 4%">
                                <col style="width: 8%">
                                <col style="width: 7%">
                                <col style="width: 6%">
                                <col style="width: 8%">
                                <col style="width: 8%">
                                <col style="width: 6%">
                                <col style="width: 6%">
                                <col style="width: 8%">
                                <col style="width: 5%">
                                <col style="width: 6%">
                                <col style="width: 6%">
                                <col style="width: 6%">
                                <col style="width: 9%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>No</th>
                                    <th>신청번호</th>
                                    <th>학교명</th>
                                    <th>신청자명</th>
                                    <th>신청자 ID</th>
                                    <th>휴대폰 번호</th>
                                    <th>룸메이트</th>
                                    <th>숙박형태</th>
                                    <th>세금계산서</th>
                                    <th>현금영수증</th>
                                    <th>결제상태</th>
                                    <th>접수상태</th>
                                    <th>신청일시</th>
                                    <th>이수 여부</th>
                                    <th>{{ $program->certificate_type ?? '이수증' }} 발급</th>
                                    <th>영수증 발급</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $index => $application)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_applications[]" value="{{ $application->id }}" class="application-checkbox">
                                        </td>
                                        <td>{{ $applications->total() - ($applications->firstItem() + $index) + 1 }}</td>
                                        <td>{{ $application->application_number }}</td>
                                        <td>{{ $application->affiliation ?? '-' }}</td>
                                        <td>{{ $application->applicant_name }}</td>
                                        <td>{{ $application->member->login_id ?? '-' }}</td>
                                        <td>{{ $application->phone_number }}</td>
                                        <td>{{ $application->roommate_name ? $application->roommate_name . ($application->roommate_phone ? ' / ' . $application->roommate_phone : '') : '-' }}</td>
                                        <td>
                                            @php
                                                $ft = (string) ($application->fee_type ?? '');
                                                $accommodation = str_contains($ft, 'twin') ? '2인1실' : (str_contains($ft, 'single') ? '1인실' : (str_contains($ft, 'no_stay') ? '비숙박' : '-'));
                                            @endphp
                                            {{ $accommodation }}
                                        </td>
                                        <td>
                                            @php
                                                $taxStatus = trim((string)($application->tax_invoice_status ?? ''));
                                                if ($taxStatus === '') {
                                                    if (!$application->has_tax_invoice) {
                                                        $taxStatus = '미신청';
                                                    } else {
                                                        $taxStatus = ($application->payment_status === '입금완료') ? '발행완료' : '신청완료';
                                                    }
                                                }
                                            @endphp
                                            <select name="tax_invoice_status[{{ $application->id }}]" class="form-control form-control-sm table-select-sm" data-action="update-tax-invoice-status" data-application-id="{{ $application->id }}">
                                                <option value="미신청" @selected($taxStatus === '미신청')>미신청</option>
                                                <option value="신청완료" @selected($taxStatus === '신청완료')>신청완료</option>
                                                <option value="발행완료" @selected($taxStatus === '발행완료')>발행완료</option>
                                            </select>
                                        </td>
                                        <td>
                                            @php
                                                $cashStatus = trim((string)($application->cash_receipt_status ?? ''));
                                                if ($cashStatus === '') {
                                                    if (!$application->has_cash_receipt) {
                                                        $cashStatus = '미신청';
                                                    } else {
                                                        $cashStatus = ($application->payment_status === '입금완료') ? '발행완료' : '신청완료';
                                                    }
                                                }
                                            @endphp
                                            <select name="cash_receipt_status[{{ $application->id }}]" class="form-control form-control-sm table-select-sm" data-action="update-cash-receipt-status" data-application-id="{{ $application->id }}">
                                                <option value="미신청" @selected($cashStatus === '미신청')>미신청</option>
                                                <option value="신청완료" @selected($cashStatus === '신청완료')>신청완료</option>
                                                <option value="발행완료" @selected($cashStatus === '발행완료')>발행완료</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="payment_status[{{ $application->id }}]" class="form-control form-control-sm table-select-sm" data-action="update-payment-status" data-application-id="{{ $application->id }}">
                                                <option value="미입금" @selected($application->payment_status == '미입금')>미입금</option>
                                                <option value="입금완료" @selected($application->payment_status == '입금완료')>입금완료</option>
                                            </select>
                                        </td>
                                        <td>{{ $application->receipt_status ?? '-' }}</td>
                                        <td>{{ $application->application_date->format('Y.m.d H:i') }}</td>
                                        <td>
                                            <div class="form-row-inline">
                                                <div class="form-check form-check-inline form-check-inline-no-margin">
                                                    <input class="form-check-input" type="radio" name="is_completed[{{ $application->id }}]" id="completed_y_{{ $application->id }}" value="1" data-action="update-completion-status" data-application-id="{{ $application->id }}" data-completed="1" @checked($application->is_completed)>
                                                    <label class="form-check-label" for="completed_y_{{ $application->id }}">Y</label>
                                                </div>
                                                <div class="form-check form-check-inline form-check-inline-no-margin">
                                                    <input class="form-check-input" type="radio" name="is_completed[{{ $application->id }}]" id="completed_n_{{ $application->id }}" value="0" data-action="update-completion-status" data-application-id="{{ $application->id }}" data-completed="0" @checked(!$application->is_completed)>
                                                    <label class="form-check-label" for="completed_n_{{ $application->id }}">N</label>
                                                </div>
                                            </div>
                                        </td>
                                        @include('backoffice.education-applications.partials.print_links', ['application' => $application, 'certificate_type' => $program->certificate_type ?? '이수증'])
                                        <td>
                                            <div class="board-btn-group">
                                                <a href="{{ route('backoffice.seminar-training-applications.edit', $application) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> 수정
                                                </a>
                                                <form action="{{ route('backoffice.seminar-training-applications.destroy', $application) }}" method="POST" class="d-inline" data-action="confirm-delete">
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
                                        <td colspan="18" class="text-center">등록된 신청 내역이 없습니다.</td>
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
            fetch(url, { method: 'POST', body: body, headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.success && data.message) {
                    var alert = document.querySelector('.board-hidden-alert.alert-success');
                    if (alert) { alert.textContent = data.message; alert.classList.remove('board-hidden-alert'); alert.style.display = 'block'; setTimeout(function() { alert.style.display = 'none'; }, 3000); }
                    else { var div = document.createElement('div'); div.className = 'alert alert-success'; div.textContent = data.message; document.querySelector('.board-container').insertBefore(div, document.querySelector('.board-container').firstChild); setTimeout(function() { div.remove(); }, 3000); }
                }
            })
            .catch(function() { alert('접수상태 저장에 실패했습니다.'); });
        });
    }
})();
</script>
@endsection
