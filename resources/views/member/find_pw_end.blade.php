@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">비밀번호 변경 완료</div>
			<div class="member_area end_area">
		
				<div class="tit mb8">비밀번호 변경이 완료되었습니다.</div>
				<p>새로운 비밀번호로 로그인을 해주세요.</p>
				<div class="btns_tac colm mt40">
					<a href="{{ route('member.login') }}" class="btn btn_wbb">로그인</a>
				</div>
			</div>
		</div>
	</div>
	
</main>
@endsection