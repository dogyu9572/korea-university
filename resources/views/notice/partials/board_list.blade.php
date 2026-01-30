{{-- 알림마당 테이블형 게시판 목록 공통 (공지사항, 자료실, 지난 행사, 채용정보) --}}
{{-- 필요 변수: $listRoute, $detailRoute, $posts (컨트롤러에서 전달) --}}
<div class="board_search">
	<form method="GET" action="{{ route('notice.' . $listRoute) }}" class="search-form">
		<select name="search_type" class="text">
			<option value="">전체</option>
			<option value="title" @selected(request('search_type') == 'title')>제목</option>
			<option value="content" @selected(request('search_type') == 'content')>내용</option>
		</select>
		<input type="text" name="keyword" class="text" placeholder="검색어를 입력해주세요." value="{{ request('keyword') }}">
		<button type="submit" class="btn btn_search btn_wbb">검색</button>
	</form>
</div>

<div class="board_top mt40">
	<div class="left">
		<p>TOTAL <strong>{{ $posts->total() }}</strong></p>
	</div>
</div>

<div class="tbl board_list tbl_tac">
	<table>
		<colgroup>
			<col class="w120">
			<col>
			<col class="w120">
			<col class="w160">
		</colgroup>
		<thead>
			<tr>
				<th>NO.</th>
				<th>제목</th>
				<th>첨부파일</th>
				<th>등록일</th>
			</tr>
		</thead>
		<tbody>
			@forelse($posts as $item)
			<tr class="{{ !empty($item->is_notice) ? 'notice' : '' }}">
				<td class="num">{{ $item->id }}</td>
				<td class="tit tal">
					<a href="{{ route('notice.' . $detailRoute, $item->id) }}">{{ $item->title ?? '' }}</a>
				</td>
				<td class="down">
					@php
						$attachments = isset($item->attachments) ? json_decode($item->attachments, true) : [];
						$firstFile = is_array($attachments) && !empty($attachments) && !empty($attachments[0]['path']) ? $attachments[0] : null;
					@endphp
					@if($firstFile)
					<a href="{{ \Illuminate\Support\Facades\Storage::url($firstFile['path']) }}" download title="{{ $firstFile['name'] ?? '첨부파일 다운로드' }}"><i></i></a>
					@else
					<span></span>
					@endif
				</td>
				<td class="date">{{ isset($item->created_at) ? $item->created_at->format('Y.m.d') : '' }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="4" class="tac">등록된 게시글이 없습니다.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</div>

@include('notice.partials.pagination')
