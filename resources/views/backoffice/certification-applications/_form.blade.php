@php
    $app = $application ?? null;
    $isEdit = $isEdit ?? false;
@endphp
<form action="{{ $isEdit ? route('backoffice.certification-applications.update', $app) : route('backoffice.certification-applications.store') }}" method="POST" enctype="multipart/form-data" id="applicationForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @else
        @if($program)
            <input type="hidden" name="certification_id" value="{{ old('certification_id', $program->id) }}" required>
        @else
            <div class="member-form-section">
                <div class="member-form-list">
                    <div class="member-form-row">
                        <label class="member-form-label">교육 프로그램 <span class="required">*</span></label>
                        <div class="member-form-field">
                            <select name="certification_id" class="board-form-control @error('certification_id') is-invalid @enderror" required>
                                <option value="">선택해주세요</option>
                                @foreach($programs ?? [] as $p)
                                    <option value="{{ $p->id }}" @selected(old('certification_id') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('certification_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

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
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">생년월일</label>
                    <div class="member-form-field">
                        <input type="date" class="board-form-control @error('birth_date') is-invalid @enderror"
                               name="birth_date" value="{{ old('birth_date', $app?->birth_date ? $app?->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">증명사진</label>
                    <div class="member-form-field">
                        <input type="file" class="board-form-control @error('id_photo') is-invalid @enderror"
                               id="id_photo" name="id_photo" accept=".jpg,.jpeg,.png">
                        @error('id_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($isEdit && $app?->id_photo_path)
                            <div class="mt-2">
                                <span class="attachment-item-inline">
                                    <a href="{{ asset($app->id_photo_path) }}" target="_blank">{{ basename($app->id_photo_path) }}</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm ms-1" id="btnDeleteIdPhoto">삭제</button>
                                </span>
                                <input type="hidden" name="delete_id_photo" id="delete_id_photo" value="0">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">성적</label>
                    <div class="member-form-field">
                        <input type="number" class="board-form-control @error('score') is-invalid @enderror"
                               name="score" value="{{ old('score', $app?->score ?? '') }}" min="0">
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">합격여부</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            자동 입력
                        </div>
                        @if($isEdit && $app?->pass_status)
                            <span class="text-muted small">(현재: {{ $app->pass_status }})</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">신청일시 <span class="required">*</span></label>
                    <div class="member-form-field">
                        <input type="datetime-local" class="board-form-control @error('application_date') is-invalid @enderror"
                               id="application_date" name="application_date" value="{{ old('application_date', $app?->application_date ? $app?->application_date->format('Y-m-d\TH:i') : date('Y-m-d\TH:i')) }}" required>
                        @error('application_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">시험장</label>
                    <div class="member-form-field">
                        <select name="exam_venue_id" class="board-form-control @error('exam_venue_id') is-invalid @enderror">
                            <option value="">선택</option>
                            @foreach($examVenues ?? [] as $venue)
                                <option value="{{ $venue->id }}" @selected(old('exam_venue_id', $app?->exam_venue_id ?? '') == $venue->id)>{{ $venue->name }}</option>
                            @endforeach
                        </select>
                        @error('exam_venue_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">자격확인서</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->qualification_certificate_number ?? '자동 생성') : '자동 생성' }}
                        </div>
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">수험표 번호</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->exam_ticket_number ?? '자동 생성') : '자동 생성' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">영수증 번호</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->receipt_number ?? '자동생성') : '자동생성' }}
                        </div>
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">합격확인서</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            {{ $isEdit ? ($app?->pass_confirmation_number ?? '자동 생성') : '자동 생성' }}
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

    <div class="member-form-section">
        <h3 class="member-section-title">결제 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">응시료</label>
                    <div class="member-form-field">
                        <div class="board-form-control readonly-field">
                            자동 입력
                        </div>
                        @if($isEdit && $app?->participation_fee !== null)
                            <span class="text-muted small">({{ number_format($app->participation_fee) }}원)</span>
                        @endif
                    </div>
                </div>
                <div class="member-form-inline-item">
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
                            <span class="sub-label" style="margin-bottom:0;">입금일시</span>
                            <input type="datetime-local" class="board-form-control form-field-flex-300 @error('payment_date') is-invalid @enderror"
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', $app?->payment_date ? $app?->payment_date->format('Y-m-d\TH:i') : '') }}">
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="member-form-row">
                <label class="member-form-label">현금영수증</label>
                <div class="member-form-field">
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
            </div>
            <div class="member-form-row">
                <label class="member-form-label">용도선택 / 발행번호</label>
                <div class="member-form-field">
                    <div class="form-row-inline">
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
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> 저장
        </button>
        <a href="{{ $isEdit ? route('backoffice.certification-applications.show', $app?->program_id) : ($program ? route('backoffice.certification-applications.show', $program->id) : route('backoffice.certification-applications.index')) }}" class="btn btn-secondary">목록</a>
    </div>
</form>
