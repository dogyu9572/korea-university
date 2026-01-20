@extends('layouts.app')
@section('content')
<main class="print_inbox">
	<div class="print_area">
		<div class="print_head">
			<div class="num">[KS-123456789]</div>
			<div class="btns">
				<a href="#this" class="btn btn_wkk btn_down">PDF 다운</a>
				<button type="button" class="btn btn_print btn_kwk">인쇄</button>
			</div>
		</div>
		
		<div class="certificate_wrap">
			<div class="print_title_intype nnmj"><span>Certificate of Completion</span><strong>합격확인서</strong></div>
		
			<div class="certificate_tbl nnmj">
				<table>
					<tbody>
						<tr>
							<th>과정명</th>
							<td>과정명입니다. 과정명입니다. 과정명입니다. 과정명입니다. 과정명입니다. 과정명입니다.</td>
						</tr>
						<tr>
							<th>성명</th>
							<td>홍길동</td>
						</tr>
						<tr>
							<th>생년월일</th>
							<td>2000.01.01</td>
						</tr>
						<tr>
							<th>소속기관</th>
							<td>서울대학교</td>
						</tr>
						<tr>
							<th>교육기간</th>
							<td>2025.11.13. ~ 2025.11.16</td>
						</tr>
						<tr>
							<th>교육종류</th>
							<td>정기교육</td>
						</tr>
						<tr>
							<th>이수시간</th>
							<td>15시간(900분)</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="print_btm">
				<div class="date">YYYY년 MM월 DD일</div>
				<p>위 현황은 사실과 같음을 증명합니다.</p>
				<div class="stemp nnmj"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
			</div>
		</div>
	</div>
	
</main>

<script>
$('.btn_print').on('click', function () {
	const printContents = $('.print_inbox').html();
	const originalContents = $('body').html();

	$('body').html(printContents);
	window.print();
	$('body').html(originalContents);
	location.reload(); // 스타일 깨짐 방지용
});
</script>


@endsection