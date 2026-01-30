@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="stitle nodot">회원가입 완료</div>
		 
		<div class="end_area">
			<div class="tit">{{ session('success', '회원가입이 완료되었습니다.') }}</div>
			<p>로그인 후 서비스를 이용해 주시기 바랍니다.</p>
			<div class="btns_tac colm">
				<a href="{{ route('member.login') }}" class="btn btn_wbb">로그인</a>
			</div>
		</div>
	</div>
	
</main>
@endsection