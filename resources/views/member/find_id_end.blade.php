@extends('layouts.app')
@section('content')
<main class="sub_wrap member_wrap">
    
	<div class="inner">
		<div class="member_inner">
			<div class="stitle nodot">아이디 찾기 완료</div>
			<div class="member_area end_area">
		
				<div class="tit mb8">찾으시는 아이디는 <strong class="c_blue_light">{{ $found_login_id ?? '' }}</strong> 입니다.</div>
				<p>개인정보 보호를 위해 아이디의 일부가 가려져 있습니다.</p>
				<div class="btns_tac colm mt40">
					<a href="{{ route('member.login') }}" class="btn btn_wbb">확인</a>
					<a href="{{ route('member.find_pw') }}" class="btn btn_bwb">비밀번호 찾기</a>
				</div>
			</div>
			<p class="ne gray mt32">아이디를 찾을 수 없는 경우 063-220-3194로 문의해주시기 바랍니다.</p>
		</div>
	</div>
	
</main>
@endsection