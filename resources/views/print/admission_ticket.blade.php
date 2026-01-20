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
		
		<div class="print_title">수험표</div>
		
		<div class="admission_ticket_wrap">
			<div class="print_tbl">
				<table>
					<tbody>
						<tr>
							<th>자격증명</th>
							<td>대학연구행정전문가 1급</td>
						</tr>
						<tr>
							<th>시험 회차</th>
							<td>2026년 제1회</td>
						</tr>
						<tr>
							<th>시험일자</th>
							<td>2026.05.12(화) 10:00~12:00</td>
						</tr>
						<tr>
							<th>시험장</th>
							<td>서울대학교 사범대 103호 (좌석번호 : A-12)</td>
						</tr>
						<tr>
							<th>수험번호</th>
							<td>2026-1-00123</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td>test1234@naver.com</td>
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
					</tbody>
				</table>
			</div>
			<div class="img"><img src="/images/img_profile_sample.jpg" alt=""></div>
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