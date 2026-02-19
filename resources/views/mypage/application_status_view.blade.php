@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="stitle tal bdb">교육 신청 현황
		<div class="btns_abso pc_vw">
			@if($application->payment_status === '입금완료')
			<a href="{{ route('mypage.print.receipt', $application->id) }}" target="_blank" class="btn btn_wbb">영수증 출력</a>
			@endif
			@if($application->is_completed && $application->is_survey_completed)
			@php $certType = $application->program ? ($application->program->certificate_type ?? '이수증') : '이수증'; @endphp
			@if($certType === '수료증')
			<a href="{{ route('mypage.print.certificate_completion', $application->id) }}" target="_blank" class="btn btn_bwb">수료증 출력</a>
			@else
			<a href="{{ route('mypage.print.certificate_finish', $application->id) }}" target="_blank" class="btn btn_bwb">이수증 출력</a>
			@endif
			@endif
		</div>
		<div class="btns_abso mo_vw">
			@if($application->payment_status === '입금완료' || ($application->is_completed && $application->is_survey_completed))
			<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
			@endif
		</div>
	</div>

	@php
		$program = $application->program;
		$statusClass = match($application->display_status) {
			'수료' => 'complete',
			'미수료' => 'incomplete',
			default => 'appli',
		};
		$typeClass = match($application->education_type_label) {
			'세미나' => 'semina',
			'해외연수' => 'semina',
			default => 'regular',
		};
	@endphp

	<!-- 공통사항 -->
	<div class="otit">교육 신청 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>교육구분</dt>
			<dd><i class="type {{ $typeClass }}">{{ $application->education_type_label }}</i></dd>
		</dl>
		<dl>
			<dt>교육명</dt>
			<dd>{{ $program ? $program->name : '' }}</dd>
		</dl>
		<dl>
			<dt>교육기간</dt>
			<dd>{{ $program ? format_period_ko($program->period_start, $program->period_end) : '' }}</dd>
		</dl>
		<dl>
			<dt>신청일</dt>
			<dd>{{ $application->application_date ? $application->application_date->format('Y.m.d') : '' }}</dd>
		</dl>
		<dl>
			<dt>신청번호</dt>
			<dd>{{ $application->application_number ?? '' }}</dd>
		</dl>
		<dl>
			<dt>수료일</dt>
			<dd>{{ $application->is_completed && $application->completed_at ? $application->completed_at->format('Y.m.d') : '' }}</dd>
		</dl>
		<dl>
			<dt>상태</dt>
			<dd><i class="state {{ $statusClass }}">{{ $application->display_status }}</i></dd>
		</dl>
	</div>

	<div class="otit">교육 신청자 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>성명</dt>
			<dd>{{ $application->applicant_name ?? '' }}</dd>
		</dl>
		<dl>
			<dt>소속기관</dt>
			<dd>{{ $application->affiliation ?? '' }}</dd>
		</dl>
		<dl>
			<dt>휴대폰번호</dt>
			<dd>{{ $application->phone_number ?? '' }}</dd>
		</dl>
		<dl>
			<dt>이메일</dt>
			<dd>{{ $application->email ?? '' }}</dd>
		</dl>
		<dl>
			<dt>환불 계좌 정보</dt>
			<dd>
				@if($application->refund_account_holder || $application->refund_bank_name || $application->refund_account_number)
					{{ $application->refund_account_holder ?? '' }} {{ $application->refund_bank_name ?? '' }} {{ $application->refund_account_number ?? '' }}
				@else
					{{ '' }}
				@endif
			</dd>
		</dl>
	</div>
	<!-- //공통사항 -->

	@if($application->seminar_training_id && !in_array($application->fee_type, ['member_single', 'member_no_stay', 'guest_single', 'guest_no_stay']))
	<!-- 세미나만 노출 (2인1실일 때만 룸메이트 정보 표시) -->
	<div class="otit">세미나/해외연수 룸메이트 정보</div>
	<div class="roommate_info">
		@if($application->roommate_name || $application->roommate_phone)
		<dl class="i2">
			<dt>룸메이트</dt>
			<dd>{{ $application->roommate_name ?? '' }} {{ $application->roommate_phone ? '(' . $application->roommate_phone . ')' : '' }}</dd>
		</dl>
		@else
		<dl>
			<dt>룸메이트</dt>
			<dd>{{ '' }}</dd>
		</dl>
		@endif
	</div>
	<!-- //세미나만 노출 -->
	@endif

	<!-- 공통사항 -->
	<div class="otit">결제/입금 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>결제상태</dt>
			<dd>
				<i class="deposit {{ $application->payment_status === '입금완료' ? 'completed' : 'not' }}">{{ $application->payment_status ?? '미입금' }}</i>
			</dd>
		</dl>
		<dl>
			<dt>결제금액</dt>
			<dd>{{ $application->participation_fee !== null ? number_format($application->participation_fee) . '원' : '' }}</dd>
		</dl>
		<dl>
			<dt>입금계좌</dt>
			<dd>{{ $program && $program->deposit_account ? $program->deposit_account : '' }}</dd>
		</dl>
		<dl>
			<dt>입금자명</dt>
			<dd>{{ $application->applicant_name ?? '' }}</dd>
		</dl>
		<dl>
			<dt>입금일자</dt>
			<dd>{{ $application->payment_date ? $application->payment_date->format('Y.m.d') : '' }}</dd>
		</dl>
	</div>

	<div class="otit mb0">증빙서류 발행 여부</div>
	<div class="obtit">현금영수증 발행 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>용도</dt>
			<dd>{{ $application->cash_receipt_purpose ?? '' }}</dd>
		</dl>
		<dl>
			<dt>발행 번호</dt>
			<dd>{{ $application->cash_receipt_number ?? '' }}</dd>
		</dl>
		<dl>
			<dt>발행 상태</dt>
			<dd>{{ $application->payment_status === '입금완료' ? '발행완료' : '신청완료' }}</dd>
		</dl>
	</div>
	<div class="obtit">세금계산서 발행 정보</div>
	<div class="glbox dl_tbl dt_long">
		<dl>
			<dt>사업자등록번호</dt>
			<dd>{{ $application->registration_number ?? '' }}</dd>
		</dl>
		<dl>
			<dt>상호명</dt>
			<dd>{{ $application->company_name ?? '' }}</dd>
		</dl>
		<dl>
			<dt>담당자명</dt>
			<dd>{{ $application->contact_person_name ?? '' }}</dd>
		</dl>
		<dl>
			<dt>담당자 이메일</dt>
			<dd>{{ $application->contact_person_email ?? '' }}</dd>
		</dl>
		<dl>
			<dt>담당자 연락처</dt>
			<dd>{{ $application->contact_person_phone ?? '' }}</dd>
		</dl>
		<dl>
			<dt>발행 상태</dt>
			<dd>{{ $application->payment_status === '입금완료' ? '발행완료' : '신청완료' }}</dd>
		</dl>
	</div>
	<!-- //공통사항 -->

	@if($application->seminar_training_id && $program && $program->survey_url)
	<!-- 세미나만 노출 -->
	<div class="otit">교육 설문 제출</div>
	<div class="glbox dl_tbl">
		<dl class="aic">
			<dt>설문 참여 링크</dt>
			<dd><a href="{{ $program->survey_url }}" target="_blank" rel="noopener noreferrer" class="btn_outlink">{{ $program->survey_url }}</a></dd>
		</dl>
	</div>
	<!-- //세미나만 노출 -->
	@endif

	<div class="btns_tac">
		<a href="{{ route('mypage.application_status') }}" class="btn btn_bwb">목록</a>
	</div>

	<!-- 출력 -->
	@include('print.pop_print', ['application' => $application])

</main>
@endsection
