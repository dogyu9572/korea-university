/**
 * 회원 관리 페이지 JavaScript
 */

/** 회원교 검색: 세미나 신청 등과 동일한 Bootstrap #schoolSearchModal (한 번만 바인딩) */
var membersSchoolPopupBound = false;

function escapeHtmlSchoolName(str) {
    if (str == null) {
        return '';
    }
    var el = document.createElement('textarea');
    el.textContent = String(str);
    return el.innerHTML;
}

function escapeAttrSchoolName(str) {
    return String(str == null ? '' : str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function applySelectedSchoolName(schoolName) {
    var normalized = String(schoolName == null ? '' : schoolName).trim();
    if (!normalized) {
        return;
    }
    var $ = jQuery;
    $('#school_name, input[name="school_name"]').val(normalized).trigger('input').trigger('change');
    $('.input_school').val(normalized);
    $('#school_name_direct').val('').prop('disabled', true);
}

// 테이블 행 버튼에서 직접 호출하는 전역 함수 (위임 이벤트/캐시 꼬임 회피)
window.selectSchoolFromModal = function (el) {
    if (!el || typeof jQuery === 'undefined') {
        return;
    }
    var $el = jQuery(el);
    var schoolName = $el.attr('data-school-name') || '';
    applySelectedSchoolName(schoolName);
    jQuery('#schoolSearchModal').modal('hide');
};

function bindSchoolSearchPopupIfPresent() {
    if (membersSchoolPopupBound || typeof jQuery === 'undefined') {
        return;
    }
    var $ = jQuery;
    if (!$('#schoolSearchModal').length || !$('#btnSearchSchool').length) {
        return;
    }
    membersSchoolPopupBound = true;

    function loadSchoolList(keyword) {
        var url = '/member/schools' + (keyword ? '?school_name=' + encodeURIComponent(keyword) : '');
        var $tbody = $('#schoolSearchResults');
        $tbody.html(
            '<tr><td colspan="3" class="text-center">조회 중...</td></tr>'
        );
        fetch(url)
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                var schools = data.schools || [];
                if (schools.length === 0) {
                    $tbody.html(
                        '<tr><td colspan="3" class="text-center">조회된 회원교가 없습니다.</td></tr>'
                    );
                    return;
                }
                var html = schools
                    .map(function (s, index) {
                        var name = s.school_name || '';
                        return (
                            '<tr>' +
                            '<td>' +
                            (index + 1) +
                            '</td>' +
                            '<td>' +
                            escapeHtmlSchoolName(name) +
                            '</td>' +
                            '<td class="text-center">' +
                            '<button type="button" class="btn btn-primary btn-sm btn-select-school" onclick="window.selectSchoolFromModal(this)" data-school-name="' +
                            escapeAttrSchoolName(name) +
                            '">선택</button>' +
                            '</td>' +
                            '</tr>'
                        );
                    })
                    .join('');
                $tbody.html(html);
            })
            .catch(function () {
                $tbody.html(
                    '<tr><td colspan="3" class="text-center text-danger">목록을 불러오는 중 오류가 발생했습니다.</td></tr>'
                );
            });
    }

    $('#schoolSearchModal').on('shown.bs.modal', function () {
        // 모달을 열 때마다 검색/선택 상태 초기화
        $('#popSchoolKeyword').val('');
        $('#schoolSearchResults').html(
            '<tr><td colspan="3" class="text-center">조회 중...</td></tr>'
        );
        loadSchoolList('');
    });

    $(document).on('click', '#popSchoolSearch', function () {
        loadSchoolList($('#popSchoolKeyword').val().trim());
    });
    $(document).on('keypress', '#popSchoolKeyword', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#popSchoolSearch').trigger('click');
        }
    });
    $(document).on('input', '#school_name', function () {
        if (!$(this).val().trim()) {
            $('#school_name_direct').prop('disabled', false);
        }
    });
}

function bootBackofficeMembersScript() {
    function waitForJQuery() {
        if (typeof window.jQuery !== 'undefined') {
            if (typeof window.$ === 'undefined') {
                window.$ = window.jQuery;
            }
            initPage();
        } else {
            setTimeout(waitForJQuery, 50);
        }
    }
    waitForJQuery();
    initVanillaEventHandlers();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootBackofficeMembersScript);
} else {
    bootBackofficeMembersScript();
}

// 페이지 초기화
function initPage() {
    console.log('[DEBUG] initPage 함수 호출됨');
    initEventHandlers();
}

