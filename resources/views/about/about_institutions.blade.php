@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">

	<div class="search_wrap">
		<form method="GET" action="{{ route('about.about_institutions') }}" id="institutionsSearchForm">
			<div class="flex bdb">
				<dl>
					<dt>회원 학교명</dt>
					<dd><input type="text" name="school_name" class="w100p" placeholder="회원 학교명을 입력해주세요." value="{{ $filters['school_name'] ?? '' }}"></dd>
				</dl>
				<dl>
					<dt>지회</dt>
					<dd class="dates">
						<select name="branch" class="w100p">
							@foreach($branchOptions ?? [] as $value => $label)
							<option value="{{ $value }}" @selected(($filters['branch'] ?? '') == $value)>{{ $label }}</option>
							@endforeach
						</select>
					</dd>
				</dl>
			</div>
			<div class="btns_tac mt0">
				<button type="submit" class="btn btn_search btn_wbb btn_w160">검색</button>
				<a href="{{ route('about.about_institutions') }}" class="btn btn_reset btn_bwb btn_w160">초기화</a>
			</div>
		</form>
	</div>

	<div class="tbl board_list tbl_tac">
		<table>
			<colgroup>
				<col class="slide3">
				<col class="slide3">
				<col class="slide3">
			</colgroup>
			<thead>
				<tr>
					<th>지회</th>
					<th>회원학교</th>
					<th>공식 웹사이트</th>
				</tr>
			</thead>
			<tbody>
				@forelse($schools as $school)
				<tr>
					<td class="branch">{{ $school->branch ?? '' }}</td>
					<td class="school">{{ $school->school_name ?? '' }}</td>
					<td class="link">
						@if(!empty($school->url))
						<a href="{{ $school->url }}" target="_blank" rel="noopener noreferrer" class="btn_outlink">링크</a>
						@else
						-
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="3" class="tac">등록된 회원기관이 없습니다.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	@include('notice.partials.pagination', ['posts' => $schools])

</main>
@endsection
