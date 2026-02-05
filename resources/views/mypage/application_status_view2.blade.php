@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	@php
		$online = $application->onlineEducation;
		$periodText = '';
		if ($online) {
			if ($online->period_start) {
				$periodText = $online->period_start->format('Y년 n월 j일');
				if ($online->period_time) {
					$periodText .= ', ' . $online->period_time;
				}
			} else {
				$periodText = $online->period_time ?? '';
			}
		}
	@endphp

	<div class="board_view">
		<div class="tit gbox">
			<div class="types"><span class="type type_online">온라인교육</span></div>
			<strong>{{ $online ? $online->name : '' }}</strong>
			<dl class="date dates"><dt>교육기간</dt><dd>{{ $periodText }}</dd></dl>
		</div>

		<div class="con">
			@if($online && ($online->education_overview || $online->curriculum))
				{!! nl2br(e($online->education_overview ?? $online->curriculum ?? '')) !!}
			@else
				{{ '' }}
			@endif
		</div>

		<div class="otit">강의 자료 다운로드</div>
		<div class="download_area type_gbox">
			@if($online && $online->attachments->count() > 0)
				@foreach($online->attachments as $att)
				<a href="{{ $att->path }}" download target="_blank" rel="noopener noreferrer">{{ $att->name }}</a>
				@endforeach
			@else
				<p class="no_data">첨부된 자료가 없습니다.</p>
			@endif
		</div>

		<div class="board_btm btns_tac mt80">
			<a href="{{ route('mypage.application_status') }}" class="btn btn_list">목록</a>
		</div>
	</div>

</main>
@endsection
