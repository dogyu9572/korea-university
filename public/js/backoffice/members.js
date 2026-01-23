/**
 * 회원 관리 페이지 JavaScript
 */

// DOMContentLoaded 이벤트로 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('[DEBUG] members.js DOMContentLoaded 이벤트 발생');
    
    // jQuery 로드 확인
    function waitForJQuery() {
        if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
            console.log('[DEBUG] jQuery 로드 확인됨');
            initPage();
        } else {
            console.log('[DEBUG] jQuery 대기 중...');
            setTimeout(waitForJQuery, 50);
        }
    }
    
    waitForJQuery();
    
    // 바닐라 JS 이벤트 핸들러 (안전장치)
    initVanillaEventHandlers();
});

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

    // 우편번호 검색
    $(document).on('click', '#btnSearchAddress', function() {
        new daum.Postcode({
            oncomplete: function(data) {
                $('#address_postcode').val(data.zonecode);
                $('#address_base').val(data.address);
                $('#address_detail').focus();
            }
        }).open();
    });

    // 학교명 검색
    $(document).on('click', '#btnSearchSchool', function() {
        if (typeof layerShow === 'function') {
            layerShow('searchSchool');
        }
    });

    // 학교명 직접 입력
    $(document).on('input', '#school_name_direct', function() {
        const value = $(this).val();
        if (value) {
            $('#school_name').val(value);
        }
    });

    // 학교 검색 팝업에서 선택 (기존 로직 활용)
    if (typeof $ !== 'undefined') {
        $(document).on('click', '#searchSchool .search_list input[type="checkbox"]', function() {
            const schoolName = $(this).siblings('span').text().trim();
            $('#school_name').val(schoolName);
            $('#school_name_direct').val('');
        });
    }

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
    
    // 우편번호 검색
    const btnSearchAddress = document.getElementById('btnSearchAddress');
    if (btnSearchAddress && typeof daum !== 'undefined') {
        btnSearchAddress.addEventListener('click', function() {
            new daum.Postcode({
                oncomplete: function(data) {
                    document.getElementById('address_postcode').value = data.zonecode;
                    document.getElementById('address_base').value = data.address;
                    document.getElementById('address_detail').focus();
                }
            }).open();
        });
    }
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
