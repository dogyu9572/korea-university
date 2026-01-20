@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="board_search">
		<select name="" id="" class="text">
			<option value="">전체</option>
			<option value="">제목</option>
			<option value="">내용</option>
		</select>
		<input type="text" class="text" placeholder="검색어를 입력해주세요.">
		<button class="btn btn_search btn_wbb">검색</button>
	</div>
	
	<div class="board_top mt40">
		<div class="left">
			<p>TOTAL <strong>20</strong></p>
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
				<tr class="notice">
					<td class="num">10</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr class="notice">
					<td class="num">9</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">8</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">7</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">6</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">5</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">4</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">3</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">2</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
				</tr>
				<tr>
					<td class="num">1</td>
					<td class="tit tal"><a href="/notice/notice_view">제61차 추계세미나 수료증 및 영수증 발급 안내(11월 11일 현재부터 발급 가능)</a></td>
					<td class="down"><i></i></td>
					<td class="date">2025.11.11</td>
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
@endsection