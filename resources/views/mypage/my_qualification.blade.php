@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="stitle tal">나의 자격 현황</div>

	<div class="board_top">
		<div class="left">
			<p>TOTAL <strong>{{ $applications->total() }}</strong></p>
		</div>
	</div>

	<ul class="application_list">
		@forelse($applications as $item)
		@php
			$cert = $item->certification;
			$stateClass = match($item->qualification_display_status) {
				'접수완료' => 'complete',
				'합격' => 'pass',
				'불합격' => 'fail',
				default => 'complete',
			};
			$examDateStr = $cert && $cert->exam_date ? $cert->exam_date->format('Y-m-d H:i') : '';
			$admissionUrl = route('mypage.print.admission_ticket', $item->id);
			$receiptUrl = $item->payment_status === '입금완료' ? route('mypage.print.receipt', $item->id) : '';
			$passCertUrl = $item->is_qualification_passed ? route('mypage.print.certificate_qualification', $item->id) : '';
			$qualCertUrl = $item->is_qualification_passed ? route('mypage.print.qualification_certificate', $item->id) : '';
		@endphp
		<li data-admission-ticket-url="{{ $admissionUrl }}"
			data-receipt-url="{{ $receiptUrl }}"
			data-certificate-qualification-url="{{ $passCertUrl }}"
			data-qualification-certificate-url="{{ $qualCertUrl }}">
			<a href="{{ route('mypage.my_qualification_view', $item->id) }}" class="link">
				<span class="statebox"><i class="state {{ $stateClass }}">{{ $item->qualification_display_status }}</i></span>
				<span class="tit">{{ $cert ? $cert->name : '' }}</span>
				<dl>
					<dt>시험일자</dt>
					<dd>{{ $examDateStr }}</dd>
				</dl>
				<dl>
					<dt>유효기간</dt>
					<dd>-</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print">출력</button>
			</div>
		</li>
		@empty
		<li class="empty">자격증 신청 내역이 없습니다.</li>
		@endforelse
	</ul>

	@if($applications->hasPages())
	@php $posts = $applications; @endphp
	@include('notice.partials.pagination')
	@endif

	<!-- 출력 -->
	@include('print.pop_print')

</main>

<script>
document.querySelectorAll('.application_list .btn_print').forEach(function(btn) {
	btn.addEventListener('click', function() {
		var li = this.closest('li');
		if (!li) return;
		var options = document.getElementById('popPrintQualificationOptions');
		if (!options) return;
		var items = [
			{ url: li.dataset.admissionTicketUrl, label: '수험표 출력' },
			{ url: li.dataset.receiptUrl, label: '영수증 출력' },
			{ url: li.dataset.certificateQualificationUrl, label: '합격확인서 출력' },
			{ url: li.dataset.qualificationCertificateUrl, label: '자격증 출력' }
		];
		var html = '';
		items.forEach(function(it) {
			if (it.url) {
				html += '<label class="select"><input type="radio" name="print" data-url="' + it.url + '"><span>' + it.label + '</span></label>';
			}
		});
		options.innerHTML = html || '<p class="msg_error">발급 가능한 증빙이 없습니다.</p>';
		if (typeof layerSlideShow === 'function') layerSlideShow('popPrint');
	});
});
</script>
@endsection
