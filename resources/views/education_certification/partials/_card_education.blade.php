@php
	$typeClass = ($item->education_type ?? '') === '수시교육' ? 'c2' : 'c1';
	$enrolled = $item->applications_count ?? 0;
	$capacity = $item->capacity ?? 0;
	$capacityUnlimited = $item->capacity_unlimited ?? false;
	$hasRemain = $capacityUnlimited || $enrolled < $capacity;
	$capacityText = $capacityUnlimited ? $enrolled . '명 / 무제한' : $enrolled . '명 / ' . $capacity . '명';

	$status = $item->application_status ?? '';
	if ($status === '접수중') {
		$btnClass = 'btn btn_write btn_wbb';
		$btnText = '신청하기';
		$applyUrl = url('education_certification/application_ec_apply/' . $item->id);
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
	$periodTime = $item->period_time ?? '';
	$periodText = $periodStart && $periodEnd ? $periodStart . ' ~ ' . $periodEnd : ($periodStart ? $periodStart . ($periodTime ? ' ' . $periodTime : '') : '-');

	$feeParts = [];
	foreach (['fee_member_twin', 'fee_member_single', 'fee_member_no_stay', 'fee_guest_twin', 'fee_guest_single', 'fee_guest_no_stay'] as $f) {
		if (!empty($item->$f)) {
			$feeParts[] = number_format($item->$f) . '원';
		}
	}
	$feeText = implode(', ', $feeParts) ?: '';

	$thumb = $item->thumbnail_path ?: '/images/img_application_ec_sample.jpg';
@endphp
<div class="box">
	<div class="imgfit"><img src="{{ $thumb }}" alt=""></div>
	<div class="txt">
		<div class="tit"><span class="type {{ $typeClass }}">{{ $item->education_type ?? '정기교육' }}</span>{{ $item->name ?? '' }}</div>
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
				<dd>{{ $item->education_class ?? '-' }}</dd>
			</dl>
			<dl class="i6">
				<dt>교육장소</dt>
				<dd>{{ $item->location ?? '-' }}</dd>
			</dl>
			@if($feeText)
			<dl class="i7">
				<dt>참가비</dt>
				<dd>{{ $feeText }}</dd>
			</dl>
			@endif
		</div>
		<div class="btns_tal">
			<a href="{{ $applyUrl }}" class="{{ $btnClass }}">{{ $btnText }}</a>
			<a href="{{ $viewUrl }}" class="btn btn_link btn_bwb">상세보기</a>
		</div>
	</div>
</div>
