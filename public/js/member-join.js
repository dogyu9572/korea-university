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
	// 유효성 검사 실패 시 첫 번째 에러 항목으로 스크롤
	if (document.querySelector('[data-join-errors]')) {
		var firstErr = document.querySelector('.join_field_error');
		if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
	$('#btnCheckEmail').on('click', function () {
		var email = $('input[name="email"]').val().trim();
		showEmailCheckMsg('');
		if (!email) {
			showEmailCheckMsg('이메일을 입력해 주세요.');
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
	$('#btnCheckPhone').on('click', function () {
		var phone = $('input[name="phone_number"]').val().trim();
		showPhoneCheckMsg('');
		if (!phone) {
			showPhoneCheckMsg('휴대폰번호를 입력해 주세요.');
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

	// 회원가입 제출 전 중복확인 여부 체크 (클라이언트)
	$('#joinForm').on('submit', function () {
		if ($('#email_verified').val() !== '1') {
			alert('이메일 중복확인을 해 주세요.');
			$('input[name="email"]').focus();
			return false;
		}
		if ($('#phone_verified').val() !== '1') {
			alert('휴대폰번호 중복확인을 해 주세요.');
			$('input[name="phone_number"]').focus();
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
