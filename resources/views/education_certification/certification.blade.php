@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle">대학연구행정전문가 자격증 안내</div>
	
	<div class="certification.blade_wrap">
		<p class="tac big">「대학연구행정전문가 자격」은 대학 및 연구기관의 연구관리·행정 분야 종사자의 전문성과 직무 역량을 체계적으로 인증하기 위해 마련된 제도입니다.<br/>
			연구비 관리, 회계·계약, 연구윤리 등 연구행정 전반에 대한 이해와 실무 능력을 평가하며, <br class="pc_vw">
			급속히 변화하는 연구환경 속에서 대학 연구행정의 질적 수준을 향상시키는 것을 목표로 합니다.
		</p>
		<div class="imgfit radius40_4 mt40 img_top img_certification"></div>
		
		<div class="btit mb40">대학연구행정전문가 (1급 / 2급)</div>
		<ul class="expert_info">
			<li class="i1"><span>자격제정 목적</span><p>대학 연구행정 실무자의 직무 전문성 강화 및 표준화된 역량 인증 제도</p></li>
			<li class="i2"><span>주관기관</span><p>전국대학 산학협력단 관리자협의회</p></li>
			<li class="i3"><span>자격유형</span><p>민간자격 (등록 예정)</p></li>
			<li class="i4"><span>도입시기</span><p>2026년 예정</p></li>
			<li class="i5"><span>등급구분</span><p>실무자용 2급 / <br class="pc_vw"/>관리자용 1급</p></li>
		</ul>
		
		<div class="jq_tabonoff">
			<ul class="jq_tab round_tabs">
				<li><button type="button">대학연구행정전문가 1급</button></li>
				<li><button type="button">대한연구행정전문가 2급</button></li>
			</ul>
			<div class="jq_cont">
				<div class="cont">
					<div class="btit">대학연구행정전문가 1급 시험정보</div>
					<p>대학 및 연구기관의 연구행정·관리 역량을 종합적으로 평가하는 시험으로, 단순한 지식 암기보다 실무 응용력·사례 분석 능력·정책 이해도를 중점적으로 평가합니다.</p>
					<div class="otit">출제경향</div>
					<ul class="exam_trends">
						<li class="i1"><strong>출제영역</strong>연구관리 제도 및 법규, 연구비 집행·성과관리, 기관운영 및 리스크 관리, 윤리·컴플라이언스 등</li>
						<li class="i2"><strong>출제형태</strong>서술형 문항 및 사례 기반 문제 중심</li>
						<li class="i3"><strong>평가방식</strong>실제 연구행정 사례 분석 및 문제 해결 중심 평가</li>
						<li class="i4"><strong>난이도</strong>중·상급 (관리자급 역량 검증 수준)</li>
						<li class="i5"><strong>평가목표</strong>연구행정 실무에서의 의사결정력, 제도 이해도, 책임감 있는 관리 능력 평가</li>
					</ul>
					<div class="otit">취득방법</div>
					<div class="tbl th_bg mo_break mo_break_tbl_row">
						<table>
							<colgroup>
								<col class="w200">
								<col>
								<col class="w200">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th>응시자격</th>
									<td>2급 자격 소지자 또는 연구행정 경력 2년 이상</td>
									<th>교육이수</th>
									<td>사전 지정된 심화과정(법규·성과관리·기관운영 등) 이수 필수</td>
								</tr>
								<tr>
									<th>응시절차</th>
									<td>집합교육이수 → 시험 접수 → 필기시험(서술형/사례형) <br class="pc_vw">→ 합격자 발표</td>
									<th>합격기준</th>
									<td>100점 만점 기준 60점 이상</td>
								</tr>
								<tr>
									<th>자격유효기간</th>
									<td>5년 (갱신제 운영 예정)</td>
									<th>시행시기</th>
									<td>2026년 하반기 예정</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btns_tac">
						<a href="/education_certification/application_ec" class="btn btn_link btn_wbb">자격 시험 신청하기</a>
					</div>
				</div><!-- //대학연구행정전문가 1급 -->
				<div class="cont">
					<div class="btit">대학연구행정전문가 2급 시험정보</div>
					<p>연구행정 실무자의 기초 직무역량을 공식적으로 인증하기 위한 자격입니다. 지정 교육과정 이수 후 필기시험을 통과하면 자격을 부여받을 수 있습니다.</p>
					<div class="otit">출제경향</div>
					<ul class="exam_trends">
						<li class="i1"><strong>출제영역</strong>연구비 관리, 회계·세무, 계약, 연구윤리 및 개인정보보호 등</li>
						<li class="i2"><strong>출제형태</strong>객관식·단답형 위주</li>
						<li class="i3"><strong>평가방식</strong>기본 개념 이해 및 실무 절차 숙련도 평가</li>
						<li class="i4"><strong>난이도</strong>초·중급 (신규·실무자 수준)</li>
						<li class="i5"><strong>평가목표</strong>연구행정의 표준 프로세스 이해 및 정확한 문서처리 역량 검증</li>
					</ul>
					<div class="otit">취득방법</div>
					<div class="tbl th_bg mo_break mo_break_tbl_row">
						<table>
							<colgroup>
								<col class="w200">
								<col>
								<col class="w200">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th>응시자격</th>
									<td>연구행정 실무자, 산학협력단 및 연구지원 부서 종사자</td>
									<th>교육이수</th>
									<td>기본과정(연구비, 회계, 계약, 연구윤리 등) 이수</td>
								</tr>
								<tr>
									<th>응시절차</th>
									<td>집합교육이수 → 시험 접수 → 필기시험(객관식) <br class="pc_vw">→ 합격자 발표</td>
									<th>합격기준</th>
									<td>100점 만점 기준 60점 이상</td>
								</tr>
								<tr>
									<th>자격유효기간</th>
									<td>5년 (갱신제 운영 예정)</td>
									<th>시행시기</th>
									<td>2026년 하반기 예정</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btns_tac">
						<a href="/education_certification/application_ec" class="btn btn_link btn_wbb">자격 시험 신청하기</a>
					</div>
				</div><!-- //대한연구행정전문가 2급 -->
			</div>
		</div>
		
	</div>
    
</main>

<script>
$(document).ready (function () {
	//탭(ul) onoff
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