@php
    $app = $application ?? null;
    $isEdit = $isEdit ?? false;
    $feeProgram = $app->seminarTraining ?? $program ?? null;
@endphp
<form action="{{ $isEdit ? route('backoffice.seminar-training-applications.update', $app) : route('backoffice.seminar-training-applications.store') }}" method="POST" enctype="multipart/form-data" id="applicationForm"
    @if($isEdit && ($app->seminar_training_id ?? null))
        data-roommate-requests-url="{{ route('backoffice.seminar-training-applications.roommate-requests', $app) }}"
        data-roommate-requests-approve-url="{{ route('backoffice.seminar-training-applications.roommate-requests.approve', $app) }}"
        data-roommate-requests-reject-url="{{ route('backoffice.seminar-training-applications.roommate-requests.reject', $app) }}"
    @endif>
    @csrf
    @if($isEdit)
        @method('PUT')
    @else
        @if($program ?? null)
            <input type="hidden" name="seminar_training_id" value="{{ old('seminar_training_id', $program->id) }}" required>
        @else
            <div class="member-form-section">
                <div class="member-form-list">
                    <div class="member-form-row">
                        <label class="member-form-label">교육 프로그램 <span class="required">*</span></label>
                        <div class="member-form-field">
                            <select name="seminar_training_id" class="board-form-control @error('seminar_training_id') is-invalid @enderror" required>
                                <option value="">선택해주세요</option>
                                @foreach($programs ?? [] as $p)
                                    <option value="{{ $p->id }}" @selected(old('seminar_training_id') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('seminar_training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- 신청 정보 (PN36 순서: 신청번호, 신청자 ID+회원검색, 신청자명, 소속기관, 휴대폰, 이메일, 신청일시, 이수 여부, 설문조사 여부, 룸메이트, 이수증/수료증 번호, 영수증 번호, 환불계좌정보) -->
    <div class="member-form-section">
        <h3 class="member-section-title">신청 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">신청번호</label>
                <div class="member-form-field">
                    <div class="board-form-control readonly-field">
                        {{ $isEdit ? ($app?->application_number ?? '') : '자동 생성' }}
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">신청자 ID</label>
                <div class="member-form-field">
                    <div class="input-with-button">
                        <input type="text" class="board-form-control @error('member_id') is-invalid @enderror"
                               id="member_login_id" name="member_login_id" value="{{ old('member_login_id', $app?->member?->login_id ?? '') }}" readonly>
                        <input type="hidden" id="member_id" name="member_id" value="{{ old('member_id', $app?->member_id ?? '') }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-action="open-member-search">
                            회원 검색
                        </button>
                    </div>
                    @error('member_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">신청자명 <span class="required">*</span></label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control @error('applicant_name') is-invalid @enderror"
                               id="applicant_name" name="applicant_name" value="{{ old('applicant_name', $app?->applicant_name ?? '') }}" maxlength="50" required>
                        @error('applicant_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">소속기관</label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control @error('affiliation') is-invalid @enderror"
                               id="affiliation" name="affiliation" value="{{ old('affiliation', $app?->affiliation ?? '') }}" maxlength="100">
                        @error('affiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">휴대폰 번호 <span class="required">*</span></label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control @error('phone_number') is-invalid @enderror"
                               id="phone_number" name="phone_number" value="{{ old('phone_number', $app?->phone_number ?? '') }}" maxlength="20" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">이메일</label>
                    <div class="member-form-field">
                        <input type="email" class="board-form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $app?->email ?? '') }}" maxlength="100">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">신청일시 <span class="required">*</span></label>
                <div class="member-form-field">
                    <input type="datetime-local" class="board-form-control @error('application_date') is-invalid @enderror"
                           id="application_date" name="application_date" value="{{ old('application_date', $app?->application_date ? $app?->application_date->format('Y-m-d\TH:i') : date('Y-m-d\TH:i')) }}" required>
                    @error('application_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">이수 여부</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="is_completed_y" name="is_completed" value="1" class="board-radio-input" @checked(old('is_completed', $app?->is_completed ?? 0) == 1)>
                            <label for="is_completed_y">Y</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="is_completed_n" name="is_completed" value="0" class="board-radio-input" @checked(old('is_completed', $app?->is_completed ?? 0) == 0)>
                            <label for="is_completed_n">N</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">접수상태</label>
                <div class="member-form-field">
                    <select name="receipt_status" class="board-form-control @error('receipt_status') is-invalid @enderror">
                        <option value="신청완료" @selected(old('receipt_status', $app?->receipt_status ?? '') == '신청완료')>신청완료</option>
                        <option value="수료" @selected(old('receipt_status', $app?->receipt_status ?? '') == '수료')>수료</option>
                        <option value="미수료" @selected(old('receipt_status', $app?->receipt_status ?? '') == '미수료')>미수료</option>
                        <option value="접수취소" @selected(old('receipt_status', $app?->receipt_status ?? '') == '접수취소')>접수취소</option>
                    </select>
                    @error('receipt_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">설문조사 여부</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="is_survey_completed_y" name="is_survey_completed" value="1" class="board-radio-input" @checked(old('is_survey_completed', $app?->is_survey_completed ?? 0) == 1)>
                            <label for="is_survey_completed_y">Y</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="is_survey_completed_n" name="is_survey_completed" value="0" class="board-radio-input" @checked(old('is_survey_completed', $app?->is_survey_completed ?? 0) == 0)>
                            <label for="is_survey_completed_n">N</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">룸메이트</label>
                <div class="member-form-field">
                    <div class="input-with-button">
                        <input type="text" class="board-form-control" id="roommate_display" readonly
                               value="{{ old('roommate_display', $app?->roommate_name ? $app?->roommate_name . ($app?->roommate_phone ? ' / ' . $app?->roommate_phone : '') : '') }}" placeholder="회원 검색으로 선택">
                        <input type="hidden" id="roommate_member_id" name="roommate_member_id" value="{{ old('roommate_member_id', $app?->roommate_member_id ?? '') }}">
                        <input type="hidden" id="roommate_name" name="roommate_name" value="{{ old('roommate_name', $app?->roommate_name ?? '') }}">
                        <input type="hidden" id="roommate_phone" name="roommate_phone" value="{{ old('roommate_phone', $app?->roommate_phone ?? '') }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-action="open-roommate-search">
                            회원 검색
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-action="open-roommate-requests">
                            요청 내역
                        </button>
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">이수증/수료증 번호</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->certificate_number ?? '자동 생성') : '자동 생성' }}
                        </div>
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">영수증 번호</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->receipt_number ?? '자동 생성') : '자동 생성' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">환불계좌정보</label>
                <div class="member-form-field">
                    <div class="refund-account-row">
                        <div class="form-field-flex">
                            <label class="sub-label">예금주명</label>
                            <input type="text" class="board-form-control @error('refund_account_holder') is-invalid @enderror"
                                   id="refund_account_holder" name="refund_account_holder" value="{{ old('refund_account_holder', $app?->refund_account_holder ?? '') }}" maxlength="50">
                            @error('refund_account_holder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-field-flex">
                            <label class="sub-label">은행선택</label>
                            <select name="refund_bank_name" class="board-form-control @error('refund_bank_name') is-invalid @enderror">
                                <option value="">선택해주세요</option>
                                @foreach(['KB국민은행','신한은행','우리은행','하나은행','NH농협은행','IBK기업은행','카카오뱅크','토스뱅크','SC제일은행','한국씨티은행','케이뱅크','새마을금고','신협','우체국'] as $bank)
                                    <option value="{{ $bank }}" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == $bank)>{{ $bank }}</option>
                                @endforeach
                            </select>
                            @error('refund_bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-field-flex">
                            <label class="sub-label">계좌번호</label>
                            <input type="text" class="board-form-control @error('refund_account_number') is-invalid @enderror"
                                   id="refund_account_number" name="refund_account_number" value="{{ old('refund_account_number', $app?->refund_account_number ?? '') }}" maxlength="50">
                            @error('refund_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 결제 정보 (PN36: 참가비, 결제방법, 결제상태, 현금영수증, 세금계산서) -->
    <div class="member-form-section">
        <h3 class="member-section-title">결제 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">참가비</label>
                <div class="member-form-field">
                    <div class="board-form-control readonly-field form-field-flex-200" id="participation_fee_display">
                        @if(isset($app) && $app->participation_fee !== null && $app->participation_fee !== '')
                            {{ number_format((float) $app->participation_fee) }}원
                        @else
                            {{ '' }}
                        @endif
                    </div>
                    <input type="hidden" id="participation_fee" name="participation_fee" value="{{ old('participation_fee', $app?->participation_fee ?? '') }}">
                    <input type="hidden" id="fee_type" name="fee_type" value="{{ old('fee_type', $app?->fee_type ?? '') }}">
                    @php
                        $feeSchoolType = old('fee_school_type', $app?->fee_type ? (str_starts_with($app?->fee_type ?? '', 'member') ? '회원교' : '비회원교') : '회원교');
                        $ft = $app?->fee_type ?? '';
                        $feeAccommodation = old('fee_accommodation');
                        if ($feeAccommodation === null || $feeAccommodation === '') {
                            $feeAccommodation = str_contains($ft, 'twin') ? '2인1실' : (str_contains($ft, 'single') ? '1인실' : (str_contains($ft, 'no_stay') ? '비숙박' : '2인1실'));
                        }
                    @endphp
                    <input type="hidden" name="fee_school_type" value="{{ $feeSchoolType }}">
                    <div class="fee-type-block mt-2"
                         id="fee-type-block"
                         data-fee-member-twin="{{ $feeProgram ? (string) ($feeProgram->fee_member_twin ?? '') : '' }}"
                         data-fee-member-single="{{ $feeProgram ? (string) ($feeProgram->fee_member_single ?? '') : '' }}"
                         data-fee-member-no-stay="{{ $feeProgram ? (string) ($feeProgram->fee_member_no_stay ?? '') : '' }}"
                         data-fee-guest-twin="{{ $feeProgram ? (string) ($feeProgram->fee_guest_twin ?? '') : '' }}"
                         data-fee-guest-single="{{ $feeProgram ? (string) ($feeProgram->fee_guest_single ?? '') : '' }}"
                         data-fee-guest-no-stay="{{ $feeProgram ? (string) ($feeProgram->fee_guest_no_stay ?? '') : '' }}">
                        <div class="form-row-inline mb-1">
                            <div class="board-radio-item">
                                <input type="radio" id="fee_member_school" name="fee_school_type_display" value="회원교" class="board-radio-input" disabled @checked($feeSchoolType == '회원교')>
                                <label for="fee_member_school">회원교</label>
                            </div>
                            <div class="board-radio-group ml-3">
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_member_twin" name="fee_accommodation" value="2인1실" class="board-radio-input fee-accommodation-member" @disabled($feeSchoolType == '비회원교') @checked($feeSchoolType == '회원교' && $feeAccommodation == '2인1실')>
                                    <label for="fee_member_twin">2인 1실</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_member_single" name="fee_accommodation" value="1인실" class="board-radio-input fee-accommodation-member" @disabled($feeSchoolType == '비회원교') @checked($feeSchoolType == '회원교' && $feeAccommodation == '1인실')>
                                    <label for="fee_member_single">1인실</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_member_no_stay" name="fee_accommodation" value="비숙박" class="board-radio-input fee-accommodation-member" @disabled($feeSchoolType == '비회원교') @checked($feeSchoolType == '회원교' && $feeAccommodation == '비숙박')>
                                    <label for="fee_member_no_stay">비숙박</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row-inline">
                            <div class="board-radio-item">
                                <input type="radio" id="fee_guest_school" name="fee_school_type_display" value="비회원교" class="board-radio-input" disabled @checked($feeSchoolType == '비회원교')>
                                <label for="fee_guest_school">비회원교</label>
                            </div>
                            <div class="board-radio-group ml-3">
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_guest_twin" name="fee_accommodation" value="2인1실" class="board-radio-input fee-accommodation-guest" @disabled($feeSchoolType == '회원교') @checked($feeSchoolType == '비회원교' && $feeAccommodation == '2인1실')>
                                    <label for="fee_guest_twin">2인 1실</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_guest_single" name="fee_accommodation" value="1인실" class="board-radio-input fee-accommodation-guest" @disabled($feeSchoolType == '회원교') @checked($feeSchoolType == '비회원교' && $feeAccommodation == '1인실')>
                                    <label for="fee_guest_single">1인실</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="fee_guest_no_stay" name="fee_accommodation" value="비숙박" class="board-radio-input fee-accommodation-guest" @disabled($feeSchoolType == '회원교') @checked($feeSchoolType == '비회원교' && $feeAccommodation == '비숙박')>
                                    <label for="fee_guest_no_stay">비숙박</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">결제방법</label>
                <div class="member-form-field">
                    @php
                        $paymentMethods = old('payment_method', $app?->payment_method ?? []);
                        if (!is_array($paymentMethods)) {
                            $paymentMethods = [];
                        }
                    @endphp
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="payment_method[]" value="무통장입금" @checked(in_array('무통장입금', $paymentMethods))>
                            <span>무통장 입금</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="payment_method[]" value="방문카드결제" @checked(in_array('방문카드결제', $paymentMethods))>
                            <span>방문 카드결제</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="payment_method[]" value="온라인카드결제" @checked(in_array('온라인카드결제', $paymentMethods))>
                            <span>온라인 카드결제</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">결제상태 <span class="required">*</span></label>
                <div class="member-form-field">
                    <div class="form-row-group">
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">결제상태</span>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="payment_status_unpaid" name="payment_status" value="미입금" class="board-radio-input" @checked(old('payment_status', $app?->payment_status ?? '미입금') == '미입금') required>
                                    <label for="payment_status_unpaid">미입금</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="payment_status_paid" name="payment_status" value="입금완료" class="board-radio-input" @checked(old('payment_status', $app?->payment_status ?? '') == '입금완료')>
                                    <label for="payment_status_paid">입금완료</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">입금일시 :</span>
                            <input type="datetime-local" class="board-form-control form-field-flex-300 @error('payment_date') is-invalid @enderror"
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', $app?->payment_date ? $app?->payment_date->format('Y-m-d\TH:i') : '') }}">
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @error('payment_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">세금계산서 발행여부</label>
                <div class="member-form-field">
                    <select name="tax_invoice_status" class="board-form-control @error('tax_invoice_status') is-invalid @enderror">
                        <option value="미신청" @selected(old('tax_invoice_status', $app?->tax_invoice_status ?? '미신청') == '미신청')>미신청</option>
                        <option value="신청완료" @selected(old('tax_invoice_status', $app?->tax_invoice_status ?? '') == '신청완료')>신청완료</option>
                        <option value="발행완료" @selected(old('tax_invoice_status', $app?->tax_invoice_status ?? '') == '발행완료')>발행완료</option>
                    </select>
                    @error('tax_invoice_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">현금영수증</label>
                <div class="member-form-field">
                    <div class="form-row-group">
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">현금영수증</span>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="has_cash_receipt_y" name="has_cash_receipt" value="1" class="board-radio-input" @checked(old('has_cash_receipt', $app?->has_cash_receipt ?? 0) == 1)>
                                    <label for="has_cash_receipt_y">Y</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="has_cash_receipt_n" name="has_cash_receipt" value="0" class="board-radio-input" @checked(old('has_cash_receipt', $app?->has_cash_receipt ?? 0) == 0)>
                                    <label for="has_cash_receipt_n">N</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">용도선택</span>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="cash_receipt_purpose_income" name="cash_receipt_purpose" value="소득공제용" class="board-radio-input" @checked(old('cash_receipt_purpose', $app?->cash_receipt_purpose ?? '') == '소득공제용')>
                                    <label for="cash_receipt_purpose_income">소득공제용</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="cash_receipt_purpose_business" name="cash_receipt_purpose" value="사업자 지출증빙용" class="board-radio-input" @checked(old('cash_receipt_purpose', $app?->cash_receipt_purpose ?? '') == '사업자 지출증빙용')>
                                    <label for="cash_receipt_purpose_business">사업자 지출증빙용</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">발행번호</span>
                            <input type="text" class="board-form-control form-field-flex-200 @error('cash_receipt_number') is-invalid @enderror"
                                   id="cash_receipt_number" name="cash_receipt_number" value="{{ old('cash_receipt_number', $app?->cash_receipt_number ?? '') }}" maxlength="50">
                            @error('cash_receipt_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">세금계산서</label>
                <div class="member-form-field">
                    <div class="form-row-group">
                        <div class="board-radio-group">
                            <div class="board-radio-item">
                                <input type="radio" id="has_tax_invoice_y" name="has_tax_invoice" value="1" class="board-radio-input" @checked(old('has_tax_invoice', $app?->has_tax_invoice ?? 0) == 1)>
                                <label for="has_tax_invoice_y">Y</label>
                            </div>
                            <div class="board-radio-item">
                                <input type="radio" id="has_tax_invoice_n" name="has_tax_invoice" value="0" class="board-radio-input" @checked(old('has_tax_invoice', $app?->has_tax_invoice ?? 0) == 0)>
                                <label for="has_tax_invoice_n">N</label>
                            </div>
                        </div>
                        <div class="form-row-inline mt-1">
                            <span class="sub-label" style="margin-bottom:0;">상호명/등록번호</span>
                            <input type="text" class="board-form-control form-field-flex-200 @error('company_name') is-invalid @enderror"
                                   id="company_name" name="company_name" value="{{ old('company_name', $app?->company_name ?? '') }}" maxlength="100" placeholder="상호명">
                            <span class="separator mx-1">/</span>
                            <input type="text" class="board-form-control form-field-flex-200 @error('registration_number') is-invalid @enderror"
                                   id="registration_number" name="registration_number" value="{{ old('registration_number', $app?->registration_number ?? '') }}" maxlength="50" placeholder="등록번호">
                            @error('company_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('registration_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row-inline mt-1">
                            <span class="sub-label" style="margin-bottom:0;">담당자 정보</span>
                            <input type="text" class="board-form-control form-field-flex-150 @error('contact_person_name') is-invalid @enderror"
                                   id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $app?->contact_person_name ?? '') }}" maxlength="50" placeholder="담당자명">
                            <span class="separator mx-1">/</span>
                            <input type="email" class="board-form-control form-field-flex-200 @error('contact_person_email') is-invalid @enderror"
                                   id="contact_person_email" name="contact_person_email" value="{{ old('contact_person_email', $app?->contact_person_email ?? '') }}" maxlength="100" placeholder="이메일">
                            <span class="separator mx-1">/</span>
                            <input type="text" class="board-form-control form-field-flex-150 @error('contact_person_phone') is-invalid @enderror"
                                   id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $app?->contact_person_phone ?? '') }}" maxlength="20" placeholder="휴대폰 번호">
                            @error('contact_person_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('contact_person_email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('contact_person_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row-inline mt-1">
                            <span class="sub-label" style="margin-bottom:0;">사업자등록증</span>
                            <input type="file" class="board-form-control form-field-flex-300" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <button type="button" class="btn btn-outline-secondary btn-sm ml-1">추가</button>
                            <button type="button" class="btn btn-outline-danger btn-sm ml-1" data-action="clear-attachment-files">삭제</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> 저장
        </button>
        @php
            $listUrl = $isEdit && $app?->program
                ? route('backoffice.seminar-training-applications.show', $app->program_id)
                : (($program ?? null) ? route('backoffice.seminar-training-applications.show', $program->id) : route('backoffice.seminar-training-applications.index'));
        @endphp
        <a href="{{ $listUrl }}" class="btn btn-secondary">목록</a>
    </div>
</form>
