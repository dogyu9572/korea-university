{{-- 알림마당 게시판 상세 공통 (공지사항, 자료실, 지난 행사, 채용정보) --}}
{{-- 필요 변수: $listRoute, $detailRoute, $post, $prevPost, $nextPost (컨트롤러에서 전달) --}}
<div class="board_view">
	<div class="tit gbox">
		<div class="types">
			@if(!empty($post->is_notice))
			<span class="type type_notice">공지</span>
			@endif
		</div>
		<strong>{{ $post->title ?? '' }}</strong>
		<dl class="date">
			<dt>등록일</dt>
			<dd>{{ isset($post->created_at) ? $post->created_at->format('Y.m.d') : '' }}</dd>
		</dl>
	</div>

	<div class="con">
		{!! $post->content ?? '' !!}
	</div>

	@php
		$attachments = isset($post->attachments) ? json_decode($post->attachments, true) : [];
	@endphp
	@if(!empty($attachments) && is_array($attachments))
	<div class="download_area type_gbox">
		@foreach($attachments as $file)
		@if(is_array($file) && !empty($file['path']))
		<a href="{{ \Illuminate\Support\Facades\Storage::url($file['path']) }}" download>{{ $file['name'] ?? '첨부파일' }}</a>
		@endif
		@endforeach
	</div>
	@endif

	<div class="board_btm btns_tac mt80">
		@if(!empty($prevPost))
		<a href="{{ route('notice.' . $detailRoute, $prevPost->id) }}" class="arrow prev"><strong>이전 글</strong><p>{{ $prevPost->title ?? '' }}</p></a>
		@else
		<span class="arrow prev"><strong>이전 글</strong><p>이전 글이 없습니다.</p></span>
		@endif
		@if(!empty($nextPost))
		<a href="{{ route('notice.' . $detailRoute, $nextPost->id) }}" class="arrow next"><strong>다음 글</strong><p>{{ $nextPost->title ?? '' }}</p></a>
		@else
		<span class="arrow next"><strong>다음 글</strong><p>다음 글이 없습니다.</p></span>
		@endif
		<a href="{{ route('notice.' . $listRoute) }}" class="btn btn_list">목록</a>
	</div>
</div>
