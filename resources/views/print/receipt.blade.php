@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
	@php
		$program = $application->program;
		$depositAccount = $program ? ($program->deposit_account ?? '') : '';
		$depositParts = preg_split('/\s+/', $depositAccount, 2);
		$depositBank = $depositParts[0] ?? '';
		$depositNumber = $depositParts[1] ?? $depositAccount;
		$receiptNum = $application->receipt_number ?? $application->application_number ?? '-';
	@endphp
	<div class="print_area" data-pdf-filename="영수증_{{ $receiptNum }}.pdf">
		<div class="print_head">
			<div class="num">[{{ $receiptNum }}]</div>
			<div class="btns" data-html2canvas-ignore>
				<button type="button" class="btn btn_wkk btn_down">PDF 다운</button>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>

		<div class="print_title">영수증</div>

		<div class="admission_ticket_wrap">
			<div class="print_tbl">
				<table>
					<tbody>
						<tr>
							<th>입금자명</th>
							<td>{{ $application->applicant_name ?? '' }}</td>
						</tr>
						<tr>
							<th>입금은행</th>
							<td>{{ $depositBank }}</td>
						</tr>
						<tr>
							<th>계좌번호</th>
							<td>{{ $depositNumber }}</td>
						</tr>
						<tr>
							<th>예금주</th>
							<td>전국대학 연구·산학협력관리자협의회</td>
						</tr>
						<tr>
							<th>입금일시</th>
							<td>{{ $application->payment_date ? $application->payment_date->format('Y.m.d H:i') : '' }}</td>
						</tr>
						<tr>
							<th>입금금액</th>
							<td>{{ $application->participation_fee !== null ? number_format($application->participation_fee) . '원' : '' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="print_btm">
			<div class="date">{{ now()->format('Y년 n월 j일') }}</div>
			<p>위 현황은 사실과 같음을 증명합니다.</p>
			<div class="stemp"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
		</div>
	</div>
	@else
	<div class="print_area">
		<p class="ta_c">유효한 증빙이 없습니다.</p>
	</div>
	@endisset
</main>

@include('print.partials.scripts')

@endsection
