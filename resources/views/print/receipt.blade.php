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
		
		<div class="print_title">영수증</div>
		
		<div class="admission_ticket_wrap">
			<div class="print_tbl">
				<table>
					<tbody>
						<tr>
							<th>입금자명</th>
							<td>홍길동</td>
						</tr>
						<tr>
							<th>입금은행</th>
							<td>국민은행</td>
						</tr>
						<tr>
							<th>계좌번호</th>
							<td>123-4567-8901-23</td>
						</tr>
						<tr>
							<th>예금주</th>
							<td>전국대학 연구·산학협력관리자협의회</td>
						</tr>
						<tr>
							<th>입금일시</th>
							<td>2025.11.10 14:32</td>
						</tr>
						<tr>
							<th>입금금액</th>
							<td>50,000원</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="print_btm">
			<div class="date">YYYY년 MM월 DD일</div>
			<p>위 현황은 사실과 같음을 증명합니다.</p>
			<div class="stemp"><span>전국대학 연구·산학협력관리자협의회(KUCra)</span></div>
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