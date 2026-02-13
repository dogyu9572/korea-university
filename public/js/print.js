/**
 * 증빙 출력 공통: 인쇄 / PDF 다운로드
 * .print_area의 data-pdf-filename을 파일명으로 사용
 */
(function () {
	'use strict';

	function onPrintClick() {
		var printContents = document.querySelector('.print_inbox');
		if (!printContents) return;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents.innerHTML;
		window.print();
		document.body.innerHTML = originalContents;
		location.reload();
	}

	function onPdfDownClick() {
		var area = document.querySelector('.print_area');
		if (!area || typeof html2pdf === 'undefined') return;
		var filename = area.getAttribute('data-pdf-filename') || 'document.pdf';
		var opt = {
			margin: 0,
			filename: filename,
			image: { type: 'jpeg', quality: 0.98 },
			html2canvas: { 
				scale: 2, 
				useCORS: true,
					width:794,
				ignoreElements: function (element) {
					return element.classList.contains('btns');
				}
			},
			jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
		};
		html2pdf().set(opt).from(area).save();
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.btn_print').forEach(function (el) {
			el.addEventListener('click', onPrintClick);
		});
		document.querySelectorAll('.btn_down').forEach(function (el) {
			el.addEventListener('click', onPdfDownClick);
		});
	});
})();
