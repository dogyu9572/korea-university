@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
	@php
		$cert = $application->certification;
		$issueNumber = $application->qualification_certificate_number ?? $application->application_number ?? '';
		$issueDate = now()->format('Y.m.d');
		$validityStr = '-';
		if ($cert && $cert->exam_date) {
			$validityEnd = $cert->exam_date->copy()->addYears(6);
			$validityStr = $cert->exam_date->format('Y.m.d') . ' ~ ' . $validityEnd->format('Y.m.d');
		}
	@endphp
	<div class="print_area" data-pdf-filename="자격증_{{ $issueNumber }}.pdf">
		<div class="print_head">
			<div class="num">[{{ $issueNumber }}]</div>
			<div class="btns" data-html2canvas-ignore>
				<button type="button" class="btn btn_wkk btn_down">PDF 다운</button>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>

		<div class="certificate_wrap qualification_wrap">
			<div class="print_title_intype nnmj"><span>Certificate of Certification</span><strong>자격 확인서</strong></div>

			<div class="certificate_tbl nnmj">
				<table>
					<tbody>
						<tr>
							<th>발급번호</th>
							<td>{{ $issueNumber }}</td>
						</tr>
						<tr>
							<th>발급일</th>
							<td>{{ $issueDate }}</td>
						</tr>
						<tr>
							<th>유효기간</th>
							<td>{{ $validityStr }}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="print_btm">
				<div class="date">{{ now()->format('Y년 m월 d일') }}</div>
				<p>위 현황은 사실과 같음을 증명합니다.</p>
				<div class="stemp nnmj"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
			</div>
		</div>
	</div>
	@else
	<div class="print_area">
		<div class="print_head">
			<div class="num">[KS-123456789]</div>
			<div class="btns" data-html2canvas-ignore>
				<a href="#this" class="btn btn_wkk btn_down">PDF 다운</a>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>

		<div class="certificate_wrap qualification_wrap">
			<div class="print_title_intype nnmj"><span>Certificate of Certification</span><strong>자격 확인서</strong></div>

			<div class="certificate_tbl nnmj">
				<table>
					<tbody>
						<tr><th>발급번호</th><td>DR2026-00123</td></tr>
						<tr><th>발급일</th><td>2026.05.25</td></tr>
						<tr><th>유효기간</th><td>2026.05.25 ~ 2029.05.25</td></tr>
					</tbody>
				</table>
			</div>

			<div class="print_btm">
				<div class="date">YYYY년 MM월 DD일</div>
				<p>위 현황은 사실과 같음을 증명합니다.</p>
				<div class="stemp nnmj"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
			</div>
		</div>
	</div>
	@endisset
</main>
@include('print.partials.scripts')
@endsection
