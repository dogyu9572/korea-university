<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="robots" content="follow">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="subject" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta name="title" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta name="description" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta name="keywords" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta name="copyright" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta property="og:title" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta property="og:subject" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta property="og:description" content="사단법인 전국대학연구·산학협력관리자협회" />
	<meta property="og:image" content="/images/og_image.jpg" />
	<meta name="author" content="http://">
	<link rel="canonical" href="http://" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>@yield('title', '사단법인 전국대학연구·산학협력관리자협회')</title>

    <!-- Styles -->
	<link rel="stylesheet" href="/css/font.css" media="all">
    <link rel="stylesheet" href="{{ asset('css/styles.css?v2') }}">
    <link rel="stylesheet" href="{{ asset('css/reactive.css?v2') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
	<link rel="icon" href="/images/favicon.png" type="image/x-icon"/>
    @yield('styles')
    
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/js/com.js"></script>
    <script src="{{ asset('js/phone-input.js') }}"></script>
</head>
<body class="ios_safe">
	@if(isset($gNum) && $gNum !== '99')
    <header class="header @if(isset($gNum) && $gNum == 'main') main @endif">
		<div class="inner">
			<a href="/" class="logo"><img src="/images/logo.svg" alt="logo"><h1>사단법인 전국대학연구·산학협력관리자협회</h1></a>
			<div class="gnb">
				<div class="menu {{ $gNum == '01' ? 'on' : '' }}"><a href="/education_certification/education">교육 · 자격증</a>
					<div class="snb">
						<a href="/education_certification/education" class="{{ ($gNum == '01' && $sNum == '01') ? 'on' : '' }}">교육 안내</a>
						<a href="/education_certification/certification" class="{{ ($gNum == '01' && $sNum == '02') ? 'on' : '' }}">자격증 안내</a>
						<a href="/education_certification/application_ec" class="{{ ($gNum == '01' && $sNum == '03') ? 'on' : '' }}">교육ㆍ자격증 신청</a>
					</div>
				</div>
				<div class="menu {{ $gNum == '02' ? 'on' : '' }}"><a href="/seminars_training/seminar">세미나 · 해외연수</a>
					<div class="snb">
						<a href="/seminars_training/seminar" class="{{ ($gNum == '02' && $sNum == '01') ? 'on' : '' }}">세미나 안내</a>
						<a href="/seminars_training/overseas_training" class="{{ ($gNum == '02' && $sNum == '02') ? 'on' : '' }}">해외연수 안내</a>
						<a href="/seminars_training/application_st" class="{{ ($gNum == '02' && $sNum == '03') ? 'on' : '' }}">세미나ㆍ해외연수 신청</a>
					</div>
				</div>
				<div class="menu {{ $gNum == '03' ? 'on' : '' }}"><a href="/notice/notice">알림마당</a>
					<div class="snb">
						<a href="/notice/notice" class="{{ ($gNum == '03' && $sNum == '01') ? 'on' : '' }}">공지사항</a>
						<a href="/notice/faq" class="{{ ($gNum == '03' && $sNum == '02') ? 'on' : '' }}">FAQ</a>
						<a href="/notice/data_room" class="{{ ($gNum == '03' && $sNum == '03') ? 'on' : '' }}">자료실</a>
						<a href="/notice/past_events" class="{{ ($gNum == '03' && $sNum == '04') ? 'on' : '' }}">지난 행사</a>
						<a href="/notice/recruitment" class="{{ ($gNum == '03' && $sNum == '05') ? 'on' : '' }}">회원교 채용정보</a>
					</div>
				</div>
				<div class="menu {{ $gNum == '04' ? 'on' : '' }}"><a href="/about/establishment">협회 소개</a>
					<div class="snb">
						<a href="/about/establishment" class="{{ ($gNum == '04' && $sNum == '01') ? 'on' : '' }}">설립목적 및 역할</a>
						<a href="/about/projects" class="{{ ($gNum == '04' && $sNum == '02') ? 'on' : '' }}">주요사업</a>
						<a href="/about/history" class="{{ ($gNum == '04' && $sNum == '03') ? 'on' : '' }}">연혁 및 정관</a>
						<a href="/about/organizational" class="{{ ($gNum == '04' && $sNum == '04') ? 'on' : '' }}">조직도</a>
						<a href="/about/about_institutions" class="{{ ($gNum == '04' && $sNum == '05') ? 'on' : '' }}">회원기관 소개</a>
					</div>
				</div>
				<div class="menu {{ $gNum == '05' ? 'on' : '' }}"><a href="/mypage/application_status">마이페이지</a>
					<div class="snb">
						<a href="/mypage/application_status" class="{{ ($gNum == '05' && $sNum == '01') ? 'on' : '' }}">교육 신청 현황</a>
						<a href="/mypage/my_qualification" class="{{ ($gNum == '05' && $sNum == '02') ? 'on' : '' }}">나의 자격 현황</a>
						<a href="/mypage/my_inquiries" class="{{ ($gNum == '05' && $sNum == '03') ? 'on' : '' }}">나의 문의</a>
						<a href="/mypage/edit_member_information" class="{{ ($gNum == '05' && $sNum == '04') ? 'on' : '' }}">회원정보 수정</a>
					</div>
				</div>
			</div>
			<div class="member flex">				
				@auth('member')
					<form method="POST" action="{{ route('member.logout') }}" class="d-inline">
						@csrf
						<button type="submit" class="i2 btn_link">LOGOUT</button>
					</form>
				@else
					<a href="{{ route('member.join') }}" class="i1">SIGN UP</a>
					<a href="{{ route('member.login') }}" class="i2">LOGIN</a>
				@endauth
			</div>
			<a href="javascript:void(0);" class="btn_menu">
				<p class="t"></p>
				<p class="m"></p>
				<p class="b"></p>
			</a>
		</div>
		<div class="sitemap">
			<div class="flex_center">
				<div class="flex menus inner">
					<div class="menu {{ $gNum == '01' ? 'on' : '' }}">
						<a href="/education_certification/education" class="pc_vw">교육 · 자격증</a>
						<button type="button" class="mo_vw">교육 · 자격증<i></i></button>
						<div class="snb">
							<a href="/education_certification/education" class="{{ ($gNum == '01' && $sNum == '01') ? 'on' : '' }}"><span>교육 안내</span></a>
							<a href="/education_certification/certification" class="{{ ($gNum == '01' && $sNum == '02') ? 'on' : '' }}"><span>자격증 안내</span></a>
							<a href="/education_certification/application_ec" class="{{ ($gNum == '01' && $sNum == '03') ? 'on' : '' }}"><span>교육ㆍ자격증 신청</span></a>
						</div>
					</div>
					<div class="menu {{ $gNum == '02' ? 'on' : '' }}">
						<a href="/seminars_training/seminar" class="pc_vw">세미나 · 해외연수</a>
						<button type="button" class="mo_vw">세미나 · 해외연수<i></i></button>
						<div class="snb">
							<a href="/seminars_training/seminar" class="{{ ($gNum == '02' && $sNum == '01') ? 'on' : '' }}"><span>세미나 안내</span></a>
							<a href="/seminars_training/overseas_training" class="{{ ($gNum == '02' && $sNum == '02') ? 'on' : '' }}"><span>해외연수 안내</span></a>
							<a href="/seminars_training/application_st" class="{{ ($gNum == '02' && $sNum == '03') ? 'on' : '' }}"><span>세미나ㆍ해외연수 신청</span></a>
						</div>
					</div>
					<div class="menu {{ $gNum == '03' ? 'on' : '' }}">
						<a href="/notice/notice" class="pc_vw">알림마당</a>
						<button type="button" class="mo_vw">알림마당<i></i></button>
						<div class="snb">
							<a href="/notice/notice" class="{{ ($gNum == '03' && $sNum == '01') ? 'on' : '' }}"><span>공지사항</span></a>
							<a href="/notice/faq" class="{{ ($gNum == '03' && $sNum == '02') ? 'on' : '' }}"><span>FAQ</span></a>
							<a href="/notice/data_room" class="{{ ($gNum == '03' && $sNum == '03') ? 'on' : '' }}"><span>자료실</span></a>
							<a href="/notice/past_events" class="{{ ($gNum == '03' && $sNum == '04') ? 'on' : '' }}"><span>지난 행사</span></a>
							<a href="/notice/recruitment" class="{{ ($gNum == '03' && $sNum == '05') ? 'on' : '' }}"><span>회원교 채용정보</span></a>
						</div>
					</div>
					<div class="menu {{ $gNum == '04' ? 'on' : '' }}">
						<a href="/about/establishment" class="pc_vw">협회 소개</a>
						<button type="button" class="mo_vw">협회 소개<i></i></button>
						<div class="snb">
							<a href="/about/establishment" class="{{ ($gNum == '04' && $sNum == '01') ? 'on' : '' }}"><span>설립목적 및 역할</span></a>
							<a href="/about/projects" class="{{ ($gNum == '04' && $sNum == '02') ? 'on' : '' }}"><span>주요사업</span></a>
							<a href="/about/history" class="{{ ($gNum == '04' && $sNum == '03') ? 'on' : '' }}"><span>연혁 및 정관</span></a>
							<a href="/about/organizational" class="{{ ($gNum == '04' && $sNum == '04') ? 'on' : '' }}"><span>조직도</span></a>
							<a href="/about/about_institutions" class="{{ ($gNum == '04' && $sNum == '05') ? 'on' : '' }}"><span>회원기관 소개</span></a>
						</div>
					</div>
					<div class="menu {{ $gNum == '05' ? 'on' : '' }}">
						<a href="/mypage/application_status" class="pc_vw">마이페이지</a>
						<button type="button" class="mo_vw">마이페이지<i></i></button>
						<div class="snb">
							<a href="/mypage/application_status" class="{{ ($gNum == '05' && $sNum == '01') ? 'on' : '' }}"><span>교육 신청 현황</span></a>
							<a href="/mypage/my_qualification" class="{{ ($gNum == '05' && $sNum == '02') ? 'on' : '' }}"><span>나의 자격 현황</span></a>
							<a href="/mypage/my_inquiries" class="{{ ($gNum == '05' && $sNum == '03') ? 'on' : '' }}"><span>나의 문의</span></a>
							<a href="/mypage/edit_member_information" class="{{ ($gNum == '05' && $sNum == '04') ? 'on' : '' }}"><span>회원정보 수정</span></a>
						</div>
					</div>
					<div class="btns_abso">
						<a href="/terms/privacy_policy">개인정보처리방침</a>
						<a href="/terms/email_collection">이메일무단수집거부</a>
					</div>
				</div>
			</div>
		</div>
    </header>
	@endif
    
    <div class="container {{ $gNum == 99 ? 'print_wrap' : '' }}">
	
		@if(isset($gNum) && $gNum !== 'main' && $gNum !== '00' && $gNum !== '99')
		<!-- 서브만 적용 -->
			<div class="svisual g{{$gNum}}">
				<strong>{{$gName}}</strong>
				<div class="location"><a href="/" class="home"></a><span>{{$gName}}</span><span>{{$sName}}</span></div>
			</div>
			<div class="aside">
				<div class="inner">
					<dl>
						<dt><button type="button">{{$sName}}</button></dt>
						<dd>
						@if(isset($gNum) && $gNum == '01')
							<a href="/education_certification/education" class="{{ ($gNum == '01' && $sNum == '01') ? 'on' : '' }}">교육 안내</a>
							<a href="/education_certification/certification" class="{{ ($gNum == '01' && $sNum == '02') ? 'on' : '' }}">자격증 안내</a>
							<a href="/education_certification/application_ec" class="{{ ($gNum == '01' && $sNum == '03') ? 'on' : '' }}">교육ㆍ자격증 신청</a>
						@endif
						@if(isset($gNum) && $gNum == '02')
							<a href="/seminars_training/seminar" class="{{ ($gNum == '02' && $sNum == '01') ? 'on' : '' }}">세미나 안내</a>
							<a href="/seminars_training/overseas_training" class="{{ ($gNum == '02' && $sNum == '02') ? 'on' : '' }}">해외연수 안내</a>
							<a href="/seminars_training/application_st" class="{{ ($gNum == '02' && $sNum == '03') ? 'on' : '' }}">세미나ㆍ해외연수 신청</a>
						@endif
						@if(isset($gNum) && $gNum == '03')
							<a href="/notice/notice" class="{{ ($gNum == '03' && $sNum == '01') ? 'on' : '' }}">공지사항</a>
							<a href="/notice/faq" class="{{ ($gNum == '03' && $sNum == '02') ? 'on' : '' }}">FAQ</a>
							<a href="/notice/data_room" class="{{ ($gNum == '03' && $sNum == '03') ? 'on' : '' }}">자료실</a>
							<a href="/notice/past_events" class="{{ ($gNum == '03' && $sNum == '04') ? 'on' : '' }}">지난 행사</a>
							<a href="/notice/recruitment" class="{{ ($gNum == '03' && $sNum == '05') ? 'on' : '' }}">회원교 채용정보</a>
						@endif
						@if(isset($gNum) && $gNum == '04')
							<a href="/about/establishment" class="{{ ($gNum == '04' && $sNum == '01') ? 'on' : '' }}">설립목적 및 역할</a>
							<a href="/about/projects" class="{{ ($gNum == '04' && $sNum == '02') ? 'on' : '' }}">주요사업</a>
							<a href="/about/history" class="{{ ($gNum == '04' && $sNum == '03') ? 'on' : '' }}">연혁 및 정관</a>
							<a href="/about/organizational" class="{{ ($gNum == '04' && $sNum == '04') ? 'on' : '' }}">조직도</a>
							<a href="/about/about_institutions" class="{{ ($gNum == '04' && $sNum == '05') ? 'on' : '' }}">회원기관 소개</a>
						@endif
						@if(isset($gNum) && $gNum == '05')
							<a href="/mypage/application_status" class="{{ ($gNum == '05' && $sNum == '01') ? 'on' : '' }}">교육 신청 현황</a>
							<a href="/mypage/my_qualification" class="{{ ($gNum == '05' && $sNum == '02') ? 'on' : '' }}">나의 자격 현황</a>
							<a href="/mypage/my_inquiries" class="{{ ($gNum == '05' && $sNum == '03') ? 'on' : '' }}">나의 문의</a>
							<a href="/mypage/edit_member_information" class="{{ ($gNum == '05' && $sNum == '04') ? 'on' : '' }}">회원정보 수정</a>
						@endif
						</dd>
					</dl>
				</div>
			</div>
		<!-- //서브만 적용 -->
		@endif
		
        @yield('content')
    </div>
	@if(isset($gNum) && $gNum !== '99')
    <!-- 팝업 영역 -->
    @yield('popups')
    
    <!-- 스크립트 -->
    <script src="{{ asset('js/popup.js') }}"></script>
    @stack('scripts')

	
	<footer class="footer">
		<div class="point"></div>
		<button type="button" class="gotop">TOP</button>
		<div class="links">
			<div class="inner">
				<a href="/terms/privacy_policy">개인정보처리방침</a>
				<a href="/terms/email_collection">이메일무단수집거부</a>
				<!-- <dl class="family_site">
					<dt><button type="button">FAMILY SITE</button></dt>
					<dd>
						<a href="#this">FAMILY SITE</a>
					</dd>
				</dl> -->
			</div>
		</div>
		<div class="info">
			<div class="inner">
				<ul>
					<!-- <li><strong>회장</strong>박준철</li> -->
					<li><strong>주소</strong>서울특별시 관악구 관악로 1 서울대학교 연구공원내 943동 5층(08826)</li>
					<!-- <li><strong>Tel</strong>(02)880-2040</li> -->
					<!-- <li><strong>E-mail</strong>pjc0302@snu.ac.kr</li> -->
				</ul>
				<!-- <ul>
					<li><strong>사무국장</strong>윤대성</li>
					<li><strong>주소</strong>전라북도 전주시 완산구 천잠로 303, 대학본관 218호(55069)</li>
					<li><strong>Tel</strong>(063)220-3194</li>
					<li><strong>E-mail</strong>koreaweb@jj.ac.kr</li>
				</ul> -->
				<p class="copy">Copyright © www.kucra.or.kr All rights reserved.</p>
			</div>
		</div>
	</footer>
	@endif
</body>
</html>
