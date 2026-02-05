@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle nodot">신청 완료</div>

    <div class="end_area">
        <div class="tit">세미나·해외연수 신청이 완료되었습니다.</div>
        <p>
            신청번호: <strong>{{ $confirmation['application_number'] ?? '-' }}</strong><br>
            프로그램명: <strong>{{ $confirmation['program_name'] ?? '-' }}</strong>
        </p>
        <p>신청 정보는 <strong>마이페이지 > 나의 교육 > 교육 신청 현황</strong>에서 확인할 수 있습니다.</p>
        <div class="btns_tac colm">
            <a href="{{ route('mypage.application_status') }}" class="btn btn_wbb">신청내역 바로가기</a>
            <a href="{{ route('home') }}" class="btn btn_bwb">메인으로</a>
        </div>
    </div>
</main>
@endsection
