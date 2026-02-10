/**
 * 회원가입 페이지 (사용자)
 * - 약관 전체 동의, 아코디언, 이메일/휴대폰 중복확인, 우편번호 검색, 학교명 직접입력·회원교 선택 연동
 */
window.layerShow = function (id) {
	$("#" + id).fadeIn(300);
	if (id === 'searchSchool') {
		$(document).trigger('searchSchoolOpened');
	}
};
window.layerHide = function (id) { $("#" + id).fadeOut(300); };

$(function () {
	// 유효성 검사 실패 시 첫 번째 보이는 에러 위치로 스크롤 (약관만 미체크면 회원가입 버튼과 가까워 스크롤 생략)
	if (document.querySelector('[data-join-errors]')) {
		function scrollToFirstError() {
			var errs = document.querySelectorAll('#joinForm .join_field_error');
			var firstErr = null;
			for (var i = 0; i < errs.length; i++) {
				if (errs[i].offsetParent !== null) {
					firstErr = errs[i];
					break;
				}
			}
			if (!firstErr) return;
			if (firstErr.closest('[data-join-section="terms"]')) return;
			var dl = firstErr.closest('dl');
			var input = dl ? dl.querySelector('input:not([type="hidden"]):not([readonly])') : null;
			var scrollTarget = (input && input.offsetParent) ? input : (dl || firstErr);
			scrollTarget.style.scrollMarginTop = '220px';
			scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
			setTimeout(function () {
				scrollTarget.style.scrollMarginTop = '';
				if (input) input.focus();
			}, 500);
		}
		setTimeout(scrollToFirstError, 150);
	}

	// 약관 전체 동의
	var $allCheck = $('#allCheck');
	$allCheck.on('change', function () {
		var checked = $(this).prop('checked');
		$('input[name="terms_privacy"], input[name="terms_service"]').prop('checked', checked);
	});
	$('input[name="terms_privacy"], input[name="terms_service"]').on('change', function () {
		var total = $('input[name="terms_privacy"], input[name="terms_service"]').length;
		var checked = $('input[name="terms_privacy"]:checked, input[name="terms_service"]:checked').length;
		$allCheck.prop('checked', total === checked);
	});

	// 약관 아코디언 열기/닫기
	$('.aco_area .aco dt .btn').on('click', function () {
		var $aco = $(this).closest('.aco');
		var isOpen = $aco.hasClass('on');
		$aco.siblings('.aco').removeClass('on').children('dd').slideUp('fast').end().find('.btn').text('열기');
		$aco.toggleClass('on').children('dd').stop(false, true).slideToggle('fast');
		$(this).text(isOpen ? '열기' : '닫기');
	});

	// 이메일 중복 확인 (메시지는 항목 하단 빨간 글씨로 표시)
	function showEmailCheckMsg(msg) {
		var $el = $('#emailCheckMsg');
		if (msg) {
			$el.text(msg).css('display', 'block');
		} else {
			$el.text('').css('display', 'none');
		}
	}
	function isValidEmail(email) {
		if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) return false;
		var domain = email.split('@')[1].toLowerCase();
		var blocked = [
			'example.com', 'example.org', 'example.net', 'example.edu',
			'test.com', 'test.org', 'test.net',
			'aaa.com', 'bbb.com', 'ccc.com',
			'asdf.com', 'qwerty.com', 'sample.com', 'domain.com',
			'localhost', 'homepage.com', 'temp.com', 'fake.com'
		];
		return blocked.indexOf(domain) === -1;
	}
	function isValidPassword(pw) {
		if (!pw || pw.length < 8 || pw.length > 10) return false;
		var hasLetter = /[a-zA-Z]/.test(pw);
		var hasDigit = /[0-9]/.test(pw);
		var hasSpecial = /[^a-zA-Z0-9]/.test(pw);
		return (hasLetter ? 1 : 0) + (hasDigit ? 1 : 0) + (hasSpecial ? 1 : 0) >= 2;
	}
	$('#btnCheckEmail').on('click', function () {
		var email = $('input[name="email"]').val().trim();
		showEmailCheckMsg('');
		if (!email) {
			showEmailCheckMsg('이메일을 입력해 주세요.');
			$('input[name="email"]').focus();
			return;
		}
		if (!isValidEmail(email)) {
			showEmailCheckMsg('유효하지 않은 메일주소입니다.');
			$('input[name="email"]').focus();
			return;
		}
		var token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		fetch('/member/check-email', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': token || '',
				'Accept': 'application/json'
			},
			body: JSON.stringify({ email: email })
		})
		.then(function (res) { return res.json(); })
		.then(function (result) {
			showEmailCheckMsg(result.message);
			if (result.available) {
				$('#email_verified').val('1');
			} else {
				$('#email_verified').val('0');
				$('input[name="email"]').focus();
			}
		})
		.catch(function () {
			showEmailCheckMsg('이메일 중복 확인 중 오류가 발생했습니다.');
		});
	});
	$('input[name="email"]').on('input', function () {
		$('#email_verified').val('0');
		showEmailCheckMsg('');
	});

	// 휴대폰 중복 확인 (메시지는 항목 하단 빨간 글씨로 표시)
	function showPhoneCheckMsg(msg) {
		var $el = $('#phoneCheckMsg');
		if (msg) {
			$el.text(msg).css('display', 'block');
		} else {
			$el.text('').css('display', 'none');
		}
	}
	function showPasswordCheckMsg(msg) {
		var $el = $('#passwordCheckMsg');
		if (msg) {
			$el.text(msg).css('display', 'block');
		} else {
			$el.text('').css('display', 'none');
		}
	}
	function showPasswordConfirmCheckMsg(msg) {
		var $el = $('#passwordConfirmCheckMsg');
		if (msg) {
			$el.text(msg).css('display', 'block');
		} else {
			$el.text('').css('display', 'none');
		}
	}
	function scrollToField(inputEl) {
		if (!inputEl || !inputEl.getBoundingClientRect) return;
		inputEl.style.scrollMarginTop = '220px';
		inputEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
		setTimeout(function () {
			inputEl.style.scrollMarginTop = '';
			inputEl.focus();
		}, 500);
	}
	function isValidPhone(phone) {
		var digits = (phone || '').replace(/\D/g, '');
		if (digits.length === 11 && digits.indexOf('010') === 0) return true;
		if (digits.length === 10 && digits.indexOf('02') === 0) return true;
		if (digits.length === 10 && /^01[1-9]/.test(digits)) return true;
		return false;
	}
	$('#btnCheckPhone').on('click', function () {
		var phone = $('input[name="phone_number"]').val().trim();
		showPhoneCheckMsg('');
		if (!phone) {
			showPhoneCheckMsg('휴대폰번호를 입력해 주세요.');
			$('input[name="phone_number"]').focus();
			return;
		}
		if (!isValidPhone(phone)) {
			showPhoneCheckMsg('올바른 휴대폰번호 형식이 아닙니다. (예: 010-1234-5678)');
			$('input[name="phone_number"]').focus();
			return;
		}
		var token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		fetch('/member/check-phone', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': token || '',
				'Accept': 'application/json'
			},
			body: JSON.stringify({ phone: phone })
		})
		.then(function (res) { return res.json(); })
		.then(function (result) {
			showPhoneCheckMsg(result.message);
			if (result.available) {
				$('#phone_verified').val('1');
			} else {
				$('#phone_verified').val('0');
				$('input[name="phone_number"]').focus();
			}
		})
		.catch(function () {
			showPhoneCheckMsg('휴대폰번호 중복 확인 중 오류가 발생했습니다.');
		});
	});
	$('input[name="phone_number"]').on('input', function () {
		$('#phone_verified').val('0');
		showPhoneCheckMsg('');
	});

	// 비밀번호 조건 안내 (이메일 가입 시)
	$('input[name="password"]').on('blur', function () {
		var msg = $('#passwordCheckMsg');
		var v = $(this).val().trim();
		if (v.length === 0) {
			msg.hide().text('');
			return;
		}
		if (isValidPassword(v)) {
			msg.hide().text('');
		} else {
			msg.text('비밀번호는 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자로 입력해 주세요.').show();
		}
	});
	$('input[name="password"]').on('input', function () {
		$('#passwordCheckMsg').hide().text('');
	});

	// 우편번호 검색
	$('#btnSearchAddress').on('click', function () {
		if (typeof daum === 'undefined') {
			alert('우편번호 서비스를 불러올 수 없습니다.');
			return;
		}
		new daum.Postcode({
			oncomplete: function (data) {
				$('#address_postcode').val(data.zonecode);
				$('#address_base').val(data.address);
				$('#address_detail').focus();
			}
		}).open();
	});

	// 학교명 직접 입력 시 school_name에 반영
	$('#school_name_direct').on('input', function () {
		var v = $(this).val();
		$('#school_name').val(v);
	});

	// 회원교 검색 팝업: 백오피스 회원교 목록 API 연동
	function loadSchoolList(keyword) {
		var url = '/member/schools' + (keyword ? '?school_name=' + encodeURIComponent(keyword) : '');
		$('#popSchoolList').html('<p class="no_data">조회 중...</p>');
		fetch(url)
			.then(function (res) { return res.json(); })
			.then(function (data) {
				var schools = data.schools || [];
				if (schools.length === 0) {
					$('#popSchoolList').html('<p class="no_data">조회된 회원교가 없습니다.</p>');
					return;
				}
				var html = schools.map(function (s) {
					var name = s.school_name || '';
					return '<label class="check"><input type="radio" name="pop_school_select" value="' + name.replace(/"/g, '&quot;') + '"><i></i><span>' + name.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</span></label>';
				}).join('');
				$('#popSchoolList').html(html);
			})
			.catch(function () {
				$('#popSchoolList').html('<p class="no_data">목록을 불러오는 중 오류가 발생했습니다.</p>');
			});
	}
	$('#popSchoolSearch').on('click', function () {
		loadSchoolList($('#popSchoolKeyword').val().trim());
	});
	$(document).on('searchSchoolOpened', function () {
		loadSchoolList($('#popSchoolKeyword').val().trim());
	});
	$('#popSchoolKeyword').on('keypress', function (e) {
		if (e.which === 13) {
			e.preventDefault();
			$('#popSchoolSearch').click();
		}
	});

	// 회원가입 제출 전 검사: (0) 약관 동의 → (1) 이메일 (2) 비밀번호 (3) 비밀번호 확인 (4) 휴대폰 — 약관 미체크는 제출 막고 같은 자리에서만 안내(스크롤 없음)
	$('#joinForm').on('submit', function () {
		$('#termsCheckMsg').hide().text('');
		$('#schoolNameCheckMsg').hide().text('');
		showEmailCheckMsg('');
		showPasswordCheckMsg('');
		showPasswordConfirmCheckMsg('');
		showPhoneCheckMsg('');

		var $email = $('input[name="email"]');
		var emailVal = $email.val().trim();
		if (!emailVal) {
			showEmailCheckMsg('이메일을 입력해 주세요.');
			scrollToField($email[0]);
			return false;
		}
		if (!isValidEmail(emailVal)) {
			showEmailCheckMsg('유효하지 않은 메일주소입니다.');
			scrollToField($email[0]);
			return false;
		}
		if ($('#email_verified').val() !== '1') {
			showEmailCheckMsg('이메일 중복확인을 해 주세요.');
			scrollToField($email[0]);
			return false;
		}

		var $pwd = $('input[name="password"]');
		if ($pwd.length) {
			if (!$pwd.val().trim()) {
				showPasswordCheckMsg('비밀번호를 입력해 주세요.');
				scrollToField($pwd[0]);
				return false;
			}
			if (!isValidPassword($pwd.val())) {
				showPasswordCheckMsg('비밀번호는 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자로 입력해 주세요.');
				scrollToField($pwd[0]);
				return false;
			}
			var $pwdConf = $('input[name="password_confirmation"]');
			if (!$pwdConf.val().trim() || $pwd.val() !== $pwdConf.val()) {
				showPasswordConfirmCheckMsg('비밀번호와 비밀번호 확인이 일치하지 않습니다.');
				scrollToField($pwdConf[0]);
				return false;
			}
		}

		var $phone = $('input[name="phone_number"]');
		var phoneVal = $phone.val().trim();
		if (phoneVal && !isValidPhone(phoneVal)) {
			showPhoneCheckMsg('올바른 휴대폰번호 형식이 아닙니다. (예: 010-1234-5678)');
			scrollToField($phone[0]);
			return false;
		}
		if ($('#phone_verified').val() !== '1') {
			showPhoneCheckMsg('휴대폰번호 중복확인을 해 주세요.');
			scrollToField($phone[0]);
			return false;
		}

		var $school = $('input[name="school_name"]');
		if (!$school.val().trim()) {
			$('#schoolNameCheckMsg').text('학교명을 입력해 주세요.').show();
			scrollToField($school[0]);
			return false;
		}

		var termsPrivacy = $('input[name="terms_privacy"]').prop('checked');
		var termsService = $('input[name="terms_service"]').prop('checked');
		if (!termsPrivacy || !termsService) {
			$('#termsCheckMsg').text('필수 약관에 동의해 주세요.').show();
			return false;
		}
	});
	$('#popSchoolRegister').on('click', function () {
		var selected = $('input[name="pop_school_select"]:checked');
		if (!selected.length) {
			alert('학교를 선택해 주세요.');
			return;
		}
		var schoolName = selected.val();
		$('#school_name').val(schoolName);
		$('.input_school').val(schoolName);
		$('#school_name_direct').val('').prop('disabled', true);
		layerHide('searchSchool');
	});
	// 검색으로 선택한 학교를 지우면 직접 입력란 다시 활성화
	$('#school_name').on('input', function () {
		if (!$(this).val().trim()) {
			$('#school_name_direct').prop('disabled', false);
		}
	});

});
