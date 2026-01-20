@extends('layouts.app')

@section('content')
<main class="main_wrap">
    
	<div class="mvisual_wrap">
		<div class="inner">
			<div class="mvisual">
				<img src="/images/mvisual01.png" alt="">
				<img src="/images/mvisual01.png" alt="">
			</div>
			<div class="txt">
				<span>대학과 산업의 상생을 이끄는 협력의 중심, </span>
				<strong class="bar">전국대학연구</strong>
				<strong class="bar">산학협력관리자협회가</strong>
				<strong>함께합니다.</strong>
				<div class="navi flex">
					<div class="paging"></div>
					<button type="button" class="arrow prev">이전</button>
					<button type="button" class="arrow next">다음</button>
					<button type="button" class="papl pause on">정지</button>
					<button type="button" class="papl play">재생</button>
				</div>
			</div>
		</div>
	</div>
	<div class="mvisual_btm_bg"></div>
	<div class="main_quick">
		<div class="inner">
			<div class="flex">
				<div class="tit"><div class="tt">자주 찾는 메뉴</div><p><strong>가장 많이 이용하는 주요 서비스</strong>를 <br class="pc_vw"/>한눈에 확인할 수 있습니다.</p></div>
				<div class="con">
					<a href="/education_certification/education" class="i3">교육·자격증</a>
					<a href="/seminars_training/seminar" class="i4">세미나·해외연수</a>
					<a href="/about/about_institutions" class="i5">회원기관 소개</a>
					<a href="/notice/data_room" class="i6">자료실</a>
					<a href="/mypage/application_status" class="i1">수료증 발급</a>
					<a href="/mypage/my_qualification" class="i2">영수증 발급</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="mcon mc01_1">
		<div class="inner">
			<div class="mtit">교육<i></i>자격증 <a href="/education_certification/education" class="more">교육·자격증으로 이동</a></div>
			<p>산학협력단 직원의 역량 강화를 위한 <br class="mo_vw">다양한 교육 프로그램을 제공합니다.</p>
			<div class="mc01_slide">
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">교육</span>
						<span class="tit">2025년 산학협력단 직원 전문 강좌</span>
						<dl class="i1"><dt>신청기간</dt><dd>25.09.25~25.10.02</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>25.10.23~25.10.24</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c3">자격증</span>
						<span class="tit">2026년 대학연구행정전문가 1급 시험 접수 안내 </span>
						<dl class="i1"><dt>신청기간</dt><dd>26.11.18~26.11.29</dd></dl>
						<dl class="i2"><dt>시험일</dt><dd>26.12.07</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c4">온라인 교육</span>
						<span class="tit">2026년 산학협력 실무 역량강화 온라인 교육</span>
						<dl class="i1"><dt>신청기간</dt><dd>26.05.01~26.05.12</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>26.05.19~25.05.30</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">교육</span>
						<span class="tit">2025년 산학협력단 직원 전문 강좌</span>
						<dl class="i1"><dt>신청기간</dt><dd>25.09.25~25.10.02</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>25.10.23~25.10.24</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c3">자격증</span>
						<span class="tit">2026년 대학연구행정전문가 1급 시험 접수 안내 </span>
						<dl class="i1"><dt>신청기간</dt><dd>26.11.18~26.11.29</dd></dl>
						<dl class="i2"><dt>시험일</dt><dd>26.12.07</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c4">온라인 교육</span>
						<span class="tit">2026년 산학협력 실무 역량강화 온라인 교육</span>
						<dl class="i1"><dt>신청기간</dt><dd>26.05.01~26.05.12</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>26.05.19~25.05.30</dd></dl>
					</span>
				</a>
			</div>
		</div>
	</div>
	
	<div class="mcon mc01_2">
		<div class="inner">
			<div class="mtit">세미나<i></i>해외연수 <a href="/seminars_training/seminar" class="more">세미나·해외연수로 이동</a></div>
			<p>산학협력단 직원의 역량 강화를 위한 <br class="mo_vw">다양한 세미나 및 해외연수 프로그램을 제공합니다.</p>
			<div class="mc01_slide">
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">교육</span>
						<span class="tit">2025년 산학협력단 직원 전문 강좌</span>
						<dl class="i1"><dt>신청기간</dt><dd>25.09.25~25.10.02</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>25.10.23~25.10.24</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c2">자격증</span>
						<span class="tit">2026년 대학연구행정전문가 1급 시험 접수 안내 </span>
						<dl class="i1"><dt>신청기간</dt><dd>26.11.18~26.11.29</dd></dl>
						<dl class="i2"><dt>시험일</dt><dd>26.12.07</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">온라인 교육</span>
						<span class="tit">2026년 산학협력 실무 역량강화 온라인 교육</span>
						<dl class="i1"><dt>신청기간</dt><dd>26.05.01~26.05.12</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>26.05.19~25.05.30</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">교육</span>
						<span class="tit">2025년 산학협력단 직원 전문 강좌</span>
						<dl class="i1"><dt>신청기간</dt><dd>25.09.25~25.10.02</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>25.10.23~25.10.24</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c2">자격증</span>
						<span class="tit">2026년 대학연구행정전문가 1급 시험 접수 안내 </span>
						<dl class="i1"><dt>신청기간</dt><dd>26.11.18~26.11.29</dd></dl>
						<dl class="i2"><dt>시험일</dt><dd>26.12.07</dd></dl>
					</span>
				</a>
				<a href="#this">
					<span class="imgfit"><img src="/images/img_mc01_sample.jpg" alt=""></span>
					<span class="txt">
						<span class="type c1">온라인 교육</span>
						<span class="tit">2026년 산학협력 실무 역량강화 온라인 교육</span>
						<dl class="i1"><dt>신청기간</dt><dd>26.05.01~26.05.12</dd></dl>
						<dl class="i2"><dt>교육기간</dt><dd>26.05.19~25.05.30</dd></dl>
					</span>
				</a>
			</div>
		</div>
	</div>
	
	<div class="mcon mc02">
		<div class="inner flex">
		
			<div class="left">
				<div class="mtit">알림마당</div>
				<p>전국대학연구산학협력 관리자협회의 <br class="mo_vw">주요 소식과 다양한 자료를 안내합니다.</p>
				<div class="jq_tabonoff list">
					<ul class="jq_tab">
						<li><button type="button">공지사항</button></li>
						<li><button type="button">자료실</button></li>
					</ul>
					<div class="jq_cont">
						<div class="cont">
							<a href="/notice/notice" class="more">공지사항으로 이동</a>
							<ul>
								<li><a href="#this">공지사항 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
							</ul>
						</div><!-- //공지사항 -->
						<div class="cont">
							<a href="/notice/notice" class="more">자료실로 이동</a>
							<ul>
								<li><a href="#this">자료실 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
								<li><a href="#this">제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. 제목입니다. <p>2025-10-31</p></a></li>
							</ul>
						</div><!-- //자료실 -->
					</div>
				</div>
			</div>
			
			<div class="right">
				<div class="main_education_certification">
					<div class="tit">교육<i></i>자격증 안내</div>
					<p>교육과정 및 대학연구행정전문가 1급·2급 <br/>자격과정을 안내합니다.</p>
					<div class="flex">
						<a href="/education_certification/education">교육안내</a>
						<a href="/education_certification/certification">자격증안내</a>
					</div>
				</div>
				<div class="main_contact">
					<div class="tt">문의</div>
					<div class="number">02-880-2040 <p>*주말 및 공휴일 제외</p></div>
					<div class="time">
						<dl>
							<dt>월~금</dt>
							<dd>9:30~17:00</dd>
						</dl>
						<dl>
							<dt>점심시간</dt>
							<dd>11:30~13:00 </dd>
						</dl>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</main>

