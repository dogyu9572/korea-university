@php
	$program = $application->program;
	$certNum = $application->certificate_number ?? $application->application_number ?? '-';
	$birthDate = $application->birth_date ? $application->birth_date->format('Y.m.d') : '-';
	$periodStr = $program ? format_period_ko($program->period_start, $program->period_end) : '-';
	$completionHours = $program && isset($program->completion_hours) ? $program->completion_hours : '-';
	$certificateType = $certificateType ?? '수료증';
	$hoursLabel = $hoursLabel ?? '수료시간';
@endphp
<div class="print_area" data-pdf-filename="{{ $certificateType }}_{{ $certNum }}.pdf">
	<div class="print_head">
		<div class="num">[{{ $certNum }}]</div>
		<div class="btns">
			<button type="button" class="btn btn_wkk btn_down">PDF 다운</button>
			<button type="button" class="btn btn_print btn_kwk">인쇄</button>
		</div>
	</div>

	<div class="certificate_wrap">
		<div class="print_title_intype nnmj"><span>Certificate of Completion</span><strong>{{ $certificateType }}</strong></div>

		<div class="certificate_tbl nnmj">
			<table>
				<tbody>
					<tr>
						<th>과정명</th>
						<td>{{ $program ? $program->name : '' }}</td>
					</tr>
					<tr>
						<th>성명</th>
						<td>{{ $application->applicant_name ?? '' }}</td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td>{{ $birthDate }}</td>
					</tr>
					<tr>
						<th>소속기관</th>
						<td>{{ $application->affiliation ?? '' }}</td>
					</tr>
					<tr>
						<th>교육기간</th>
						<td>{{ $periodStr }}</td>
					</tr>
					<tr>
						<th>교육종류</th>
						<td>{{ $application->education_type_label ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $hoursLabel }}</th>
						<td>{{ $completionHours }}</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="print_btm">
			<div class="date">{{ now()->format('Y년 n월 j일') }}</div>
			<p>위 현황은 사실과 같음을 증명합니다.</p>
			<div class="stemp nnmj"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
		</div>
	</div>
</div>
