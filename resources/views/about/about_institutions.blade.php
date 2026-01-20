@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="search_wrap">
		<div class="flex bdb">
			<dl>
				<dt>회원 학교명</dt>
				<dd><input type="text" class="w100p" placeholder="회원 학교명을 입력해주세요."></dd>
			</dl>
			<dl>
				<dt>지회</dt>
				<dd class="dates">
					<select name="" id="" class="w100p">
						<option value="">전체</option>
					</select>
				</dd>
			</dl>
		</div>
		<div class="btns_tac mt0">
			<button type="button" class="btn btn_search btn_wbb btn_w160">검색</button>
			<button type="button" class="btn btn_reset btn_bwb btn_w160">초기화</button>
		</div>
	</div>
	
	<div class="tbl board_list tbl_tac">
		<table>
			<colgroup>
				<col class="slide3">
				<col class="slide3">
				<col class="slide3">
			</colgroup>
			<thead>
				<tr>
					<th>지회</th>
					<th>회원학교</th>
					<th>공식 웹사이트</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
				</tr>
				<tr>
					<td class="branch">경기인천강원지회</td>
					<td class="school">가천대학교</td>
					<td class="link"><a href="#this" target="_blank" class="btn_outlink">링크</a></td>
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
$('.btn_reset').on('click', function () {
	const $wrap = $(this).closest('.search_wrap');
	$wrap.find('input[type="text"]').val('');
	$wrap.find('.con select').each(function () {
		this.selectedIndex = 0;
	});
	$wrap.find('.con input[type="checkbox"], .con input[type="radio"]').prop('checked', false);
});
</script>

@endsection