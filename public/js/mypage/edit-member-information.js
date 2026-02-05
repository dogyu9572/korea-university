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

	$(function () {
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
	});
})();
