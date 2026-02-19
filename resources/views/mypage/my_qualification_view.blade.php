@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="stitle tal bdb">나의 자격 현황
		<div class="btns_abso pc_vw">
			<a href="{{ route('mypage.print.admission_ticket', $application->id) }}" target="_blank" class="btn btn_wbb">수험표 출력</a>
			@if($application->payment_status === '입금완료')
			<a href="{{ route('mypage.print.receipt', $application->id) }}" target="_blank" class="btn btn_wbb">영수증 출력</a>
			@endif
			@if($application->is_qualification_passed)
			<a href="{{ route('mypage.print.certificate_qualification', $application->id) }}" target="_blank" class="btn btn_bwb">합격확인서 출력</a>
			<a href="{{ route('mypage.print.qualification_certificate', $application->id) }}" target="_blank" class="btn btn_bwb">자격증 출력</a>
			@endif
		</div>
		<div class="btns_abso mo_vw">
			<button type="button" class="btn btn_wbb btn_print" onclick="layerSlideShow('popPrint')">출력</button>
		</div>
	</div>

	@php
		$cert = $application->certification;
		$examDateStr = $cert && $cert->exam_date ? $cert->exam_date->format('Y-m-d H:i') : '';
		$venueName = $application->examVenue ? $application->examVenue->name : ($cert && $cert->exam_venue ? $cert->exam_venue : '');
	@endphp
	<div class="otit">응시 및 자격 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>시험명</dt>
			<dd>{{ $cert ? $cert->name : '' }}</dd>
		</dl>
		<dl>
			<dt>시험일자</dt>
			<dd>{{ $examDateStr }}</dd>
		</dl>
		<dl>
			<dt>접수번호</dt>
			<dd>{{ $application->application_number ?? '' }}</dd>
		</dl>
		<dl>
			<dt>상태</dt>
			<dd>{{ $application->qualification_display_status }}</dd>
		</dl>
		<dl>
			<dt>시험장 정보</dt>
			<dd>{{ $venueName }}</dd>
		</dl>
		<dl>
			<dt>유효기간</dt>
			<dd>-</dd>
		</dl>
		<dl>
			<dt>인증기관</dt>
			<dd>전국대학연구·산학협력관리자 협회</dd>
		</dl>
		<dl>
			<dt>점수</dt>
			<dd>@if($application->score !== null)<strong>{{ $application->score }}점</strong>@else - @endif</dd>
		</dl>
	</div>

	<div class="otit">결제/입금 정보</div>
	<div class="glbox dl_tbl">
		<dl>
			<dt>결제상태</dt>
			<dd>
				@if($application->payment_status === '입금완료')
				<i class="deposit completed">입금완료</i>
				@else
				<i class="deposit not">미결제</i>
				@endif
			</dd>
		</dl>
		<dl>
			<dt>결제금액</dt>
			<dd>{{ $application->participation_fee !== null ? number_format((float) $application->participation_fee) . '원' : '' }}</dd>
		</dl>
		<dl>
			<dt>입금계좌</dt>
			<dd>{{ $cert && $cert->deposit_account ? $cert->deposit_account : '' }}</dd>
		</dl>
		<dl>
			<dt>입금일자</dt>
			<dd>{{ $application->payment_date ? $application->payment_date->format('Y.m.d H:i') : '' }}</dd>
		</dl>
	</div>

	<div class="otit">시험 응시 유의사항</div>
	<ul class="gbox dots_list">
		<li>시험 당일 수험표와 신분증을 반드시 지참해야 합니다.</li>
		<li>시험 시작 20분 전까지 입실해야 합니다.</li>
		<li>시험 중 휴대폰 사용 시 즉시 실격 처리됩니다.</li>
	</ul>

	<div class="btns_tac">
		<a href="{{ route('mypage.my_qualification') }}" class="btn btn_bwb">목록</a>
	</div>

	<!-- 출력 (상세에서는 $application 전달로 pop_print에 옵션 표시) -->
	@include('print.pop_print')

</main>
@endsection
