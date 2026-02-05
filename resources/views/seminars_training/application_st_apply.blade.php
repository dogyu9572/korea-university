@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	<div class="stitle tal bdb">세미나 · 해외연수 신청</div>

	<form method="POST" action="{{ route('seminars_training.application_st_apply.store') }}" enctype="multipart/form-data" class="application_form" data-roommate-check-url="{{ route('seminars_training.roommate_check') }}">
		@csrf
		<input type="hidden" name="seminar_training_id" value="{{ $program->id }}">

	<div class="otit">세미나 신청</div>
	<div class="glbox dl_slice">
		<dl>
			<dt>세미나명</dt>
			<dd>{{ $program->name }}</dd>
		</dl>
		<dl>
			<dt>교육기간</dt>
			<dd>
				{{ format_date_ko($program->period_start) }}
				@if($program->period_end)
					~ {{ format_date_ko($program->period_end) }}
				@endif
			</dd>
		</dl>
		<dl>
			<dt>교육시간</dt>
			<dd>{{ $program->period_time ?? '-' }}</dd>
		</dl>
		<dl>
			<dt>수료기준</dt>
			<dd>{{ $program->completion_criteria ?? '추후 안내 예정' }}</dd>
		</dl>
	</div>

	<div class="otit">신청자 정보 입력</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>성명</dt>
			<dd>
				<input type="text" name="applicant_name" class="text w1" value="{{ old('applicant_name', $member->name ?? '') }}" readonly>
			</dd>
		</dl>
		<dl>
			<dt>소속기관</dt>
			<dd>
				<input type="text" name="affiliation" class="text w1" value="{{ old('affiliation', $member->school_name ?? '') }}" readonly>
			</dd>
		</dl>
		<dl>
			<dt>휴대폰번호</dt>
			<dd>
				<input type="text" name="phone_number" class="text w1" value="{{ old('phone_number', $member->phone_number ?? '') }}" readonly>
			</dd>
		</dl>
		<dl>
			<dt>이메일</dt>
			<dd>
				<input type="text" name="email" class="text w1" value="{{ old('email', $member->email ?? '') }}" readonly inputmode="email">
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
											<input type="radio" name="fee_type" value="{{ $item['key'] }}" @checked(old('fee_type', $defaultFeeType) === $item['key'])>
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
	<div class="obtit">룸메이트 선택</div>
	<div class="glbox dl_slice in_inputs">
		<dl>
			<dt>룸메이트 휴대폰번호 입력</dt>
			<dd class="inbtn">
				<input type="hidden" name="roommate_member_id" id="roommate_member_id" value="{{ old('roommate_member_id', '') }}">
				<input type="hidden" name="roommate_name" id="roommate_name" value="{{ old('roommate_name', '') }}">
				<input type="hidden" name="roommate_phone" id="roommate_phone" value="{{ old('roommate_phone', '') }}">
				<input type="text" class="text" id="roommate_phone_input" placeholder="010-1234-5678">
				<button type="button" class="btn" data-action="roommate-search">조회하기</button>
				<p class="ne w100p mt0 hide" id="roommateSuccessMsg">룸메이트가 성공적으로 신청되었습니다.</p>
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
					<td>
						@if($program->payment_methods)
							{{ implode(', ', $program->payment_methods) }}
						@else
							무통장입금 (입금자명 확인 필수)
						@endif
					</td>
				</tr>
				<tr>
					<th>입금계좌</th>
					<td>{{ $program->deposit_account ?? '추후 안내 예정' }}</td>
				</tr>
				<tr>
					<th>입금기한</th>
					<td>
						@if($program->deposit_deadline_days)
							접수일 기준 {{ (int) $program->deposit_deadline_days }}일 이내
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
		<p class="ne">시험 취소는 시험일 3일 전까지 가능하며, 이후에는 환불이 제한됩니다.</p>
		<p class="ne mt0">무통장입금 시 환불은 영업일 기준 3~5일 내 처리됩니다.</p>
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
					<input type="text" name="cash_receipt_number" class="w1" value="{{ old('cash_receipt_number', $member->phone_number ?? '') }}" placeholder="휴대폰번호 또는 사업자등록번호">
				</dd>
			</dl>
		</div>
		<p class="ne">입금 확인 후 국세청으로 발행 처리됩니다.</p>

		<dl class="mt40">
			<dt>세금계산서 발행</dt>
			<dd class="radios">
				<label class="radio">
					<input type="radio" name="has_tax_invoice" value="1" @checked(old('has_tax_invoice', '1') === '1')>
					<i></i><span>발행</span>
				</label>
				<label class="radio">
					<input type="radio" name="has_tax_invoice" value="0" @checked(old('has_tax_invoice') === '0')>
					<i></i><span>미발행</span>
				</label>
			</dd>
		</dl>
		<div class="gbox" data-tax-box>
			<dl>
				<dt>사업자등록번호</dt>
				<dd>
					<input type="text" name="registration_number" class="w1" placeholder="사업자등록번호를 입력해주세요." value="{{ old('registration_number') }}">
				</dd>
			</dl>
			<dl>
				<dt>상호명</dt>
				<dd>
					<input type="text" name="company_name" class="w1" placeholder="상호명을 입력해주세요." value="{{ old('company_name') }}">
				</dd>
			</dl>
			<dl>
				<dt>담당자 정보</dt>
				<dd class="colm">
					<input type="text" name="contact_person_name" class="w1" placeholder="담당자명 입력해주세요." value="{{ old('contact_person_name') }}">
					<input type="text" name="contact_person_email" class="w1" placeholder="이메일을 입력해주세요." value="{{ old('contact_person_email') }}" inputmode="email">
					<input type="text" name="contact_person_phone" class="w1" placeholder="연락처를 입력해주세요." value="{{ old('contact_person_phone') }}">
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

	<!-- 룸메이트 정보 있을 때 -->
	<div class="popup" id="confirmInfo">
		<div class="dm" onclick="layerHide('confirmInfo')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('confirmInfo')">닫기</button>
			<div class="tit check">입력하신 번호로 <br/>등록된 회원이 확인되었습니다.</div>
			<div class="con tac">개인정보 보호를 위해 상세 정보는 표시되지 않습니다. <br/><strong>룸메이트로 신청하시겠습니까?</strong></div>
			<div class="btns_tac">
				<button type="button" class="btn btn_bwb" onclick="layerHide('confirmInfo')">취소</button>
				<button type="button" class="btn btn_wbb" id="btnRoommateApply">룸메이트 신청</button>
			</div>
		</div>
	</div>

	<!-- 룸메이트 정보 없을 때 -->
	<div class="popup" id="noInfo">
		<div class="dm" onclick="layerHide('noInfo')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('noInfo')">닫기</button>
			<div class="tit exclamation">입력하신 번호로 조회되는 <br/>회원 정보가 없습니다.</div>
			<div class="con tac">룸메이트 분이 아직 회원가입을 하지 않으신 경우, <br/><strong>원활한 룸메이트 배정을 위해 회원가입을 권장드립니다.</strong></div>
			<div class="btns_tac">
				<button type="button" class="btn btn_wbb" onclick="layerHide('noInfo')">확인</button>
			</div>
		</div>
	</div>

</main>

@include('member.pop_search_school')

@push('scripts')
<script src="{{ asset('js/seminars_training/application-st-apply.js') }}"></script>
@endpush

@endsection
