/**
 * 마이페이지 회원정보 수정
 * - layerShow/layerHide, Daum 우편번호, 학교 검색, 회원 탈퇴 팝업
 */
(function () {
	'use strict';

	window.layerShow = function (id) {
		$('#' + id).fadeIn(300);
		if (id === 'searchSchool') {
			$(document).trigger('searchSchoolOpened');
		}
	};
	window.layerHide = function (id) {
		$('#' + id).fadeOut(300);
	};

	function showCheckMsg(id, msg) {
		var $el = $('#' + id);
		if (!$el.length) return;
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
	function isValidPassword(pw) {
		if (!pw || pw.length < 8 || pw.length > 10) return false;
		var hasLetter = /[a-zA-Z]/.test(pw);
		var hasDigit = /[0-9]/.test(pw);
		var hasSpecial = /[^a-zA-Z0-9]/.test(pw);
		return (hasLetter ? 1 : 0) + (hasDigit ? 1 : 0) + (hasSpecial ? 1 : 0) >= 2;
	}

	$(function () {
		if (document.querySelector('main[data-join-errors]')) {
			function scrollToFirstError() {
				var errs = document.querySelectorAll('#mypageMemberForm .join_field_error');
				var firstErr = null;
				for (var i = 0; i < errs.length; i++) {
					var el = errs[i];
					if (el.offsetParent !== null && el.closest('dl')) {
						firstErr = el;
						break;
					}
				}
				if (!firstErr) firstErr = document.querySelector('#mypageMemberForm .join_field_error');
				if (firstErr) {
					var dl = firstErr.closest('dl');
					var input = dl ? dl.querySelector('input:not([type="hidden"]):not([readonly])') : null;
					if (!input) input = document.querySelector('#mypageMemberForm input:not([type="hidden"]):not([readonly])');
					var scrollTarget = (input && input.offsetParent) ? input : (dl || firstErr);
					scrollTarget.style.scrollMarginTop = '220px';
					scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
					setTimeout(function () {
						scrollTarget.style.scrollMarginTop = '';
						if (input) input.focus();
					}, 500);
				}
			}
			setTimeout(scrollToFirstError, 150);
		}

		$('#btnSecession').on('click', function () {
			layerShow('secession');
		});

		$(document).on('click', '[data-action="layer-close"]', function () {
			var layer = $(this).data('layer');
			if (layer) layerHide(layer);
		});

		if ($('main[data-secession-errors="1"]').length) {
			layerShow('secession');
		}

		var successMsg = document.querySelector('main[data-success-message]') && document.querySelector('main').getAttribute('data-success-message');
		if (successMsg) {
			alert(successMsg);
			document.querySelector('main').removeAttribute('data-success-message');
		}

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

		$('#btnSearchSchool').on('click', function () {
			layerShow('searchSchool');
		});

		$('#school_name_direct').on('input', function () {
			var v = $(this).val();
			$('#school_name').val(v);
		});

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
		$('#school_name').on('input', function () {
			if (!$(this).val().trim()) {
				$('#school_name_direct').prop('disabled', false);
			}
		});

		// 회원정보 저장: 폼 위에서부터 순서대로 검증, 실패 시 빨간 메시지 + 스크롤
		$('#mypageMemberForm').on('submit', function () {
			showCheckMsg('currentPasswordCheckMsg', '');
			showCheckMsg('passwordCheckMsg', '');
			showCheckMsg('passwordConfirmCheckMsg', '');
			showCheckMsg('schoolNameCheckMsg', '');

			var $pwd = $('input[name="password"]');
			var pwdVal = $pwd.val().trim();
			if (pwdVal) {
				var $current = $('input[name="current_password"]');
				if (!$current.val().trim()) {
					showCheckMsg('currentPasswordCheckMsg', '비밀번호를 변경하려면 현재 비밀번호를 입력해 주세요.');
					scrollToField($current[0]);
					return false;
				}
				if (!isValidPassword(pwdVal)) {
					showCheckMsg('passwordCheckMsg', '비밀번호는 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자로 입력해 주세요.');
					scrollToField($pwd[0]);
					return false;
				}
				var $pwdConf = $('input[name="password_confirmation"]');
				if (!$pwdConf.val().trim() || pwdVal !== $pwdConf.val()) {
					showCheckMsg('passwordConfirmCheckMsg', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
					scrollToField($pwdConf[0]);
					return false;
				}
			}

			var $school = $('input[name="school_name"]');
			if (!$school.val().trim()) {
				showCheckMsg('schoolNameCheckMsg', '소속기관(학교명)을 입력해 주세요.');
				scrollToField($school[0]);
				return false;
			}
		});
	});
})();