// 이벤트 핸들러 등록
function initEventHandlers() {
    console.log('[DEBUG] initEventHandlers 함수 호출됨');
    
    if (typeof $ === 'undefined') {
        console.error('[DEBUG] jQuery가 로드되지 않았습니다.');
        return;
    }

    bindSchoolSearchPopupIfPresent();

    // edit 페이지인지 확인
    const isEditPage = $('#memberForm').attr('action') && $('#memberForm').attr('action').includes('/update');
    
    // 가입 구분 변경 시 비밀번호 필드 표시/숨김 (등록 페이지만)
    // edit 페이지에서는 항상 표시되므로 create 페이지에서만 실행
    if (!isEditPage) {
        $(document).on('change', 'input[name="join_type"]', function() {
            const joinType = $(this).val();
            if (joinType === 'email') {
                $('#passwordGroup, #passwordConfirmationGroup').show();
                $('#password, #password_confirmation').prop('required', true);
            } else {
                $('#passwordGroup, #passwordConfirmationGroup').hide();
                $('#password, #password_confirmation').prop('required', false).val('');
            }
        });
    }
    
    // edit 페이지에서는 비밀번호 필드 항상 표시 (강제)
    if (isEditPage) {
        $('#passwordGroup, #passwordConfirmationGroup').show().css({
            'display': 'block !important',
            'visibility': 'visible !important'
        });
        
        // 다른 스크립트가 숨기려고 하면 방지
        const passwordGroup = $('#passwordGroup');
        const passwordConfirmationGroup = $('#passwordConfirmationGroup');
        
        if (passwordGroup.length) {
            setInterval(function() {
                if (passwordGroup.is(':hidden') || passwordGroup.css('display') === 'none') {
                    passwordGroup.show().css('display', 'block');
                }
            }, 50);
        }
        
        if (passwordConfirmationGroup.length) {
            setInterval(function() {
                if (passwordConfirmationGroup.is(':hidden') || passwordConfirmationGroup.css('display') === 'none') {
                    passwordConfirmationGroup.show().css('display', 'block');
                }
            }, 50);
        }
    }

    // 이메일 중복 확인
    $(document).on('click', '#btnCheckEmail', function() {
        const email = $('#email').val();
        const excludeId = $(this).data('exclude-id');
        
        if (!email) {
            alert('이메일을 입력해주세요.');
            $('#email').focus();
            return;
        }

        checkDuplicateEmail(email, excludeId);
    });

    // 휴대폰 중복 확인
    $(document).on('click', '#btnCheckPhone', function() {
        const phone = $('#phone_number').val();
        const excludeId = $(this).data('exclude-id');
        
        if (!phone) {
            alert('휴대폰번호를 입력해주세요.');
            $('#phone_number').focus();
            return;
        }

        checkDuplicatePhone(phone, excludeId);
    });

    // 우편번호 검색 (단일 핸들러만 유지 - 중복 시 선택 후 팝업이 다시 뜸)
    $(document).on('click', '#btnSearchAddress', function() {
        if (typeof daum === 'undefined') {
            alert('우편번호 서비스를 불러올 수 없습니다.');
            return;
        }
        new daum.Postcode({
            oncomplete: function(data) {
                $('#address_postcode').val(data.zonecode);
                $('#address_base').val(data.address);
                $('#address_detail').focus();
            }
        }).open();
    });

    // 학교명 검색 (Bootstrap 모달 — 세미나 신청 회원 검색과 동일 패턴)
    $(document).on('click', '#btnSearchSchool', function() {
        bindSchoolSearchPopupIfPresent();
        var $modal = $('#schoolSearchModal');
        if (!$modal.length) {
            return;
        }
        if (typeof $modal.modal !== 'function') {
            alert('모달을 불러올 수 없습니다. 페이지를 새로고침 후 다시 시도해 주세요.');
            return;
        }
        $modal.modal('show');
    });

    // 학교명 직접 입력
    $(document).on('input', '#school_name_direct', function() {
        const value = $(this).val();
        if (value) {
            $('#school_name').val(value);
        }
    });

    // 생년월일 입력 제한 (숫자만, 8자리)
    $(document).on('input', '#birth_date', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value.length > 8) {
            value = value.substring(0, 8);
        }
        $(this).val(value);
    });

    // 페이지당 표시 개수 변경은 form submit으로 처리됨

    // 전체 선택/해제 (공지사항과 동일한 패턴)
    $(document).on('change', '#select-all', function() {
        const checked = $(this).prop('checked');
        $('.member-checkbox').prop('checked', checked);
    });

    // 개별 체크박스 변경 시 전체 선택 상태 업데이트
    $(document).on('change', '.member-checkbox', function() {
        const total = $('.member-checkbox').length;
        const checked = $('.member-checkbox:checked').length;
        $('#select-all').prop('checked', total === checked && total > 0);
    });

    // 선택삭제 (공지사항과 동일한 패턴)
    $(document).on('click', '#btnDeleteMultiple', function() {
        const selectedIds = [];
        $('.member-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('삭제할 회원을 선택해주세요.');
            return;
        }

        if (!confirm(`선택한 ${selectedIds.length}명의 회원을 삭제하시겠습니까?`)) {
            return;
        }

        deleteMembers(selectedIds);
    });

    // 개별 삭제
    $(document).on('click', '.btn-delete-member', function() {
        const memberId = $(this).data('id');
        if (!memberId) {
            alert('회원 ID를 찾을 수 없습니다.');
            return;
        }

        if (!confirm('정말로 삭제하시겠습니까?')) {
            return;
        }

        deleteMember(memberId);
    });

    // 엑셀 다운로드
    $(document).on('click', '#btnExport', function() {
        const form = $('#searchForm');
        const formData = new FormData(form[0]);
        const params = new URLSearchParams(formData);
        params.append('per_page', $('#perPageSelect').val() || '20');
        
        window.location.href = '/backoffice/members/export?' + params.toString();
    });
}

