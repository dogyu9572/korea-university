@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	@php
		$categoryLabels = ['전체' => '전체'] + array_combine($categories, $categories);
		$currentCategory = $filters['category'] ?? '전체';
	@endphp

	<div class="mo_select_box">
		<button type="button" class="btn_select mo_vw">{{ $currentCategory }}({{ $inquiries->total() }}개)</button>
		<ul class="round_tabs mb80">
			@foreach($categoryLabels as $value => $label)
			<li class="{{ $currentCategory === $value ? 'on' : '' }}"><a href="{{ route('mypage.my_inquiries', $value === '전체' ? [] : ['category' => $value]) }}">{{ $label }}</a></li>
			@endforeach
		</ul>
	</div>

	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>{{ $inquiries->total() }}</strong></p>
		</div>
		<div class="right right_btns">
			<a href="{{ route('mypage.my_inquiries_write') }}" class="btn btn_write btn_wbb flex_center">글쓰기</a>
		</div>
	</div>

	<div class="tbl board_list tbl_tac my_inquiries_list">
		<table>
			<colgroup>
				<col class="w120">
				<col class="w160">
				<col>
				<col class="w160">
				<col class="w160">
			</colgroup>
			<thead>
				<tr>
					<th>NO.</th>
					<th>분류</th>
					<th>문의 제목</th>
					<th>등록일</th>
					<th>상태</th>
				</tr>
			</thead>
			<tbody>
				@forelse($inquiries as $index => $inquiry)
				<tr>
					<td class="num">{{ $inquiries->total() - ($inquiries->currentPage() - 1) * $inquiries->perPage() - $index }}</td>
					<td class="type">{{ $inquiry->category }}</td>
					<td class="tit tal"><a href="{{ route('mypage.my_inquiries_view', $inquiry->id) }}">{{ $inquiry->title }}</a></td>
					<td class="date">{{ $inquiry->created_at->format('Y.m.d') }}</td>
					<td class="state">@if($inquiry->status === '답변완료')<i class="answer_end">답변완료</i>@else<i class="answer_ing">미답변</i>@endif</td>
				</tr>
				@empty
				<tr>
					<td colspan="5" class="tac" style="padding:40px 0; color:#6D7882;">등록된 문의가 없습니다.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	@php $posts = $inquiries; @endphp
	@include('notice.partials.pagination')

</main>

<script>
function selectToggle() {
	if ($(window).width() <= 767) {
		$(".mo_select_box .btn_select").off("click").on("click", function (e) {
			e.stopPropagation();
			const $box = $(this).closest(".mo_select_box");
			const $list = $box.find(".round_tabs");
			$list.stop(true, true).slideToggle("fast");
			$box.toggleClass("on");
		});
		$(document).off("click.moSelect").on("click.moSelect", function (e) {
			if (!$(e.target).closest(".mo_select_box").length) {
				$(".mo_select_box").removeClass("on")
					.find(".round_tabs").slideUp("fast");
			}
		});
		
		$(".mo_select_box .round_tabs a").on("click", function () {
			const text = $(this).text();
			const $box = $(this).closest(".mo_select_box");
			$box.find(".btn_select").text(text);
			$box.removeClass("on").find(".round_tabs").slideUp("fast");
			$(this).parent().addClass("on").siblings().removeClass("on");
		});
	} else {
		$(".mo_select_box .btn_select").off("click");
		$(document).off("click.moSelect");
		$(".mo_select_box")
			.removeClass("on")
			.find(".round_tabs")
			.removeAttr("style");
	}
}
selectToggle();
$(window).on("resize", function () {
	selectToggle();
});
</script>

@endsection