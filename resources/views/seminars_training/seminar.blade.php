@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle">2025년 세미나 운영 안내</div>

	<div class="imgfit radius40_4 img_top img_seminar_top"></div>
	
	<div class="btit">정기 세미나 운영 일정</div>
	<p>대학 연구관리자의 전문성 제고와 대학 간 교류 활성화를 위해 매년 상·하반기 정기 세미나를 운영하고 있습니다.<br/>
	세미나는 연구관리·산학협력 실무자의 정책 이해도 향상, 제도 개선사항 공유, 우수사례 발표 등을 중심으로 구성되어 있으며, 정부 부처 및 유관기관의 전문가 강연도 함께 진행됩니다.</p>
	
	<div class="tbl mt40 mo_break mo_break_th_tbl">
		<table>
			<colgroup>
				<col class="w100">
				<col>
				<col class="w200">
				<col class="w230">
				<col class="w320">
			</colgroup>
			<thead>
				<tr>
					<th>구분</th>
					<th>세미나명</th>
					<th>장소</th>
					<th>기간</th>
					<th>주관기관</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="seminar01">상반기</td>
					<th class="seminar02"><a href="/seminars_training/application_st_view">2025년 전국대학 연구·산학협력관리자 협회 하계 세미나</a></th>
					<td class="seminar03">경주 라한셀렉트호텔</td>
					<td class="seminar04">2025년 6월 중 (2박 3일)</td>
					<td class="seminar05">전국대학 연구 · 산학협력관리자 협회</td>
				</tr>
				<tr>
					<td class="seminar01">하반기</td>
					<th class="seminar02"><a href="/seminars_training/application_st_view">2025년 전국대학 연구·산학협력관리자 협회 추계 세미나</a></th>
					<td class="seminar03">그랜드 하얏트 제주</td>
					<td class="seminar04">025년 11월 5일(수) <br class="pc_vw"/>~ 11월 7일(금), 2박 3일</td>
					<td class="seminar05">전국대학 연구 · 산학협력관리자 협회</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="btns_tac">
		<a href="/seminars_training/application_st" class="btn btn_link btn_wbb">세미나 신청하기</a>
	</div>
	
</main>
@endsection

