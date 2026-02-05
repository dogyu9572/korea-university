{{-- 세미나·해외연수 신청 리스트 페이지네이션 --}}
@php
    $paginator = $programs->withQueryString();
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $blockSize = 10;
    $startOfBlock = (int) floor(($currentPage - 1) / $blockSize) * $blockSize + 1;
    $endOfBlock = min($lastPage, $startOfBlock + $blockSize - 1);
    $prevBlockPage = max(1, $startOfBlock - $blockSize);
    $nextBlockPage = $startOfBlock + $blockSize;
@endphp
<div class="board_bottom">
	<div class="paging">
		@if($currentPage > 1)
		<a href="{{ $paginator->url(1) }}" class="arrow two first" title="맨 처음">맨끝</a>
		@else
		<span class="arrow two first">맨끝</span>
		@endif
		@if($startOfBlock > 1)
		<a href="{{ $paginator->url($prevBlockPage) }}" class="arrow one prev" title="이전 블록">이전</a>
		@else
		<span class="arrow one prev">이전</span>
		@endif
		@foreach($paginator->getUrlRange($startOfBlock, $endOfBlock) as $page => $url)
			@if($page == $currentPage)
			<a href="{{ $url }}" class="on">{{ $page }}</a>
			@else
			<a href="{{ $url }}">{{ $page }}</a>
			@endif
		@endforeach
		@if($nextBlockPage <= $lastPage)
		<a href="{{ $paginator->url($nextBlockPage) }}" class="arrow one next" title="다음 블록">다음</a>
		@else
		<span class="arrow one next">다음</span>
		@endif
		@if($currentPage < $lastPage)
		<a href="{{ $paginator->url($lastPage) }}" class="arrow two last" title="맨 끝">맨끝</a>
		@else
		<span class="arrow two last">맨끝</span>
		@endif
	</div>
</div>
