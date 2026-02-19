@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle tal bdb">교육 · 자격증 신청</div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert" style="margin-bottom:1rem;">
            <strong>입력 내용을 확인해주세요.</strong>
            <ul class="mb-0 mt-1" style="padding-left:1.25rem;">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('education_certification.application_ec_e_learning.store') }}" enctype="multipart/form-data" class="application_form" @if($errors->any()) data-join-errors="1" @endif>
        @csrf
        <input type="hidden" name="online_education_id" value="{{ $onlineEducation->id }}">

        <div class="otit">교육 신청</div>
        <div class="glbox dl_slice">
            <dl>
                <dt>교육명</dt>
                <dd>{{ $onlineEducation->name }}</dd>
            </dl>
            <dl>
                <dt>교육기간</dt>
                <dd>
                    {{ format_date_ko($onlineEducation->period_start) }}
                    @if($onlineEducation->period_end)
                        ~ {{ format_date_ko($onlineEducation->period_end) }}
                    @endif
                </dd>
            </dl>
            <dl>
                <dt>교육시간</dt>
                <dd>{{ $onlineEducation->period_time ?? '-' }}</dd>
            </dl>
            <dl>
                <dt>수료기준</dt>
                <dd>{{ $onlineEducation->completion_criteria ?? '추후 안내 예정' }}</dd>
            </dl>
        </div>

        <div class="otit">신청자 정보 입력</div>
        <div class="glbox dl_slice in_inputs">
            <dl>
                <dt>성명</dt>
                <dd>
                    <input type="text" name="applicant_name" class="text w1" value="{{ old('applicant_name', $member->name) }}" readonly>
                </dd>
            </dl>
            <dl>
                <dt>소속기관</dt>
                <dd>
                    <input type="text" name="affiliation" class="text w1" value="{{ old('affiliation', $member->school_name) }}" readonly>
                </dd>
            </dl>
            <dl>
                <dt>휴대폰번호</dt>
                <dd>
                    <input type="text" name="phone_number" class="text w1" value="{{ old('phone_number', $member->phone_number) }}" readonly>
                </dd>
            </dl>
            <dl>
                <dt>이메일</dt>
                <dd>
                    <input type="text" name="email" class="text w1" value="{{ old('email', $member->email) }}" readonly inputmode="email">
                </dd>
            </dl>
            <dl>
                <dt>환불 계좌 정보</dt>
                <dd class="colm">
                    <input type="text" name="refund_account_holder" class="text w1 @error('refund_account_holder') is-invalid @enderror" placeholder="예금주명을 입력해주세요." value="{{ old('refund_account_holder') }}">
                    @error('refund_account_holder')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                    <select name="refund_bank_name" class="text w1 @error('refund_bank_name') is-invalid @enderror">
                        <option value="">은행을 선택해주세요.</option>
                        @foreach (['KB국민은행','신한은행','우리은행','하나은행','NH농협은행','IBK기업은행','카카오뱅크','토스뱅크','새마을금고','SC제일은행'] as $bank)
                            <option value="{{ $bank }}" @selected(old('refund_bank_name') === $bank)>{{ $bank }}</option>
                        @endforeach
                    </select>
                    @error('refund_bank_name')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                    <input type="text" name="refund_account_number" class="text w1 @error('refund_account_number') is-invalid @enderror" placeholder="계좌번호를 입력해주세요." value="{{ old('refund_account_number') }}">
                    @error('refund_account_number')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </dd>
            </dl>
        </div>

        <div class="otit">교육 참가비 선택</div>
        <div class="tbl th_bg mo_reverse_tbl">
            <table>
                <colgroup>
                    <col class="w240">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>구분</th>
                        <th class="tac">참가비</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>일반</th>
                        <td class="tac">{{ $onlineEducation->is_free ? '무료' : number_format((float) ($onlineEducation->fee ?? 0)) . '원' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="otit">결제 및 환불 안내</div>
        <div class="tbl th_bg">
            <table>
                <colgroup>
                    <col class="w240">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <th>결제방법</th>
                        <td>{{ $onlineEducation->payment_methods ? implode(', ', $onlineEducation->payment_methods) : '무통장입금' }}</td>
                    </tr>
                    <tr>
                        <th>입금계좌</th>
                        <td>{{ $onlineEducation->deposit_account ?? '추후 안내 예정' }}</td>
                    </tr>
                    <tr>
                        <th>참가비</th>
                        <td>
                            <p class="big">
                                <strong class="c_blue">
                                    {{ $onlineEducation->is_free ? '무료' : number_format((float) ($onlineEducation->fee ?? 0)) . '원' }}
                                </strong>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if(!($onlineEducation->is_free ?? (float)($onlineEducation->fee ?? 0) == 0))
        <div class="otit">증빙서류 발행 여부</div>
        <div class="glbox dl_slice in_inputs dt_long">
            <dl>
                <dt>현금영수증 발행</dt>
                <dd class="radios">
                    @php
                        $defaultHasCashReceipt = old('has_cash_receipt') !== null ? old('has_cash_receipt') : ($cashReceiptDefaults['has_cash_receipt'] ?? false ? '1' : '0');
                    @endphp
                    <label class="radio">
                        <input type="radio" name="has_cash_receipt" value="1" @checked($defaultHasCashReceipt === '1')>
                        <i></i><span>발행</span>
                    </label>
                    <label class="radio">
                        <input type="radio" name="has_cash_receipt" value="0" @checked($defaultHasCashReceipt === '0')>
                        <i></i><span>미발행</span>
                    </label>
                </dd>
            </dl>
            <div class="gbox" data-cash-box>
                <dl>
                    <dt>용도 선택</dt>
                    <dd class="radios">
                        @php
                            $defaultPurpose = old('cash_receipt_purpose') ?? ($cashReceiptDefaults['cash_receipt_purpose'] ?? '');
                        @endphp
                        <label class="radio">
                            <input type="radio" name="cash_receipt_purpose" value="소득공제용" @checked($defaultPurpose === '소득공제용')>
                            <i></i><span>소득공제용</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="cash_receipt_purpose" value="사업자지출증빙용" @checked($defaultPurpose === '사업자지출증빙용')>
                            <i></i><span>사업자 지출증빙용</span>
                        </label>
                        @error('cash_receipt_purpose')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
                <dl>
                    <dt>발행번호</dt>
                    <dd>
                        <input type="text" name="cash_receipt_number" class="w1 @error('cash_receipt_number') is-invalid @enderror" value="{{ old('cash_receipt_number', $cashReceiptDefaults['cash_receipt_number'] ?? ($member?->phone_number ?? '')) }}" placeholder="휴대폰번호 또는 사업자등록번호">
                        @error('cash_receipt_number')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
            </div>
            <p class="ne">입금 확인 후 국세청으로 발행 처리됩니다.</p>

            <dl class="mt40">
                <dt>세금계산서 발행</dt>
                <dd class="radios">
                    <label class="radio">
                        <input type="radio" name="has_tax_invoice" value="1" @checked(old('has_tax_invoice') === '1')>
                        <i></i><span>발행</span>
                    </label>
                    <label class="radio">
                        <input type="radio" name="has_tax_invoice" value="0" @checked(old('has_tax_invoice', '0') === '0')>
                        <i></i><span>미발행</span>
                    </label>
                </dd>
            </dl>
            <div class="gbox" data-tax-box>
                <dl>
                    <dt>사업자등록번호</dt>
                    <dd>
                        <input type="text" name="registration_number" class="w1 @error('registration_number') is-invalid @enderror" placeholder="사업자등록번호를 입력해주세요." value="{{ old('registration_number') }}">
                        @error('registration_number')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
                <dl>
                    <dt>상호명</dt>
                    <dd>
                        <input type="text" name="company_name" class="w1 @error('company_name') is-invalid @enderror" placeholder="상호명을 입력해주세요." value="{{ old('company_name') }}">
                        @error('company_name')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
                <dl>
                    <dt>담당자 정보</dt>
                    <dd class="colm">
                        <input type="text" name="contact_person_name" class="w1 @error('contact_person_name') is-invalid @enderror" placeholder="담당자명 입력해주세요." value="{{ old('contact_person_name') }}">
                        <input type="text" name="contact_person_email" class="w1 @error('contact_person_email') is-invalid @enderror" placeholder="이메일을 입력해주세요." value="{{ old('contact_person_email') }}" inputmode="email">
                        <input type="text" name="contact_person_phone" class="w1 @error('contact_person_phone') is-invalid @enderror" placeholder="연락처를 입력해주세요." value="{{ \App\Models\Member::formatPhoneForDisplay(old('contact_person_phone')) }}" maxlength="13" inputmode="tel" autocomplete="tel">
                        @error('contact_person_name')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                        @error('contact_person_email')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                        @error('contact_person_phone')<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                    </dd>
                </dl>
                <dl>
                    <dt>사업자등록증 첨부</dt>
                    <dd class="file_inputs">
                        <label class="file">
                            <input type="file" name="business_registration" accept=".pdf,.jpg,.jpeg,.png">
                            <span>파일선택</span>
                        </label>
                        <div class="file_input">선택된 파일 없음</div>
                        @error('business_registration')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
            </div>
            <p class="ne">세금계산서는 입금 확인 후 3영업일 이내 이메일로 발송됩니다.</p>
        </div>
        @endif

        <div class="btns_tac">
            <a href="javascript:history.back();" class="btn btn_bwb">취소</a>
            <button type="submit" class="btn btn_wbb" @if(!empty($applicationDisabled)) disabled @endif>신청하기</button>
        </div>
    </form>
</main>

@include('member.pop_search_school')

@push('scripts')
<script src="{{ asset('js/education-certification/application-ec-apply.js') }}"></script>
@endpush

@endsection
