@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <h1 class="stitle nodot">신청 완료</h1>

    <div class="end_area">
        <h2 class="tit">
            @if(($confirmation['type'] ?? '') === 'online')
                온라인 교육 신청이 완료되었습니다.
            @else
                교육 수강 신청이 완료되었습니다.
            @endif
        </h2>
        <p>
            신청번호: <strong>{{ $confirmation['application_number'] ?? '-' }}</strong><br>
            프로그램명: <strong>{{ $confirmation['program_name'] ?? '-' }}</strong>
        </p>
        <p>신청 정보는 <strong>마이페이지 > 나의 교육 > 교육 신청 현황</strong>에서 확인할 수 있으며, <br>교육 시작 전까지 수정이 가능합니다.</p>
        <div class="btns_tac colm">
            <a href="/mypage/application_status" class="btn btn_wbb">신청내역 바로가기</a>
            <a href="/" class="btn btn_bwb">메인으로</a>
        </div>
    </div>
</main>

@endsection