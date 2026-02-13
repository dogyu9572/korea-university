@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
	@php
		$cert = $application->certification;
		$birthStr = $application->birth_date ? $application->birth_date->format('Y.m.d') : '';
		$examDateStr = $cert && $cert->exam_date ? $cert->exam_date->format('Y.m.d') : '';
		$num = $application->pass_confirmation_number ?? $application->qualification_certificate_number ?? $application->application_number ?? '';
	@endphp
	<div class="print_area" data-pdf-filename="합격확인서_{{ $num }}.pdf">
		<div class="print_head">
			<div class="num">[{{ $num }}]</div>
			<div class="btns" data-html2canvas-ignore>
				<button type="button" class="btn btn_wkk btn_down">PDF 다운</button>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>

		<div class="certificate_wrap">
			<div class="print_title_intype nnmj"><span>Certificate of Completion</span><strong>합격확인서</strong></div>

			<div class="certificate_tbl nnmj">
				<table>
					<tbody>
						<tr>
							<th>과정명</th>
							<td>{{ $cert ? $cert->name : '' }}</td>
						</tr>
						<tr>
							<th>성명</th>
							<td>{{ $application->applicant_name ?? '' }}</td>
						</tr>
						<tr>
							<th>생년월일</th>
							<td>{{ $birthStr }}</td>
						</tr>
						<tr>
							<th>소속기관</th>
							<td>{{ $application->affiliation ?? '' }}</td>
						</tr>
						<tr>
							<th>시험일자</th>
							<td>{{ $examDateStr }}</td>
						</tr>
						<tr>
							<th>점수</th>
							<td>{{ $application->score !== null ? $application->score . '점' : '' }}</td>
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

		<div class="certificate_wrap">
			<div class="print_title_intype nnmj"><span>Certificate of Completion</span><strong>합격확인서</strong></div>

			<div class="certificate_tbl nnmj">
				<table>
					<tbody>
						<tr><th>과정명</th><td>과정명입니다.</td></tr>
						<tr><th>성명</th><td>홍길동</td></tr>
						<tr><th>생년월일</th><td>2000.01.01</td></tr>
						<tr><th>소속기관</th><td>서울대학교</td></tr>
						<tr><th>교육기간</th><td>2025.11.13. ~ 2025.11.16</td></tr>
						<tr><th>교육종류</th><td>정기교육</td></tr>
						<tr><th>이수시간</th><td>15시간(900분)</td></tr>
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