// 바닐라 JS 이벤트 핸들러 (안전장치)
function initVanillaEventHandlers() {
    console.log('[DEBUG] initVanillaEventHandlers 함수 호출됨');
    
    // 이메일 중복 확인
    const btnCheckEmail = document.getElementById('btnCheckEmail');
    if (btnCheckEmail) {
        btnCheckEmail.addEventListener('click', function() {
            if (typeof $ !== 'undefined') {
                const email = $('#email').val();
                const excludeId = $(this).data('exclude-id');
                if (email) {
                    checkDuplicateEmail(email, excludeId);
                }
            }
        });
    }
    
    // 휴대폰 중복 확인
    const btnCheckPhone = document.getElementById('btnCheckPhone');
    if (btnCheckPhone) {
        btnCheckPhone.addEventListener('click', function() {
            if (typeof $ !== 'undefined') {
                const phone = $('#phone_number').val();
                const excludeId = $(this).data('exclude-id');
                if (phone) {
                    checkDuplicatePhone(phone, excludeId);
                }
            }
        });
    }
    
    // 우편번호 검색: initEventHandlers()의 $(document).on('click', '#btnSearchAddress')에서만 처리 (중복 등록 시 팝업이 두 번 뜨므로 여기서는 등록하지 않음)
}

// 이메일 중복 확인
function checkDuplicateEmail(email, excludeId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = { email: email };
    if (excludeId) {
        data.exclude_id = excludeId;
    }

    fetch('/backoffice/members/check-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        const resultDiv = document.getElementById('emailCheckResult');
        if (resultDiv) {
            resultDiv.textContent = result.message;
            resultDiv.className = 'check-result ' + (result.available ? 'success' : 'error');
        }
        if (!result.available) {
            $('#email').focus();
        }
    })
    .catch(error => {
        console.error('이메일 중복 확인 오류:', error);
        alert('이메일 중복 확인 중 오류가 발생했습니다.');
    });
}

// 휴대폰 중복 확인
function checkDuplicatePhone(phone, excludeId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = { phone: phone };
    if (excludeId) {
        data.exclude_id = excludeId;
    }

    fetch('/backoffice/members/check-phone', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        const resultDiv = document.getElementById('phoneCheckResult');
        if (resultDiv) {
            resultDiv.textContent = result.message;
            resultDiv.className = 'check-result ' + (result.available ? 'success' : 'error');
        }
        if (!result.available) {
            $('#phone_number').focus();
        }
    })
    .catch(error => {
        console.error('휴대폰 중복 확인 오류:', error);
        alert('휴대폰 중복 확인 중 오류가 발생했습니다.');
    });
}

// 회원 삭제
function deleteMember(memberId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/backoffice/members/${memberId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: token })
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showSuccessMessage('회원이 삭제되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
            return;
        }
        return response.json().catch(() => ({ success: false }));
    })
    .then(result => {
        if (result && !result.success) {
            showErrorMessage('삭제 중 오류가 발생했습니다.');
        }
    })
    .catch(error => {
        console.error('삭제 오류:', error);
        showErrorMessage('삭제 중 오류가 발생했습니다.');
    });
}

// 회원 일괄 삭제
function deleteMembers(ids) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/backoffice/members/delete-multiple', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: ids, _token: token })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showSuccessMessage(result.message || '선택한 회원이 삭제되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showErrorMessage(result.message || '삭제 중 오류가 발생했습니다.');
        }
    })
    .catch(error => {
        console.error('일괄 삭제 오류:', error);
        showErrorMessage('삭제 중 오류가 발생했습니다.');
    });
}

// 성공 메시지 표시
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success board-hidden-alert';
    alertDiv.textContent = message;
    
    const container = document.querySelector('.board-container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}

// 에러 메시지 표시
function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger board-hidden-alert';
    alertDiv.textContent = message;
    
    const container = document.querySelector('.board-container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}
