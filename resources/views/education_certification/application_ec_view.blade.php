@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="board_view">
		<div class="tit">
			<div class="types"><span class="type {{ $viewData['type_class'] }}">{{ $viewData['education_type'] }}</span></div>
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
					<dd>{{ $viewData['education_type'] }}</dd>
				</dl>
				<dl>
					<dt>신청기간</dt>
					<dd>{{ $viewData['app_period'] ?: '-' }}</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>{{ $viewData['period_text'] }}</dd>
				</dl>
				<dl>
					<dt>총차시/기수</dt>
					<dd>{{ $viewData['education_class'] }}</dd>
				</dl>
				<dl>
					<dt>합숙여부</dt>
					<dd>{{ $viewData['accommodation_text'] }}</dd>
				</dl>
				<dl>
					<dt>정원</dt>
					<dd>{!! $viewData['has_remain'] ? '<strong class="c_iden">' . $viewData['enrolled'] . '명</strong>' : '<strong>' . $viewData['enrolled'] . '명</strong>' !!} / {{ $viewData['capacity_text'] }}</dd>
				</dl>
			</div>
			<div class="btns">
				<a href="{{ $viewData['apply_url'] }}" class="{{ $viewData['btn_class'] }}">{{ $viewData['btn_text'] }}</a>
			</div>
		</div>

		@if($education->attachments->count() > 0)
		<div class="otit mt0">첨부파일</div>
		<div class="download_area">
			@foreach($education->attachments as $att)
			<a href="{{ $att->path }}" download>{{ $att->name }}</a>
			@endforeach
		</div>
		@endif

		<div class="scroll_wrap">
			<ul class="scroll_tabs">
				<li class="on"><button type="button">교육개요</button></li>
				<li><button type="button">교육일정</button></li>
				<li><button type="button">참가비 및 납부 안내</button></li>
				<li><button type="button">취소 및 환불 규정</button></li>
				<li><button type="button">교과내용</button></li>
				<li><button type="button">교육안내</button></li>
			</ul>
			<div class="view_cont">
				<div class="cont">
					<div class="otit">교육개요</div>
					@if($education->education_overview)
					<div class="gbox">{!! $education->education_overview !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">교육일정</div>
					@if($education->education_schedule)
					<div class="gbox">{!! $education->education_schedule !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">교육 참가비 및 납부 안내</div>
					@if($education->fee_info)
					<div class="gbox">{!! $education->fee_info !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">교육 취소 및 환불 규정</div>
					@if($education->refund_policy)
					<div class="gbox">{!! $education->refund_policy !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">교과내용</div>
					@if($education->curriculum)
					<div class="gbox">{!! $education->curriculum !!}</div>
					@else
					<div class="gbox"><p>등록된 내용이 없습니다.</p></div>
					@endif
				</div>
				<div class="cont">
					<div class="otit">교육안내</div>
					@if($education->education_notice)
					<div class="gbox">{!! $education->education_notice !!}</div>
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
