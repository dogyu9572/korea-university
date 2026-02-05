@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
	
	<div class="stitle tal">교육 신청 현황</div>

	@if(session('success'))
	<p class="msg_success">{{ session('success') }}</p>
	@endif
	@if(session('error'))
	<p class="msg_error">{{ session('error') }}</p>
	@endif
	
	<form method="GET" action="{{ route('mypage.application_status') }}" class="search_wrap">
		<dl>
			<dt>교육명</dt>
			<dd><input type="text" name="name" class="text w100p" placeholder="교육명을 입력해주세요." value="{{ request('name') }}"></dd>
		</dl>
		<div class="filter_opcl on">
			<button type="button" class="btn_opcl">검색 필터</button>
			<div class="con" style="display:block;">
				<div class="flex">
					<dl>
						<dt>교육구분</dt>
						<dd>
							<select name="education_type" class="w100p">
								@foreach($educationTypeOptions as $opt)
								<option value="{{ $opt }}" @selected((request('education_type') ?? '전체') === $opt)>{{ $opt }}</option>
								@endforeach
							</select>
						</dd>
					</dl>
					<dl>
						<dt>교육기간</dt>
						<dd class="dates half">
							<select name="period_year">
								<option value="">연도</option>
								@for($y = now()->year; $y >= now()->year - 5; $y--)
								<option value="{{ $y }}" @selected(request('period_year') == $y)>{{ $y }}</option>
								@endfor
							</select>
							<select name="period_month">
								<option value="">전체</option>
								@for($m = 1; $m <= 12; $m++)
								<option value="{{ $m }}" @selected(request('period_month') == $m)>{{ $m }}월</option>
								@endfor
							</select>
						</dd>
					</dl>
					<dl>
						<dt>진행상태</dt>
						<dd>
							<select name="status" class="w100p">
								@foreach($statusOptions as $opt)
								<option value="{{ $opt }}" @selected((request('status') ?? '전체') === $opt)>{{ $opt }}</option>
								@endforeach
							</select>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="btns_tac mt0">
			<button type="submit" class="btn btn_search btn_wbb btn_w160">검색</button>
			<a href="{{ route('mypage.application_status') }}" class="btn btn_reset btn_bwb btn_w160">초기화</a>
		</div>
	</form>
	
	<ul class="application_status">
		<li class="i1"><strong>오프라인 교육 수료 기준</strong><p>교육이수 기간 90% 이상(지정좌석제 운영) 참석 및 <br class="pc_vw">설문조사 제출시 수료증 부여</p></li>
		<li class="i2"><strong>온라인 교육 수료 기준</strong><p>전체 강의의 80% 이상 시청 및 설문조사 제출시 수료증 부여</p></li>
	</ul>
	
	<div class="board_top mt80">
		<div class="left">
			<p>TOTAL <strong>{{ $applications->total() }}</strong></p>
		</div>
	</div>
	
	<ul class="application_list">
		@forelse($applications as $item)
		@php
			$program = $item->program;
			$stateClass = match($item->display_status) {
				'신청완료' => 'appli',
				'수료' => 'complete',
				'미수료' => 'incomplete',
				default => 'appli',
			};
			$detailUrl = $item->online_education_id
				? route('mypage.application_status_view2', $item->id)
				: route('mypage.application_status_view', $item->id);
			$periodStr = $program && $program->period_start
				? format_date_ko($program->period_start) . ($program->period_end ? ' - ' . format_date_ko($program->period_end) : '')
				: '';
			$refundAmount = $item->participation_fee !== null ? number_format((float) $item->participation_fee) : '0';
			$canReceipt = $item->payment_status === '입금완료';
			$canCertificate = $item->is_completed && $item->is_survey_completed;
			$receiptUrl = $canReceipt ? route('mypage.print.receipt', $item->id) : '';
			$completionUrl = $canCertificate ? route('mypage.print.certificate_completion', $item->id) : '';
			$finishUrl = $canCertificate ? route('mypage.print.certificate_finish', $item->id) : '';
		@endphp
		<li data-application-id="{{ $item->id }}"
			data-education-type="{{ $item->education_type_label }}"
			data-name="{{ $program ? e($program->name) : '' }}"
			data-period="{{ $periodStr }}"
			data-refund="{{ $refundAmount }}"
			data-receipt-url="{{ $receiptUrl }}"
			data-completion-url="{{ $completionUrl }}"
			data-finish-url="{{ $finishUrl }}">
			<a href="{{ $detailUrl }}" class="link">
				<span class="statebox"><i class="state {{ $stateClass }}">{{ $item->display_status }}</i></span>
				<span class="tit">{{ $program ? $program->name : '' }}</span>
				<dl>
					<dt>교육구분</dt>
					<dd>{{ $item->education_type_label }}</dd>
				</dl>
				<dl>
					<dt>교육기간</dt>
					<dd>{{ $periodStr }}</dd>
				</dl>
			</a>
			<div class="btns">
				<button type="button" class="btn btn_wbb btn_print btn_print_list" data-target="popPrint">출력</button>
				@if($item->display_status !== '수료')
				<button type="button" class="btn btn_cancel" onclick="openCancelPopup(this)">수강취소</button>
				@endif
			</div>
		</li>
		@empty
		<li class="empty">등록된 신청이 없습니다.</li>
		@endforelse
	</ul>

	@php $posts = $applications; @endphp
	@include('notice.partials.pagination')
	
	<!-- 수강취소 -->
	<div class="popup" id="classCancel">
		<div class="dm" onclick="layerHide('classCancel')"></div>
		<div class="inbox">
			<button type="button" class="btn_close" onclick="layerHide('classCancel')">닫기</button>
			<div class="tit">수강취소</div>
			<form method="POST" action="{{ route('mypage.application_status.cancel') }}" id="formCancel">
				@csrf
				<input type="hidden" name="application_id" id="cancel_application_id" value="">
				<div class="con gbox">
					<dl>
						<dt>교육구분</dt>
						<dd id="cancel_education_type"></dd>
					</dl>
					<dl>
						<dt>교육명</dt>
						<dd id="cancel_name"></dd>
					</dl>
					<dl>
						<dt>교육기간</dt>
						<dd id="cancel_period"></dd>
					</dl>
					<dl>
						<dt>환불금액</dt>
						<dd id="cancel_refund"></dd>
					</dl>
				</div>
				<div class="btns_tac">
					<button type="submit" class="btn btn_wbb">취소하기</button>
				</div>
			</form>
		</div>
	</div>
	
	<!-- 출력 -->
	@include('print.pop_print')
    
