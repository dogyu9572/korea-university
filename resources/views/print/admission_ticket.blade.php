@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
	@php
		$cert = $application->certification;
		$examDateStr = $cert && $cert->exam_date ? $cert->exam_date->format('Y.m.d') . ' ' . $cert->exam_date->format('H:i') : '';
		$venueName = $application->examVenue ? $application->examVenue->name : ($cert && $cert->exam_venue ? $cert->exam_venue : '');
		$birthStr = $application->birth_date ? $application->birth_date->format('Y.m.d') : '';
		$num = $application->exam_ticket_number ?? $application->application_number ?? '';
	@endphp
	<div class="print_area" data-pdf-filename="수험표_{{ $num }}.pdf">
		<div class="print_head">
			<div class="num">[{{ $num }}]</div>
			<div class="btns" data-html2canvas-ignore>
				<button type="button" class="btn btn_wkk btn_down">PDF 다운</button>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>

		<div class="print_title">수험표</div>

		<div class="admission_ticket_wrap">
			<div class="print_tbl">
				<table>
					<tbody>
						<tr>
							<th>자격증명</th>
							<td>{{ $cert ? $cert->name : '' }}</td>
						</tr>
						<tr>
							<th>시험일자</th>
							<td>{{ $examDateStr }}</td>
						</tr>
						<tr>
							<th>시험장</th>
							<td>{{ $venueName }}</td>
						</tr>
						<tr>
							<th>수험번호</th>
							<td>{{ $num }}</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td>{{ $application->email ?? '' }}</td>
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
					</tbody>
				</table>
			</div>
			@php
				$path = trim((string) ($application->id_photo_path ?? ''));
				$idPhotoUrl = null;
				if ($path !== '') {
					if (str_contains($path, '/storage/')) {
						$path = preg_replace('#^.*/storage/#', '', parse_url($path, PHP_URL_PATH) ?? $path);
					} elseif (str_starts_with($path, '/')) {
						$path = ltrim($path, '/');
						if (str_starts_with($path, 'storage/')) {
							$path = substr($path, 8);
						}
					}
					if ($path !== '') {
						$idPhotoUrl = asset('storage/' . $path);
					}
				}
			@endphp
			@if($idPhotoUrl)
			<div class="img"><img src="{{ $idPhotoUrl }}" alt="증명사진"></div>
			@endif
		</div>

		<div class="print_btm">
			<div class="date">{{ now()->format('Y년 m월 d일') }}</div>
			<p>위 현황은 사실과 같음을 증명합니다.</p>
			<div class="stemp"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
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

		<div class="print_title">수험표</div>

		<div class="admission_ticket_wrap">
			<div class="print_tbl">
				<table>
					<tbody>
						<tr><th>자격증명</th><td>대학연구행정전문가 1급</td></tr>
						<tr><th>시험 회차</th><td>2026년 제1회</td></tr>
						<tr><th>시험일자</th><td>2026.05.12(화) 10:00~12:00</td></tr>
						<tr><th>시험장</th><td>서울대학교 사범대 103호 (좌석번호 : A-12)</td></tr>
						<tr><th>수험번호</th><td>2026-1-00123</td></tr>
						<tr><th>이메일</th><td>test1234@naver.com</td></tr>
						<tr><th>성명</th><td>홍길동</td></tr>
						<tr><th>생년월일</th><td>2000.01.01</td></tr>
						<tr><th>소속기관</th><td>서울대학교</td></tr>
					</tbody>
				</table>
			</div>
			<div class="img"><img src="/images/sample.jpg" alt=""></div>
		</div>

		<div class="print_btm">
			<div class="date">YYYY년 MM월 DD일</div>
			<p>위 현황은 사실과 같음을 증명합니다.</p>
			<div class="stemp"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
		</div>
	</div>
	@endisset
</main>
@include('print.partials.scripts')
@endsection
