@extends('layouts.app')
@section('content')
<main class="sub_wrap inner" id="application-st-list">
	<div class="stitle tal">세미나 · 해외연수 신청</div>

	@if(session('error'))
	<p class="msg_error" style="margin-bottom:1rem;" id="application-st-error-msg">{{ session('error') }}</p>
	@endif

	@php
		$currentTab = request('tab', 'all');
		$currentSort = request('sort', 'created_at');
		$tabCounts = $tabCounts ?? ['all' => 0, 'seminar' => 0, 'overseas' => 0];
	@endphp

	<div class="search_wrap">
		<form method="GET" action="{{ route('seminars_training.application_st') }}" id="searchForm">
			<input type="hidden" name="tab" value="{{ $currentTab }}">
			<input type="hidden" name="sort" value="{{ $currentSort }}">
			<dl>
				<dt>프로그램명</dt>
				<dd><input type="text" name="name" class="text w100p" placeholder="프로그램명을 입력해주세요." value="{{ request('name') }}"></dd>
			</dl>
			<div class="filter_opcl on">
				<button type="button" class="btn_opcl">검색 필터</button>
				<div class="con" style="display:block;">
					<div class="flex">
						<dl>
							<dt>과정구분</dt>
							<dd>
								<select name="type" class="w100p">
									<option value="">전체</option>
									<option value="세미나" @selected(request('type') === '세미나')>세미나</option>
									<option value="해외연수" @selected(request('type') === '해외연수')>해외연수</option>
								</select>
							</dd>
						</dl>
						<dl>
							<dt>기간구분</dt>
							<dd class="dates">
								<select name="date_type">
									<option value="">전체</option>
									<option value="application" @selected(request('date_type') === 'application')>신청기간</option>
									<option value="education" @selected(request('date_type') === 'education')>교육기간</option>
								</select>
								<select name="period_year">
									<option value="">연도</option>
									@foreach($periodYears ?? [now()->year + 1, now()->year, now()->year - 1] as $y)
									<option value="{{ $y }}" @selected(request('period_year') == $y)>{{ $y }}</option>
									@endforeach
								</select>
								<select name="period_month">
									<option value="">전체</option>
									@for($m = 1; $m <= 12; $m++)
									<option value="{{ sprintf('%02d', $m) }}" @selected(request('period_month') == sprintf('%02d', $m))>{{ $m }}월</option>
									@endfor
								</select>
							</dd>
						</dl>
						<dl>
							<dt>진행상태</dt>
							<dd>
								<select name="application_status" class="w100p">
									<option value="">전체</option>
									<option value="접수중" @selected(request('application_status') === '접수중')>접수중</option>
									<option value="접수마감" @selected(request('application_status') === '접수마감')>접수마감</option>
									<option value="접수예정" @selected(request('application_status') === '접수예정')>접수예정</option>
								</select>
							</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="btns_tac mt0">
				<button type="submit" class="btn btn_search btn_wbb btn_w160">검색</button>
				<button type="button" class="btn btn_reset btn_bwb btn_w160">초기화</button>
			</div>
		</form>
	</div>

	<ul class="round_tabs mb80">
		<li class="{{ $currentTab === 'all' ? 'on' : '' }}"><a href="{{ route('seminars_training.application_st', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}">전체({{ $tabCounts['all'] }}개)</a></li>
		<li class="{{ $currentTab === 'seminar' ? 'on' : '' }}"><a href="{{ route('seminars_training.application_st', array_merge(request()->except('tab', 'page'), ['tab' => 'seminar'])) }}">세미나 신청({{ $tabCounts['seminar'] }}개)</a></li>
		<li class="{{ $currentTab === 'overseas' ? 'on' : '' }}"><a href="{{ route('seminars_training.application_st', array_merge(request()->except('tab', 'page'), ['tab' => 'overseas'])) }}">해외연수 신청({{ $tabCounts['overseas'] }}개)</a></li>
	</ul>

	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>{{ $programs->total() }}</strong></p>
			<p>PAGE <span><strong>{{ $programs->currentPage() }}</strong>/{{ $programs->lastPage() ?: 1 }}</span></p>
		</div>
		<div class="right list_filter">
			<a href="{{ route('seminars_training.application_st', array_merge(request()->query(), ['sort' => 'created_at'])) }}" class="{{ $currentSort === 'created_at' ? 'on' : '' }}">최신 등록순</a>
			<a href="{{ route('seminars_training.application_st', array_merge(request()->query(), ['sort' => 'application_start'])) }}" class="{{ $currentSort === 'application_start' ? 'on' : '' }}">신청 기간순</a>
		</div>
	</div>

	<div class="thum_list">
		@forelse($programs as $item)
			@include('seminars_training.partials._card_seminar_training', ['item' => $item])
		@empty
		<p class="tac" style="padding:60px 0; color:#6D7882;">등록된 프로그램이 없습니다.</p>
		@endforelse
	</div>

	@include('seminars_training.partials.pagination')
</main>

<script>
$(".filter_opcl .btn_opcl").click(function(){
	$(this).next(".con").slideToggle("fast").parent().toggleClass("on");
});
$('.btn_reset').on('click', function () {
	location.href = '{{ route("seminars_training.application_st", ["tab" => $currentTab]) }}';
});
function toggleFilterOn() {
	if ($(window).width() >= 768) {
		$('.filter_opcl').addClass('on');
		$(".search_wrap .filter_opcl .con").show();
	} else {
		$('.filter_opcl').removeClass('on');
		$(".search_wrap .filter_opcl .con").hide();
	}
}
toggleFilterOn();
$(window).on('resize', toggleFilterOn);
</script>
@push('scripts')
<script src="{{ asset('js/seminars_training/application-st.js') }}"></script>
@endpush
@endsection