</main>

<script>
$(".filter_opcl .btn_opcl").click(function(){
	$(this).next(".con").slideToggle("fast").parent().toggleClass("on");
});
$('.btn_reset').on('click', function () {
	const $wrap = $(this).closest('.search_wrap');
	$wrap.find('input[type="text"]').val('');
	$wrap.find('.con select').each(function () {
		this.selectedIndex = 0;
	});
	$wrap.find('.con input[type="checkbox"], .con input[type="radio"]').prop('checked', false);
});

// 목록에서 출력 클릭 시 해당 행에서 출력 가능한 항목만 팝업에 표시
$(document).on('click', '.application_list .btn_print_list', function () {
	var $btn = $(this);
	var $li = $btn.closest('li');
	var receiptUrl = $li.data('receipt-url') || '';
	var completionUrl = $li.data('completion-url') || '';
	var finishUrl = $li.data('finish-url') || '';
	var parts = [];
	if (receiptUrl) {
		parts.push('<label class="select"><input type="radio" name="print" data-url="' + receiptUrl.replace(/"/g, '&quot;') + '"><span>영수증 출력</span></label>');
	}
	if (completionUrl) {
		parts.push('<label class="select"><input type="radio" name="print" data-url="' + completionUrl.replace(/"/g, '&quot;') + '"><span>수료증 출력</span></label>');
	}
	if (finishUrl) {
		parts.push('<label class="select"><input type="radio" name="print" data-url="' + finishUrl.replace(/"/g, '&quot;') + '"><span>이수증 출력</span></label>');
	}
	if (parts.length === 0) {
		alert('출력 가능한 증빙이 없습니다.');
		return;
	}
	$('#popPrint .print_select').html(parts.join(''));
	layerSlideShow('popPrint');
});

//팝업
function layerShow(id) {
	$("#" + id).fadeIn(300);
	$("html,body").addClass("over_h");
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
	$("html,body").removeClass("over_h");
}
function openCancelPopup(btn) {
	var $li = $(btn).closest('li.application_list > li, .application_list li');
	if (!$li.length) $li = $(btn).closest('li');
	var id = $li.data('application-id');
	var educationType = $li.data('education-type') || '';
	var name = $li.data('name') || '';
	var period = $li.data('period') || '';
	var refund = $li.data('refund') || '0';
	$('#cancel_application_id').val(id);
	$('#cancel_education_type').text(educationType);
	$('#cancel_name').text(name);
	$('#cancel_period').text(period);
	$('#cancel_refund').text(refund + '원');
	layerShow('classCancel');
}

//검색 PC 열림, 모바일 닫힘
function toggleFilterOn() {
	if ($(window).width() >= 768) {
		$('.filter_opcl').addClass('on');
		$(".search_wrap .filter_opcl .con").show();
	} else {
		$('.filter_opcl').removeClass('on');
		$(".search_wrap .filter_opcl .con").hide();
	}
}
toggleFilterOn();
$(window).on('resize', toggleFilterOn);
</script>

@endsection