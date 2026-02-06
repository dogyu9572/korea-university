@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="certification_wrap">
        {{-- 1. 자격증 안내 (상단 공통) --}}
        @if($certification_guide ?? '')
        <div class="certification_guide_block">
            {!! $certification_guide !!}
        </div>
        @endif

        {{-- 2. 대학연구행정전문가 1급 / 2급 (탭) --}}
        @if(($expert_level_1 ?? '') !== '' || ($expert_level_2 ?? '') !== '')
        <div class="jq_tabonoff">
            <ul class="jq_tab round_tabs">
                <li><button type="button">대학연구행정전문가 1급</button></li>
                <li><button type="button">대학연구행정전문가 2급</button></li>
            </ul>
            <div class="jq_cont">
                <div class="cont">
                    {!! $expert_level_1 ?? '' !!}
                </div>
                <div class="cont">
                    {!! $expert_level_2 ?? '' !!}
                </div>
            </div>
        </div>
        @endif

        <div class="btns_tac">
            <a href="{{ route('education_certification.application_ec') }}" class="btn btn_link btn_wbb">자격 시험 신청하기</a>
        </div>
    </div>
</main>

<script>
$(document).ready(function () {
    $('.jq_tabonoff>.jq_cont').children().css('display', 'none');
    $('.jq_tabonoff>.jq_cont .cont:first-child').css('display', 'block');
    $('.jq_tabonoff>.jq_tab > li:first-child').addClass('on');

    $('.jq_tabonoff').delegate('.jq_tab>li', 'click', function() {
        var index = $(this).parent().children().index(this);
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $(this).parent().next('.jq_cont').children().hide().eq(index).show();
    });
});
</script>
@endsection
