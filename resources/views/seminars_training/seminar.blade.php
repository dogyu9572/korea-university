@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="seminar_wrap">
        {!! $seminar_guide ?? '' !!}
        <div class="btns_tac">
            <a href="/seminars_training/application_st" class="btn btn_link btn_wbb">세미나 신청하기</a>
        </div>
    </div>
</main>
@endsection
