@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="board_view">
		<div class="tit gbox">
			<div class="types"><span class="type {{ $inquiry->status === '답변완료' ? 'end' : 'ing' }}">{{ $inquiry->status === '답변완료' ? '답변완료' : '미답변' }}</span></div>
			<strong>{{ $inquiry->title }}</strong>
			<div class="flex">
				<dl class="typebox"><dt>분류</dt><dd>{{ $inquiry->category }}</dd></dl>
				<dl class="date"><dt>등록일</dt><dd>{{ $inquiry->created_at->format('Y.m.d') }}</dd></dl>
			</div>
		</div>
		
		<div class="con">
			{!! nl2br(e($inquiry->content)) !!}
		</div>
		
		@if($inquiry->files && $inquiry->files->count() > 0)
		<div class="download_area type_gbox">
			@foreach($inquiry->files as $file)
			<a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->file_name }}">{{ $file->file_name }}</a>
			@endforeach
		</div>
		@endif

		@if($inquiry->reply)
		<div class="reply_wrap">
			<div class="box">
				<div class="tit flex">
					<dl class="writer"><dt>작성자

					<!-- </dt><dd>{{ $inquiry->category }} -->

					</dd><dd>{{ $inquiry->reply->author ?? '관리자' }}</dd></dl>
					<dl class="date"><dt>등록일</dt><dd>{{ $inquiry->reply->reply_date ? $inquiry->reply->reply_date->format('Y.m.d') : $inquiry->reply->created_at->format('Y.m.d') }}</dd></dl>
				</div>
				<div class="con">{!! $inquiry->reply->content !!}</div>
				@if($inquiry->reply->files && $inquiry->reply->files->count() > 0)
				<div class="download_area type_gbox">
					@foreach($inquiry->reply->files as $file)
					<a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->file_name }}">{{ $file->file_name }}</a>
					@endforeach
				</div>
				@endif
			</div>
		</div>
		@endif
		
		<div class="board_btm btns_tac mt80">
			@if($prevNext['prev'])
			<a href="{{ route('mypage.my_inquiries_view', $prevNext['prev']->id) }}" class="arrow prev"><strong>이전 글</strong><p>{{ $prevNext['prev']->title }}</p></a>
			@else
			<span class="arrow prev"><strong>이전 글</strong><p>이전 글이 없습니다.</p></span>
			@endif
			@if($prevNext['next'])
			<a href="{{ route('mypage.my_inquiries_view', $prevNext['next']->id) }}" class="arrow next"><strong>다음 글</strong><p>{{ $prevNext['next']->title }}</p></a>
			@else
			<span class="arrow next"><strong>다음 글</strong><p>다음 글이 없습니다.</p></span>
			@endif
			<a href="{{ route('mypage.my_inquiries') }}" class="btn btn_list">목록</a>
		</div>
	</div>
	
</main>
@endsection