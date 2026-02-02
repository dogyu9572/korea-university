/**
 * 휴대폰번호 입력 마스크 (회원가입·아이디 찾기 등 공통)
 * - 숫자만 허용, 하이픈 자동 삽입 (010-1234-5678 / 02-123-45678 등)
 */
(function () {
	'use strict';
	function formatPhoneInput(value) {
		var digits = (value || '').replace(/\D/g, '');
		if (digits.indexOf('02') === 0) {
			digits = digits.slice(0, 10);
			if (digits.length <= 2) return digits;
			if (digits.length <= 5) return digits.slice(0, 2) + '-' + digits.slice(2);
			return digits.slice(0, 2) + '-' + digits.slice(2, 5) + '-' + digits.slice(5);
		}
		digits = digits.slice(0, 11);
		if (digits.length <= 3) return digits;
		if (digits.length <= 7) return digits.slice(0, 3) + '-' + digits.slice(3);
		return digits.slice(0, 3) + '-' + digits.slice(3, 7) + '-' + digits.slice(7);
	}
	function bindPhoneInput() {
		var $phoneInput = $('input[name="phone_number"]');
		if (!$phoneInput.length) return;
		$phoneInput.off('input.phoneInput paste.phoneInput');
		$phoneInput.on('input.phoneInput', function () {
			var pos = this.selectionStart;
			var oldLen = this.value.length;
			var formatted = formatPhoneInput(this.value);
			this.value = formatted;
			var newLen = formatted.length;
			var newPos = Math.max(0, pos + (newLen - oldLen));
			if (this.setSelectionRange) this.setSelectionRange(newPos, newPos);
		});
		$phoneInput.on('paste.phoneInput', function () {
			var el = this;
			setTimeout(function () { el.value = formatPhoneInput(el.value); }, 0);
		});
	}
	$(function () {
		bindPhoneInput();
	});
})();
