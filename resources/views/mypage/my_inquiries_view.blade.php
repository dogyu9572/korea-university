@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="board_view">
		<div class="tit gbox">
			<div class="types"><span class="type end">답변완료</span></div>
			<strong>문의 제목이 들어갈 공간입니다. 문의 제목이 들어갈 공간입니다. </strong>
			<div class="flex">
				<dl class="typebox"><dt>분류</dt><dd>연구비 사용</dd></dl>
				<dl class="date"><dt>등록일</dt><dd>2025.03.07</dd></dl>
			</div>
		</div>
		
		<div class="con">
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.<br/>
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.<br/>
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.<br/>
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.<br/>
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.<br/>
			내용입니다. 내용입니다. 내용입니다. 내용입니다. 내용입니다.
		</div>
		
		<div class="download_area type_gbox">
			<a href="#this">첨부파일 파일명입니다.</a>
			<a href="#this">첨부파일 파일명입니다.</a>
			<a href="#this">첨부파일 파일명입니다.</a>
		</div>

		<div class="reply_wrap">
			<div class="box">
				<div class="tit flex">
					<dl class="writer"><dt>분류</dt><dd>관리자</dd></dl>
					<dl class="date"><dt>등록일</dt><dd>2025.03.07</dd></dl>
				</div>
				<div class="con">
					안녕하세요. 관리자입니다.<br/>
					문의주신 내용 답변 드립니다.<br/>
					답변 내용입니다. 답변 내용입니다. 답변 내용입니다. 답변 내용입니다. 답변 내용입니다. 답변 내용입니다. 답변 내용입니다.<br/>
					감사합니다.
				</div>
			</div>
		</div>
		
		<div class="board_btm btns_tac mt80">
			<a href="#this" class="arrow prev"><strong>이전 글</strong><p>이전 글 제목입니다.</p></a>
			<a href="#this" class="arrow next"><strong>다음 글</strong><p>다음 글 제목입니다.</p></a>
			<a href="/mypage/my_inquiries" class="btn btn_list">목록</a>
		</div>
	</div>
	
</main>
@endsection