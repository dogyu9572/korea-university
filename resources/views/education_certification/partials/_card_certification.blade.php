@php
	$status = $item->application_status ?? '';
	if ($status === '접수중') {
		$btnClass = 'btn btn_write btn_wbb';
		$btnText = '신청하기';
		$applyUrl = url('education_certification/application_ec_receipt/' . $item->id);
	} elseif ($status === '접수마감') {
		$btnClass = 'btn btn_end';
		$btnText = '신청마감';
		$applyUrl = 'javascript:void(0);';
	} else {
		$btnClass = 'btn btn_wkk';
		$btnText = '개설예정';
		$applyUrl = 'javascript:void(0);';
	}
	$viewUrl = url('education_certification/application_ec_view_type2/' . $item->id);

	$appStart = $item->application_start ? $item->application_start->format('Y.m.d.(D)') : '';
	$appEnd = $item->application_end ? $item->application_end->format('Y.m.d.(D)') : '';
	$examDate = $item->exam_date ? $item->exam_date->format('Y.m.d.(D)') : '';

	$thumb = $item->thumbnail_path ?: '/images/img_application_ec_sample2.jpg';
@endphp
<div class="box">
	<div class="imgfit"><img src="{{ $thumb }}" alt=""></div>
	<div class="txt">
		<div class="tit"><span class="type c3">자격증</span>{{ $item->name ?? '' }}</div>
		<div class="info">
			<dl class="i1">
				<dt>응시자격</dt>
				<dd>{{ \Illuminate\Support\Str::limit(strip_tags($item->eligibility ?? ''), 80) ?: '-' }}</dd>
			</dl>
			<dl class="i3">
				<dt>접수기간</dt>
				<dd>{{ $appStart && $appEnd ? $appStart . ' ~ ' . $appEnd : ($appStart ?: '-') }}</dd>
			</dl>
			<dl class="i4">
				<dt>시험일</dt>
				<dd>{{ $examDate ?: '-' }}</dd>
			</dl>
		</div>
		<div class="btns_tal">
			<a href="{{ $applyUrl }}" class="{{ $btnClass }}">{{ $btnText }}</a>
			<a href="{{ $viewUrl }}" class="btn btn_link btn_bwb">상세보기</a>
		</div>
	</div>
</div>
