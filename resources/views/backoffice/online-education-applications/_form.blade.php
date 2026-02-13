@php
    $app = $application ?? null;
    $isEdit = $isEdit ?? false;
@endphp
<form action="{{ $isEdit ? route('backoffice.online-education-applications.update', $app) : route('backoffice.online-education-applications.store') }}" method="POST" enctype="multipart/form-data" id="applicationForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @else
        @if($program)
            <input type="hidden" name="online_education_id" value="{{ old('online_education_id', $program->id) }}" required>
        @else
            <div class="member-form-section">
                <div class="member-form-list">
                    <div class="member-form-row">
                        <label class="member-form-label">교육 프로그램 <span class="required">*</span></label>
                        <div class="member-form-field">
                            <select name="online_education_id" class="board-form-control @error('online_education_id') is-invalid @enderror" required>
                                <option value="">선택해주세요</option>
                                @foreach($programs ?? [] as $p)
                                    <option value="{{ $p->id }}" @selected(old('online_education_id') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('online_education_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- 신청 정보 (기획서) -->
    <div class="member-form-section">
        <h3 class="member-section-title">신청 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">신청번호</label>
                <div class="member-form-field">
                    <div class="board-form-control readonly-field">
                        {{ $isEdit ? $app?->application_number : '자동 생성' }}
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
            <div class="member-form-row">
                <label class="member-form-label">신청자명 <span class="required">*</span></label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('applicant_name') is-invalid @enderror"
                           id="applicant_name" name="applicant_name" value="{{ old('applicant_name', $app?->applicant_name ?? '') }}" maxlength="50" required>
                    @error('applicant_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">소속기관</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('affiliation') is-invalid @enderror"
                           id="affiliation" name="affiliation" value="{{ old('affiliation', $app?->affiliation ?? '') }}" maxlength="100">
                    @error('affiliation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">휴대폰 번호 <span class="required">*</span></label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('phone_number') is-invalid @enderror"
                           id="phone_number" name="phone_number" value="{{ old('phone_number', $app?->phone_number ?? '') }}" maxlength="20" required>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">이메일</label>
                <div class="member-form-field">
                    <input type="email" class="board-form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $app?->email ?? '') }}" maxlength="100">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                <label class="member-form-label">수강상태</label>
                <div class="member-form-field">
                    <select name="course_status" class="board-form-control @error('course_status') is-invalid @enderror">
                        <option value="접수" @selected(old('course_status', $app?->course_status ?? '접수') == '접수')>접수</option>
                        <option value="승인" @selected(old('course_status', $app?->course_status ?? '') == '승인')>승인</option>
                        <option value="만료" @selected(old('course_status', $app?->course_status ?? '') == '만료')>만료</option>
                    </select>
                    @error('course_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">수강률 (%)</label>
                <div class="member-form-field">
                    <input type="number" class="board-form-control @error('attendance_rate') is-invalid @enderror"
                           name="attendance_rate" value="{{ old('attendance_rate', $app?->attendance_rate ?? '') }}" min="0" max="100" step="0.01" placeholder="0–100">
                    @error('attendance_rate')
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
                <label class="member-form-label">영수증 번호</label>
                <div class="member-form-field">
                    <div class="board-form-control readonly-field">
                        {{ $isEdit ? ($app?->receipt_number ?? '-') : '자동 생성' }}
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
                                <option value="KB국민은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == 'KB국민은행')>KB국민은행</option>
                                <option value="신한은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '신한은행')>신한은행</option>
                                <option value="우리은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '우리은행')>우리은행</option>
                                <option value="하나은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '하나은행')>하나은행</option>
                                <option value="NH농협은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == 'NH농협은행')>NH농협은행</option>
                                <option value="IBK기업은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == 'IBK기업은행')>IBK기업은행</option>
                                <option value="카카오뱅크" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '카카오뱅크')>카카오뱅크</option>
                                <option value="토스뱅크" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '토스뱅크')>토스뱅크</option>
                                <option value="SC제일은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == 'SC제일은행')>SC제일은행</option>
                                <option value="한국씨티은행" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '한국씨티은행')>한국씨티은행</option>
                                <option value="케이뱅크" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '케이뱅크')>케이뱅크</option>
                                <option value="새마을금고" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '새마을금고')>새마을금고</option>
                                <option value="신협" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '신협')>신협</option>
                                <option value="우체국" @selected(old('refund_bank_name', $app?->refund_bank_name ?? '') == '우체국')>우체국</option>
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

    <!-- 결제 정보 (기획서: 참가비 자동입력, 결제방법, 결제상태, 입금일시, 현금영수증, 세금계산서) -->
    <div class="member-form-section">
        <h3 class="member-section-title">결제 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">참가비</label>
                <div class="member-form-field">
                    <div class="board-form-control readonly-field form-field-flex-200">
                        자동 입력
                    </div>
                    <input type="hidden" id="participation_fee" name="participation_fee" value="{{ old('participation_fee', $app?->participation_fee ?? '') }}">
                    <input type="hidden" id="fee_type" name="fee_type" value="{{ old('fee_type', $app?->fee_type ?? '') }}">
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
                            <span>무통장입금</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="payment_method[]" value="방문카드결제" @checked(in_array('방문카드결제', $paymentMethods))>
                            <span>방문카드결제</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="payment_method[]" value="온라인카드결제" @checked(in_array('온라인카드결제', $paymentMethods))>
                            <span>온라인카드결제</span>
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
                            <span class="sub-label" style="margin-bottom:0;">입금일시</span>
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
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">상호명/등록번호</span>
                            <input type="text" class="board-form-control form-field-flex-200 @error('company_name') is-invalid @enderror"
                                   id="company_name" name="company_name" value="{{ old('company_name', $app?->company_name ?? '') }}" maxlength="100" placeholder="상호명">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="separator">/</span>
                            <input type="text" class="board-form-control form-field-flex-200 @error('registration_number') is-invalid @enderror"
                                   id="registration_number" name="registration_number" value="{{ old('registration_number', $app?->registration_number ?? '') }}" maxlength="50" placeholder="등록번호">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">담당자 정보</span>
                            <input type="text" class="board-form-control form-field-flex-150 @error('contact_person_name') is-invalid @enderror"
                                   id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $app?->contact_person_name ?? '') }}" maxlength="50" placeholder="담당자명">
                            @error('contact_person_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="separator">/</span>
                            <input type="email" class="board-form-control form-field-flex-200 @error('contact_person_email') is-invalid @enderror"
                                   id="contact_person_email" name="contact_person_email" value="{{ old('contact_person_email', $app?->contact_person_email ?? '') }}" maxlength="100" placeholder="이메일">
                            @error('contact_person_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="separator">/</span>
                            <input type="text" class="board-form-control form-field-flex-150 @error('contact_person_phone') is-invalid @enderror"
                                   id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $app?->contact_person_phone ?? '') }}" maxlength="20" placeholder="휴대폰 번호">
                            @error('contact_person_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row-inline">
                            <span class="sub-label" style="margin-bottom:0;">사업자등록증</span>
                            <input type="file" class="board-form-control form-field-flex-300 @error('attachments.*') is-invalid @enderror"
                                   id="attachments" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if($isEdit && $app?->attachments->count() > 0)
                            <div class="mt-2">
                                <span class="sub-label" style="margin-bottom:0;">등록된 파일:</span>
                                @foreach($app?->attachments as $index => $attachment)
                                    <span class="attachment-item-inline">
                                        <a href="{{ $attachment->path }}" target="_blank">{{ $attachment->name }}</a>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-attachment-btn ms-1" data-attachment-id="{{ $attachment->id }}">
                                            삭제
                                        </button>
                                    </span>
                                    @if($index < $app?->attachments->count() - 1), @endif
                                @endforeach
                                @foreach($app?->attachments as $attachment)
                                    <input type="hidden" name="delete_attachments[]" id="delete_attachment_{{ $attachment->id }}" value="">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> 저장
        </button>
        <a href="{{ $isEdit ? route('backoffice.online-education-applications.show', $app?->program_id) : ($program ? route('backoffice.online-education-applications.show', $program->id) : route('backoffice.online-education-applications.index')) }}" class="btn btn-secondary">목록</a>
    </div>
</form>
