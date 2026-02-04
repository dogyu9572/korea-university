@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle tal bdb">교육 · 자격증 신청</div>

    <form method="POST" action="{{ route('education_certification.application_ec_receipt.store') }}" enctype="multipart/form-data" class="application_form">
        @csrf
        <input type="hidden" name="certification_id" value="{{ $certification->id }}">

        <div class="otit">시험 정보</div>
        <div class="glbox dl_slice">
            <dl>
                <dt>시험명</dt>
                <dd>{{ $certification->name }}</dd>
            </dl>
            <dl>
                <dt>응시자격</dt>
                <dd>{{ strip_tags($certification->eligibility ?? '-') ?: '-' }}</dd>
            </dl>
            <dl>
                <dt>시험일</dt>
                <dd>{{ format_date_ko($certification->exam_date) }}</dd>
            </dl>
            <dl>
                <dt>시험장 선택</dt>
                <dd>
                    <select name="exam_venue_id" class="w1 @error('exam_venue_id') is-invalid @enderror">
                        <option value="">시험장을 선택해주세요.</option>
                        @foreach($examVenues as $venue)
                            <option value="{{ $venue->id }}" @selected(old('exam_venue_id') == $venue->id)>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                    @error('exam_venue_id')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </dd>
            </dl>
        </div>

        <div class="otit">신청자 정보 입력</div>
        <div class="glbox dl_slice in_inputs">
            <dl>
                <dt>성명</dt>
                <dd><input type="text" name="applicant_name" class="text w1" value="{{ old('applicant_name', $member->name) }}" readonly></dd>
            </dl>
            <dl>
                <dt>소속기관</dt>
                <dd>
                    <input type="text" name="affiliation" class="text w1" value="{{ old('affiliation', $member->school_name) }}" readonly>
                </dd>
            </dl>
            <dl>
                <dt>휴대폰번호</dt>
                <dd><input type="text" name="phone_number" class="text w1" value="{{ old('phone_number', $member->phone_number) }}" readonly></dd>
            </dl>
            <dl>
                <dt>이메일</dt>
                <dd><input type="text" name="email" class="text w1" value="{{ old('email', $member->email) }}" readonly inputmode="email"></dd>
            </dl>
            <dl>
                <dt>생년월일</dt>
                <dd>
                    <input type="date" name="birth_date" class="text w1 @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}">
                    @error('birth_date')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </dd>
            </dl>
            <dl>
                <dt>증명사진</dt>
                <dd class="file_inputs">
                    <label class="file"><input type="file" name="id_photo" accept=".jpg,.jpeg,.png"><span>파일선택</span></label>
                    <div class="file_input">선택된 파일 없음</div>
                    @error('id_photo')
                        <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
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
                        <td>{{ $certification->payment_methods ? implode(', ', $certification->payment_methods) : '무통장입금 (입금자명 확인 필수)' }}</td>
                    </tr>
                    <tr>
                        <th>입금계좌</th>
                        <td>{{ $certification->deposit_account ?? '추후 안내 예정' }}</td>
                    </tr>
                    <tr>
                        <th>응시료</th>
                        <td><p class="big"><strong class="c_blue">{{ number_format((float) ($certification->exam_fee ?? 0)) }}원</strong></p></td>
                    </tr>
                    <tr>
                        <th>환불 규정</th>
                        <td>
                            <ul class="dots_list">
                                <li>시험 3일 전까지 취소 시 전액 환불 가능합니다.</li>
                                <li>시험 2일 전~시험 전일 취소 시 50% 환불됩니다.</li>
                                <li>시험 당일 이후 취소 시 환불이 불가합니다.</li>
                                <li>환불 요청 시 7영업일 이내 처리됩니다.</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="otit">증빙서류 발행 여부</div>
        <div class="glbox dl_slice in_inputs dt_long">
            <dl>
                <dt>현금영수증 발행</dt>
                <dd class="radios">
                    <label class="radio">
                        <input type="radio" name="has_cash_receipt" value="1" @checked(old('has_cash_receipt', '1') === '1')>
                        <i></i><span>발행</span>
                    </label>
                    <label class="radio">
                        <input type="radio" name="has_cash_receipt" value="0" @checked(old('has_cash_receipt') === '0')>
                        <i></i><span>미발행</span>
                    </label>
                </dd>
            </dl>
            <div class="gbox" data-cash-box>
                <dl>
                    <dt>용도 선택</dt>
                    <dd class="radios">
                        <label class="radio">
                            <input type="radio" name="cash_receipt_purpose" value="소득공제용" @checked(old('cash_receipt_purpose') === '소득공제용')>
                            <i></i><span>소득공제용</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="cash_receipt_purpose" value="사업자지출증빙용" @checked(old('cash_receipt_purpose') === '사업자지출증빙용')>
                            <i></i><span>사업자 지출증빙용</span>
                        </label>
                    </dd>
                </dl>
                <dl>
                    <dt>발행번호</dt>
                    <dd>
                        <input type="text" name="cash_receipt_number" class="w1 @error('cash_receipt_number') is-invalid @enderror" placeholder="휴대폰번호 또는 사업자등록번호" value="{{ old('cash_receipt_number', $member->phone_number) }}">
                        @error('cash_receipt_number')
                            <p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </dd>
                </dl>
            </div>

            <dl class="mt40">
                <dt>세금계산서 발행</dt>
                <dd class="radios">
                    <label class="radio">
                        <input type="radio" name="has_tax_invoice" value="1" @checked(old('has_tax_invoice', '1') === '1')>
                        <i></i><span>발행</span>
                    </label>
                    <label class="radio">
                        <input type="radio" name="has_tax_invoice" value="0" @checked(old('has_tax_invoice', '1') === '0')>
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
                        <input type="text" name="contact_person_phone" class="w1 @error('contact_person_phone') is-invalid @enderror" placeholder="연락처를 입력해주세요." value="{{ old('contact_person_phone') }}">
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

        <div class="btns_tac">
            <a href="javascript:history.back();" class="btn btn_bwb">취소</a>
            <button type="submit" class="btn btn_wbb" @if(!empty($applicationDisabled)) disabled @endif>접수 신청</button>
        </div>
    </form>
</main>

@include('member.pop_search_school')

@push('scripts')
<script src="{{ asset('js/education-certification/application-ec-apply.js') }}"></script>
@endpush

@endsection
