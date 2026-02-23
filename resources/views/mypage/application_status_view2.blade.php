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

	<div class="board_view" data-application-id="{{ $application->id }}" data-dwell-url="{{ route('mypage.application_status_view2.dwell', ['id' => $application->id]) }}">
		<div class="tit gbox">
			<div class="types"><span class="type type_online">온라인교육</span></div>
			<strong>{{ $online ? $online->name : '' }}</strong>
			<dl class="date dates"><dt>교육기간</dt><dd>{{ $periodText }}</dd></dl>
		</div>

		<div class="con">
			@if($online && ($online->education_overview || $online->curriculum))
				{!! $online->education_overview ?? $online->curriculum ?? '' !!}
			@else
				{{ '' }}
			@endif
		</div>

		@if($online && $online->lectures->count() > 0)
			@php
				$lecturesWithVideo = $online->lectures->filter(function ($l) {
					return $l->lectureVideo && isset($l->lectureVideo->video_url) && trim((string) $l->lectureVideo->video_url) !== '';
				});
			@endphp
			@if($lecturesWithVideo->count() > 0)
		<div class="otit">강의 영상</div>
		<div class="download_area type_gbox">
				@foreach($lecturesWithVideo as $lecture)
					@php
						$videoUrl = trim((string) $lecture->lectureVideo->video_url);
						$isYoutube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
						if ($isYoutube) {
							if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
								$embedUrl = 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1';
							} else {
								$embedUrl = $videoUrl;
							}
						}
					@endphp
					<div class="lecture-video-item" style="margin-bottom:1rem;">
						@if($isYoutube && isset($embedUrl))
							<div class="lecture-video-wrap" style="position:relative;width:100%;max-width:100%;padding-bottom:56.25%;">
								<iframe src="{{ $embedUrl }}" style="position:absolute;top:0;left:0;width:100%;height:100%;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" title="{{ $lecture->lecture_name }}"></iframe>
							</div>
						@else
							<video src="{{ $videoUrl }}" controls autoplay style="max-width:100%;"></video>
						@endif
					</div>
				@endforeach
		</div>
			@endif
		@endif

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
			<a href="{{ route('mypage.application_status.edit', $application->id) }}" class="btn btn_wbb">수정</a>
			<a href="{{ route('mypage.application_status') }}" class="btn btn_list">목록</a>
		</div>
	</div>

	<script src="{{ asset('js/mypage/online-education-dwell.js') }}"></script>
</main>
@endsection