<link rel="stylesheet" href="/css/slick.css" media="all">
<script src="/js/slick.js"></script>
<script>
$(document).ready (function () {
//메인 비주얼
	$(".mvisual").slick({
		arrows: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000,
		pauseOnHover: false,
		swipeToSlide: true,
		fade: true,
		appendDots: $('.mvisual_wrap .paging'),
		prevArrow: $('.mvisual_wrap .prev'),
		nextArrow: $('.mvisual_wrap .next'),
		responsive: [
			{
				breakpoint: 767,
				settings: {
					fade: false,
				}
			},
		]
	});
	$('.mvisual_wrap .pause').click(function(){
		$('.mvisual').slick('slickPause');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});
	$('.mvisual_wrap .play').click(function(){
		$('.mvisual').slick('slickPlay');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});
//mc01_slide
	$(".mc01_slide").slick({
		arrows: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000,
		pauseOnHover: false,
		swipeToSlide: true,
		slidesToShow: 3,
		centerMode: true,
		centerPadding: 0,
		responsive: [
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					centerMode: false,
					arrows: false,
				}
			},
		]
	});
		
//mc02 - 알림마당
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

@section('popups')
@if($popups->count() > 0)
    @foreach($popups as $popup)
        @if($popup->popup_display_type === 'normal')
            {{-- 일반팝업 (새창) --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const popupUrl = '{{ route("popup.show", $popup->id) }}';
                    const popupFeatures = 'width={{ $popup->width }},height={{ $popup->height }},left={{ $popup->position_left ?? 100 }},top={{ $popup->position_top ?? 100 }},scrollbars=yes,resizable=yes,menubar=no,toolbar=no,location=no,status=no';
                    window.open(popupUrl, 'popup_{{ $popup->id }}', popupFeatures);
                });
            </script>
        @else
            {{-- 레이어팝업 (오버레이) --}}
            <div class="popup-layer popup-fixed" 
                 id="popup-{{ $popup->id }}"
                 data-popup-id="{{ $popup->id }}"
                 data-display-type="layer"
                 style="position: absolute !important; width: {{ $popup->width }}px; height: auto; top: {{ $popup->position_top }}px; left: {{ $popup->position_left }}px; z-index: 99999;">
                
                <div class="popup-body">
                    @if($popup->popup_type === 'image' && $popup->popup_image)
                        @if($popup->url)
                            <a href="{{ $popup->url }}" target="{{ $popup->url_target }}">
                                <img src="{{ asset('storage/' . $popup->popup_image) }}" alt="{{ $popup->title }}">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $popup->popup_image) }}" alt="{{ $popup->title }}">
                        @endif
                    @elseif($popup->popup_type === 'html' && $popup->popup_content)
                        {!! $popup->popup_content !!}
                    @endif
                </div>
                
                <div class="popup-footer">
                    <label class="popup-today-label" data-popup-id="{{ $popup->id }}">
                        <input type="checkbox" class="popup-today-close" data-popup-id="{{ $popup->id }}">
                        1일 동안 보지 않음
                    </label>
                    <button type="button" class="popup-footer-close-btn" data-popup-id="{{ $popup->id }}">닫기</button>
                </div>
            </div>
        @endif
    @endforeach
@endif
@endsection