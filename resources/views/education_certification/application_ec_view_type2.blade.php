@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="board_view">
		<div class="tit">
			<div class="types"><span class="type c3">자격증</span></div>
			<strong>{{ $viewData['name'] }}</strong>
		</div>
	</div>

	<div class="application_view">
		<div class="point" id="start"></div>
		<div class="point" id="end"></div>
		<div class="imgfit"><img src="{{ $viewData['thumb'] }}" alt=""></div>
		<div class="abso_info">
			<div class="tit">{{ $viewData['name'] }}</div>
			<div class="con">
				<dl>
					<dt>구분</dt>
					<dd>자격증</dd>
				</dl>
				<dl>
					<dt>접수기간</dt>
					<dd>{{ $viewData['app_period'] ?: '-' }}</dd>
				</dl>
				<dl>
					<dt>시험일</dt>
					<dd>{{ $viewData['exam_date'] ?: '-' }}</dd>
				</dl>
				<dl>
					<dt>응시자격</dt>
					<dd>{{ $viewData['eligibility'] }}</dd>
				</dl>
				<dl>
					<dt>시험방식</dt>
					<dd>{{ $viewData['exam_method'] }}</dd>
				</dl>
				<dl>
					<dt>정원</dt>
					<dd>{!! $viewData['has_remain'] ? '<strong class="c_iden">' . $viewData['enrolled'] . '명</strong>' : '<strong>' . $viewData['enrolled'] . '명</strong>' !!} / {{ $viewData['capacity_text'] }}</dd>
				</dl>
			</div>
			<a href="{{ $viewData['apply_url'] }}" class="{{ $viewData['btn_class'] }}">{{ $viewData['btn_text'] }}</a>
		</div>

		@if($certification->attachments->count() > 0)
		<div class="otit mt0">첨부파일</div>
		<div class="download_area">
			@foreach($certification->attachments as $att)
			<a href="{{ $att->path }}" download>{{ $att->name }}</a>
			@endforeach
		</div>
		@endif

		<div class="scroll_wrap">
			<ul class="scroll_tabs">
				<li class="on"><button type="button">시험개요</button></li>
				<li><button type="button">출제경향</button></li>
				<li><button type="button">시험장 정보</button></li>
			</ul>
			<div class="view_cont">
				<div class="cont">
					<div class="otit">시험개요</div>
					@if($certification->exam_overview)
					<div class="gbox">{!! $certification->exam_overview !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">출제경향</div>
					@if($certification->exam_trend)
					<div class="gbox">{!! $certification->exam_trend !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">시험장 정보</div>
					@if($certification->exam_venue)
					<div class="gbox">{!! $certification->exam_venue !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
			</div>
		</div>

	</div>

</main>

@push('scripts')
<script src="{{ asset('js/education-certification/application-ec-detail.js') }}"></script>
@endpush
@endsection
