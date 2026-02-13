@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal bdb">교육 · 자격증 신청</div>

	<form method="POST" action="{{ route('education_certification.application_ec_apply.store') }}" enctype="multipart/form-data" class="application_form" @if($errors->any()) data-join-errors="1" @endif>
		@csrf
		<input type="hidden" name="education_id" value="{{ $education->id }}">
		<input type="hidden" id="memberSchoolType" value="{{ $memberSchoolType ?? '' }}">

		<div class="otit">교육 신청</div>
		<div class="glbox dl_slice">
			<dl>
				<dt>교육명</dt>
				<dd>{{ $education->name }}</dd>
			</dl>
			<dl>
				<dt>교육기간</dt>
				<dd>
					{{ format_date_ko($education->period_start) }}
					@if($education->period_end)
						~ {{ format_date_ko($education->period_end) }}
					@endif
				</dd>
			</dl>
			<dl>
				<dt>교육시간</dt>
				<dd>{{ $education->period_time ?? '-' }}</dd>
			</dl>
			<dl>
				<dt>수료기준</dt>
				<dd>{{ $education->completion_criteria ?? '추후 안내 예정' }}</dd>
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
			@php
				$columnLabels = !empty($feeOptions) ? collect($feeOptions[0]['items'])->pluck('label')->values()->all() : [];
				$defaultFeeType = !empty($feeOptions[0]['items']) ? $feeOptions[0]['items'][0]['key'] : null;
			@endphp
			@if(empty($feeOptions) || empty($columnLabels))
				<p class="no_data">참가비 정보가 등록되지 않았습니다.</p>
			@else
				<table>
					<colgroup>
						<col class="w240">
						@foreach($columnLabels as $label)
							<col>
						@endforeach
					</colgroup>
					<thead>
						<tr>
							<th>구분</th>
							@foreach($columnLabels as $label)
								<th class="tac">{{ $label }}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($feeOptions as $group)
							<tr>
								<th>{{ $group['label'] }}</th>
								@foreach($columnLabels as $colLabel)
									@php
										$item = collect($group['items'])->firstWhere('label', $colLabel);
									@endphp
									<td class="tac">
										@if($item)
											<label class="radio">
												<input
													type="radio"
													name="fee_type"
													value="{{ $item['key'] }}"
													data-fee-group="{{ str_starts_with($item['key'], 'member_') ? 'member' : (str_starts_with($item['key'], 'guest_') ? 'guest' : '') }}"
													@checked(old('fee_type', $defaultFeeType) === $item['key'])
												>
												<i></i>
												<span><strong>{{ $item['display_amount'] }}원</strong></span>
											</label>
										@else
											-
										@endif
									</td>
								@endforeach
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
			@error('fee_type')
				<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
			@enderror
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
						<td>
							@if($education->payment_methods)
								{{ implode(', ', $education->payment_methods) }}
							@else
								무통장입금
							@endif
						</td>
					</tr>
					<tr>
						<th>입금계좌</th>
						<td>{{ $education->deposit_account ?? '추후 안내 예정' }}</td>
					</tr>
					<tr>
						<th>입금기한</th>
						<td>
							@if($education->deposit_deadline_days)
								접수일 기준 {{ (int) $education->deposit_deadline_days }}일 이내
							@else
								추후 안내 예정
							@endif
						</td>
					</tr>
					@if(!empty($refundPolicies))
						<tr>
							<th>환불 규정</th>
							<td class="intbl">
								<table class="tbl_default tbl_tac">
									<thead>
										<tr>
											<th>구분</th>
											<th>수수료</th>
											<th>무료 취소 기한</th>
										</tr>
									</thead>
									<tbody>
										@foreach($refundPolicies as $policy)
											<tr>
												<th>{{ $policy['label'] }}</th>
												<td class="refund01">{{ $policy['fee'] }}원</td>
												<td class="refund02">{{ $policy['deadline'] }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</td>
						</tr>
					@endif
				</tbody>
			</table>
			<p class="ne">무통장입금 시 환불은 영업일 기준 3~5일 내 처리됩니다.</p>
		</div>
		
		<div class="otit">증빙서류 발행 여부</div>
		<div class="glbox dl_slice in_inputs dt_long">
			<dl>
				<dt>현금영수증 발행</dt>
				<dd class="radios">
					<label class="radio">
						<input type="radio" name="has_cash_receipt" value="1" @checked(old('has_cash_receipt') === '1')>
						<i></i><span>발행</span>
					</label>
					<label class="radio">
						<input type="radio" name="has_cash_receipt" value="0" @checked(old('has_cash_receipt', '0') === '0')>
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
						@error('cash_receipt_purpose')
							<p class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p>
						@enderror
					</dd>
				</dl>
				<dl>
					<dt>발행번호</dt>
					<dd>
						<input type="text" name="cash_receipt_number" class="w1 @error('cash_receipt_number') is-invalid @enderror" value="{{ old('cash_receipt_number', $member->phone_number) }}" placeholder="휴대폰번호 또는 사업자등록번호">
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
			<button type="submit" class="btn btn_wbb" @if(!empty($applicationDisabled)) disabled @endif>수강 신청</button>
		</div>
	</form>
</main>

@include('member.pop_search_school')

@push('scripts')
<script src="{{ asset('js/education-certification/application-ec-apply.js') }}"></script>
@endpush

@endsection
