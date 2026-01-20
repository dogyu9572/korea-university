@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="mo_select_box">
		<button type="button" class="btn_select mo_vw">전체(20개)</button>
		<ul class="round_tabs mb80">
			<li class="on"><a href="#this">전체</a></li>
			<li><a href="#this">교육</a></li>
			<li><a href="#this">자격증</a></li>
			<li><a href="#this">세미나</a></li>
			<li><a href="#this">해외연수</a></li>
			<li><a href="#this">기타</a></li>
		</ul>
	</div>

	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>20</strong></p>
		</div>
		<div class="right right_btns">
			<a href="/mypage/my_inquiries_write" class="btn btn_write btn_wbb flex_center">글쓰기</a>
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
				<tr>
					<td class="num">10</td>
					<td class="type">교육</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_ing">미답변</i></td>
				</tr>
				<tr>
					<td class="num">9</td>
					<td class="type">기타</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">8</td>
					<td class="type">자격증</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">7</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">6</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">5</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">4</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">3</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">2</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
				<tr>
					<td class="num">1</td>
					<td class="type">해외연수</td>
					<td class="tit tal"><a href="/mypage/my_inquiries_view">문의제목입니다. 문의제목입니다. 문의제목입니다. 문의제목입니다.</a></td>
					<td class="date">2025.11.11</td>
					<td class="state"><i class="answer_end">답변완료</i></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="board_bottom">
		<div class="paging">
			<a href="#this" class="arrow two first">맨끝</a>
			<a href="#this" class="arrow one prev">이전</a>
			<a href="#this" class="on">1</a>
			<a href="#this">2</a>
			<a href="#this">3</a>
			<a href="#this">4</a>
			<a href="#this">5</a>
			<a href="#this" class="arrow one next">다음</a>
			<a href="#this" class="arrow two last">맨끝</a>
		</div>
	</div> <!-- //board_bottom -->

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