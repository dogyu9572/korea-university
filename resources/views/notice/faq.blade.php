@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="board_search">
		<form method="GET" action="{{ route('notice.faq') }}" class="search-form">
			@if(request('category'))
			<input type="hidden" name="category" value="{{ request('category') }}">
			@endif
			<select name="search_type" class="text">
				<option value="">전체</option>
				<option value="title" @selected(request('search_type') == 'title')>제목</option>
				<option value="content" @selected(request('search_type') == 'content')>내용</option>
			</select>
			<input type="text" name="keyword" class="text" placeholder="검색어를 입력해주세요." value="{{ request('keyword') }}">
			<button type="submit" class="btn btn_search btn_wbb">검색</button>
		</form>
	</div>

	@php
		$categories = [
			'' => '전체',
			'교육' => '교육',
			'자격증' => '자격증',
			'세미나' => '세미나',
			'해외연수' => '해외연수',
			'기타' => '기타',
		];
		$currentCategory = request('category', '');
	@endphp
	<ul class="round_tabs mt40 mb80">
		@foreach($categories as $value => $label)
		<li class="{{ $currentCategory === $value ? 'on' : '' }}">
			<a href="{{ route('notice.faq', $value ? ['category' => $value] : []) }}">{{ $label }}</a>
		</li>
		@endforeach
	</ul>

	<div class="faq_wrap">
		@forelse($posts as $item)
		<dl>
			<dt><button type="button"><strong>{{ $item->category ?? '기타' }}</strong><p>{{ $item->title ?? '' }}</p></button></dt>
			<dd>
				{!! $item->content ?? '' !!}
			</dd>
		</dl>
		@empty
		<p class="tac" style="padding:40px 0; color:#6D7882;">등록된 FAQ가 없습니다.</p>
		@endforelse
	</div>

	@include('notice.partials.pagination')

</main>
<script src="{{ asset('js/notice-faq.js') }}"></script>
@endsection
