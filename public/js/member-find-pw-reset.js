/**
 * 비밀번호 재설정 페이지
 * - 새 비밀번호: 영문/숫자/특수문자 중 두 가지 이상, 8~10자 검증 (서버와 동일)
 */
(function () {
	'use strict';

	function isValidPassword(pw) {
		if (!pw || pw.length < 8 || pw.length > 10) return false;
		var hasLetter = /[a-zA-Z]/.test(pw);
		var hasDigit = /[0-9]/.test(pw);
		var hasSpecial = /[^a-zA-Z0-9]/.test(pw);
		return (hasLetter ? 1 : 0) + (hasDigit ? 1 : 0) + (hasSpecial ? 1 : 0) >= 2;
	}

	function showMsg(id, msg) {
		var el = document.getElementById(id);
		if (!el) return;
		if (msg) {
			el.textContent = msg;
			el.style.display = 'block';
		} else {
			el.textContent = '';
			el.style.display = 'none';
		}
	}

	var form = document.getElementById('findPwResetForm');
	if (form) {
		form.addEventListener('submit', function (e) {
			showMsg('passwordCheckMsg', '');
			showMsg('passwordConfirmCheckMsg', '');

			var pwd = (form.querySelector('input[name="password"]') || {}).value || '';
			var pwdConf = (form.querySelector('input[name="password_confirmation"]') || {}).value || '';

			if (!pwd.trim()) {
				showMsg('passwordCheckMsg', '비밀번호를 입력해 주세요.');
				form.querySelector('input[name="password"]').focus();
				e.preventDefault();
				return;
			}
			if (!isValidPassword(pwd)) {
				showMsg('passwordCheckMsg', '비밀번호는 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자로 입력해 주세요.');
				form.querySelector('input[name="password"]').focus();
				e.preventDefault();
				return;
			}
			if (pwd !== pwdConf) {
				showMsg('passwordConfirmCheckMsg', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
				form.querySelector('input[name="password_confirmation"]').focus();
				e.preventDefault();
			}
		});
	}
})();
