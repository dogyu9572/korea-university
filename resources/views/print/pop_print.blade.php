<div class="popup pop_slide_set" id="popPrint">
	<div class="dm" onclick="layerSlideHide('popPrint')"></div>
	<div class="inbox">
		<div class="tit">출력</div>
		<div class="con print_select">
			@if(isset($application))
			@if($application->payment_status === '입금완료')
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.receipt', $application->id) }}"><span>영수증 출력</span></label>
			@endif
			@if($application->is_completed && $application->is_survey_completed)
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.certificate_completion', $application->id) }}"><span>수료증 출력</span></label>
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.certificate_finish', $application->id) }}"><span>이수증 출력</span></label>
			@endif
			@elseif(isset($gNum) && $gNum == '05' && $sNum == '01')
			<label class="select"><input type="radio" name="print" data-url="/print/receipt"><span>영수증 출력</span></label>
			<label class="select"><input type="radio" name="print" data-url="/print/certificate_completion"><span>수료증 출력</span></label>
			<label class="select"><input type="radio" name="print" data-url="/print/certificate_finish"><span>이수증 출력</span></label>
			@endif
			@if(isset($gNum) && $gNum == '05' && $sNum == '02')
			@if(isset($application) && $application->certification_id)
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.admission_ticket', $application->id) }}"><span>수험표 출력</span></label>
			@if($application->payment_status === '입금완료')
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.receipt', $application->id) }}"><span>영수증 출력</span></label>
			@endif
			@if($application->is_qualification_passed)
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.certificate_qualification', $application->id) }}"><span>합격확인서 출력</span></label>
			<label class="select"><input type="radio" name="print" data-url="{{ route('mypage.print.qualification_certificate', $application->id) }}"><span>자격증 출력</span></label>
			@endif
			@else
			<div class="print_select" id="popPrintQualificationOptions"></div>
			@endif
			@endif
		</div>
		<div class="btns_tac mt0">
			<button type="button" class="btn btn_kwg" onclick="layerSlideHide('popPrint')">취소</button>
			<button type="button" class="btn btn_bwb btn_print">출력하기</button>
		</div>
	</div>
</div>

<script>
//출력
function layerSlideShow(id) {
	const $layer = $("#" + id);
	$layer.css("display", "block"); // display만 먼저
	$layer.children(".dm").stop(true, true).fadeIn("fast");
	$layer.children(".inbox").stop(true, true).slideDown("fast");
	$("html,body").addClass("over_h");
}

function layerSlideHide(id, fromSwipe = false) {
	const $layer = $("#" + id);
	const $inbox = $layer.children(".inbox");

	$layer.children(".dm").stop(true, true).fadeOut("fast");
	$("html,body").removeClass("over_h");

	if (fromSwipe) {
		// 👉 스와이프 닫기: transform 기준
		const winH = $(window).height();

		$inbox.css({
			transition: "transform 0.25s ease",
			transform: `translateY(${winH}px)`
		});

		setTimeout(function () {
			$inbox.css({ transform: "", transition: "" });
			$layer.css("display", "none");
		}, 250);

	} else {
		// 👉 기존 닫기: slideUp
		$inbox.stop(true, true).slideUp("fast", function () {
			$layer.css("display", "none");
		});
	}
}

let startY = 0;
let moveY = 0;
const swipeCloseDistance = 80;
$(document).on("touchstart", "#popPrint .inbox", function (e) {
	startY = e.originalEvent.touches[0].clientY;
	moveY = 0;
});
$(document).on("touchmove", "#popPrint .inbox", function (e) {
	moveY = e.originalEvent.touches[0].clientY - startY;
	if (moveY > 0) {
		$(this).css({
			transform: `translateY(${moveY}px)`,
			transition: "none"
		});
	}
});
$(document).on("touchend", "#popPrint .inbox", function () {
	if (moveY > swipeCloseDistance) {
		layerSlideHide("popPrint", true);
	} else {
		$(this).css({
			transform: "translateY(0)",
			transition: "transform 0.2s ease"
		});
	}
});

$(document).on("click", "#popPrint .btn_print", function () {
	const $checked = $("#popPrint input[name='print']:checked");
	if (!$checked.length) {
		alert("출력할 항목을 선택해 주세요.");
		return;
	}
	const url = $checked.data("url");
	window.open(url, "_blank");
	$("#popPrint input[name='print']").prop("checked", false);
	layerSlideHide("popPrint");
});
</script>