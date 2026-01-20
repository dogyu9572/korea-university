@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="stitle">2025년 해외연수 운영 안내</div>

	<div class="imgfit radius40_4 img_top img_overseas_training_top"></div>
	
	<div class="btit">정기 해외연수 운영 일정</div>
	<p>대학 연구관리자의 전문성 제고와 대학 간 교류 활성화를 위해 매년 정기 해외연수를 운영하고 있습니다.<br/>
	해외연수는 산학협력과 연구관리 실무자 대상의 연수로, 해외 우수사례 학습, 제도 개선사례 탐방, 전문가 간 정보 교류 등의 프로그램으로 구성됩니다.</p>
	
	<div class="tbl mt40 mo_break mo_break_th_tbl ">
		<table>
			<colgroup>
				<col class="w100">
				<col>
				<col class="w240">
				<col class="w360">
			</colgroup>
			<thead>
				<tr>
					<th>구분</th>
					<th>해외연수명</th>
					<th>장소</th>
					<th>주관기관</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="overseas01">상반기</td>
					<th class="overseas02"><a href="/seminars_training/application_st_view">2025년 전국대학 연구·산학협력관리자 협의회 하계 해외연수</a></th>
					<td class="overseas03">스페인 및 이탈리아 일원</td>
					<td class="overseas04">전국대학 연구 · 산학협력관리자 협의회</td>
				</tr>
				<tr>
					<td class="overseas01">하반기</td>
					<th class="overseas02"><a href="/seminars_training/application_st_view">2025년 전국대학 연구·산학협력관리자 협의회 추계 해외연수</a></th>
					<td class="overseas03">스페인 및 이탈리아 일원</td>
					<td class="overseas04">전국대학 연구 · 산학협력관리자 협의회</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="btns_tac">
		<a href="/seminars_training/application_st" class="btn btn_link btn_wbb">해외연수 신청하기</a>
	</div>
	
</main>
@endsection

