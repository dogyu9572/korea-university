@php
	$enrolled = $item->applications_count ?? 0;
	$capacity = $item->capacity ?? 0;
	$capacityUnlimited = $item->capacity_unlimited ?? false;
	$hasRemain = $capacityUnlimited || $enrolled < $capacity;

	$status = $item->application_status ?? '';
	if ($status === '접수중') {
		$btnClass = 'btn btn_write btn_wbb';
		$btnText = '신청하기';
		$applyUrl = url('education_certification/application_ec_e_learning/' . $item->id);
	} elseif ($status === '접수마감') {
		$btnClass = 'btn btn_end';
		$btnText = '신청마감';
		$applyUrl = 'javascript:void(0);';
	} else {
		$btnClass = 'btn btn_wkk';
		$btnText = '개설예정';
		$applyUrl = 'javascript:void(0);';
	}
	$viewUrl = url('education_certification/application_ec_view/' . $item->id);

	$appStart = $item->application_start ? $item->application_start->format('Y.m.d.(D)') : '';
	$appEnd = $item->application_end ? $item->application_end->format('Y.m.d.(D)') : '';
	$periodStart = $item->period_start ? $item->period_start->format('Y.m.d.(D)') : '';
	$periodEnd = $item->period_end ? $item->period_end->format('Y.m.d.(D)') : '';
	$periodText = $periodStart && $periodEnd ? $periodStart . ' ~ ' . $periodEnd : '-';

	$educationClass = $item->education_class ?? '';
	if (!empty($item->completion_hours)) {
		$educationClass = $educationClass ? $educationClass . '(인정시간: ' . $item->completion_hours . '시간)' : '인정시간: ' . $item->completion_hours . '시간';
	}
	$educationClass = $educationClass ?: '-';

	$thumb = $item->thumbnail_path ?: '/images/img_application_ec_sample.jpg';
@endphp
<div class="box">
	<div class="imgfit"><img src="{{ $thumb }}" alt=""></div>
	<div class="txt">
		<div class="tit"><span class="type c4">온라인교육</span>{{ $item->name ?? '' }}</div>
		<div class="info">
			<dl class="i1">
				<dt>정원</dt>
				<dd>{!! $hasRemain ? '<strong class="c_iden">' . $enrolled . '명</strong>' : '<strong>' . $enrolled . '명</strong>' !!} / {{ $capacityUnlimited ? '무제한' : $capacity . '명' }}</dd>
			</dl>
			<dl class="i2">
				<dt>교육대상</dt>
				<dd>{{ $item->target ?? '-' }}</dd>
			</dl>
			<dl class="i3">
				<dt>신청기간</dt>
				<dd>{{ $appStart && $appEnd ? $appStart . ' ~ ' . $appEnd : ($appStart ?: '-') }}</dd>
			</dl>
			<dl class="i4">
				<dt>교육기간</dt>
				<dd>{{ $periodText }}</dd>
			</dl>
			<dl class="i5">
				<dt>교육구분</dt>
				<dd>{{ $educationClass }}</dd>
			</dl>
			<dl class="i6">
				<dt>교육장소</dt>
				<dd>온라인 학습</dd>
			</dl>
		</div>
		<div class="btns_tal">
			<a href="{{ $applyUrl }}" class="{{ $btnClass }}">{{ $btnText }}</a>
			<a href="{{ $viewUrl }}" class="btn btn_link btn_bwb">상세보기</a>
		</div>
	</div>
</div>
