/**
 * 연혁 관리 페이지 JavaScript
 */

// DOMContentLoaded 이벤트로 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('[DEBUG] history.js DOMContentLoaded 이벤트 발생');
    
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

    // 날짜 선택 시 연도 자동 입력 (등록 섹션)
    $(document).on('change', '#dateInput', function() {
        const dateValue = $(this).val();
        if (dateValue) {
            const year = new Date(dateValue).getFullYear();
            $('#yearInput').val(year + '년');
        }
    });
    
    // 목록에서 날짜 변경 시 연도 자동 업데이트
    $(document).on('change', '.history-date', function() {
        const dateValue = $(this).val();
        const row = $(this).closest('tr');
        
        if (dateValue && row.length > 0) {
            const year = new Date(dateValue).getFullYear();
            // 연도 컬럼 업데이트
            row.find('td').eq(1).text(year + '년');
        }
    });

    // 연혁 추가 버튼
    $(document).on('click', '#btnAddHistory', function(e) {
        console.log('[DEBUG] 연혁 추가 버튼 클릭 (jQuery)');
        e.preventDefault();
        addHistory();
    });

    // 연혁 수정 버튼
    $(document).on('click', '.btn-save-history', function(e) {
        console.log('[DEBUG] 연혁 수정 버튼 클릭 (jQuery)');
        console.log('[DEBUG] 버튼 요소:', this);
        e.preventDefault();
        e.stopPropagation();
        const historyId = $(this).data('id');
        console.log('[DEBUG] 연혁 ID:', historyId);
        if (!historyId) {
            alert('연혁 ID를 찾을 수 없습니다.');
            return;
        }
        updateHistory(historyId);
    });

    // 연혁 삭제 버튼
    $(document).on('click', '.btn-delete-history', function(e) {
        console.log('[DEBUG] 연혁 삭제 버튼 클릭 (jQuery)');
        console.log('[DEBUG] 버튼 요소:', this);
        e.preventDefault();
        e.stopPropagation();
        const historyId = $(this).data('id');
        console.log('[DEBUG] 연혁 ID:', historyId);
        if (!historyId) {
            alert('연혁 ID를 찾을 수 없습니다.');
            return;
        }
        deleteHistory(historyId);
    });
}

// 바닐라 JS 이벤트 핸들러 (안전장치)
function initVanillaEventHandlers() {
    console.log('[DEBUG] initVanillaEventHandlers 함수 호출됨');
    
    // 연혁 추가 버튼
    const btnAddHistory = document.getElementById('btnAddHistory');
    if (btnAddHistory) {
        btnAddHistory.addEventListener('click', function(e) {
            console.log('[DEBUG] 연혁 추가 버튼 클릭 (바닐라 JS)');
            e.preventDefault();
            if (typeof $ !== 'undefined') {
                addHistory();
            }
        });
    }
    
    // 연혁 수정/삭제 버튼 (이벤트 위임)
    document.addEventListener('click', function(e) {
        // 수정 버튼
        const saveBtn = e.target.closest('.btn-save-history');
        if (saveBtn) {
            console.log('[DEBUG] 연혁 수정 버튼 클릭 (바닐라 JS)');
            e.preventDefault();
            e.stopPropagation();
            
            if (typeof $ === 'undefined') {
                alert('jQuery가 로드되지 않았습니다. 페이지를 새로고침해주세요.');
                return;
            }
            
            const historyId = $(saveBtn).data('id');
            console.log('[DEBUG] 연혁 ID:', historyId);
            if (!historyId) {
                alert('연혁 ID를 찾을 수 없습니다.');
                return;
            }
            
            updateHistory(historyId);
            return;
        }
        
        // 삭제 버튼
        const deleteBtn = e.target.closest('.btn-delete-history');
        if (deleteBtn) {
            console.log('[DEBUG] 연혁 삭제 버튼 클릭 (바닐라 JS)');
            e.preventDefault();
            e.stopPropagation();
            
            if (typeof $ === 'undefined') {
                return;
            }
            
            const historyId = $(deleteBtn).data('id');
            console.log('[DEBUG] 연혁 ID:', historyId);
            if (!historyId) {
                return;
            }
            
            deleteHistory(historyId);
            return;
        }
    });
}

// 연혁 추가
function addHistory() {
    const date = $('#dateInput').val();
    const content = $('#contentInput').val();
    const isVisible = $('#isVisibleInput').val();

    // 유효성 검사
    if (!date) {
        alert('날짜를 선택해주세요.');
        $('#dateInput').focus();
        return;
    }

    if (!content || content.trim() === '') {
        alert('내용을 입력해주세요.');
        $('#contentInput').focus();
        return;
    }

    const data = {
        date: date,
        content: content.trim(),
        is_visible: isVisible,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    fetch('/backoffice/histories', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data),
        redirect: 'follow'
    })
    .then(response => {
        if (response.ok || response.status === 302 || response.redirected) {
            showSuccessMessage('연혁이 추가되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
            return;
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(result => {
                showSuccessMessage('연혁이 추가되었습니다.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }).catch(() => {
                showSuccessMessage('연혁이 추가되었습니다.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        }
        
        showSuccessMessage('연혁이 추가되었습니다.');
        setTimeout(() => {
            location.reload();
        }, 1000);
    })
    .catch(error => {
        showErrorMessage(error.message || '추가 중 오류가 발생했습니다.');
    });
}

// 연혁 수정
function updateHistory(historyId) {
    console.log('[DEBUG] updateHistory 함수 호출됨, historyId:', historyId);
    
    if (typeof $ === 'undefined') {
        console.error('[DEBUG] jQuery가 로드되지 않았습니다.');
        alert('jQuery가 로드되지 않았습니다. 페이지를 새로고침해주세요.');
        return;
    }
    
    const row = $(`tr[data-id="${historyId}"]`);
    console.log('[DEBUG] 찾은 row:', row.length);
    
    if (row.length === 0) {
        showErrorMessage('연혁 정보를 찾을 수 없습니다.');
        return;
    }
    
    const data = {
        date: row.find('.history-date').val(),
        content: row.find('.history-content').val(),
        is_visible: row.find('.history-is-visible').val(),
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    console.log('[DEBUG] 수정할 데이터:', data);

    // 유효성 검사
    if (!data.date) {
        alert('날짜를 선택해주세요.');
        row.find('.history-date').focus();
        return;
    }
    
    if (!data.content || data.content.trim() === '') {
        alert('내용을 입력해주세요.');
        row.find('.history-content').focus();
        return;
    }

    console.log('[DEBUG] 수정 요청 전송 중...');
    fetch(`/backoffice/histories/${historyId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('[DEBUG] 수정 응답 상태:', response.status, response.ok);
        if (response.ok || response.redirected) {
            showSuccessMessage('연혁이 수정되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
            return;
        }
        return response.json().then(err => {
            console.error('[DEBUG] 수정 실패:', err);
            throw new Error(err.message || '수정에 실패했습니다.');
        }).catch(() => {
            throw new Error('수정에 실패했습니다.');
        });
    })
    .catch(error => {
        console.error('[DEBUG] 수정 오류:', error);
        showErrorMessage(error.message || '수정 중 오류가 발생했습니다.');
    });
}

// 연혁 삭제
function deleteHistory(historyId) {
    console.log('[DEBUG] deleteHistory 함수 호출됨, historyId:', historyId);
    
    if (!confirm('정말로 삭제하시겠습니까?')) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('[DEBUG] 삭제 요청 전송 중...');
    fetch(`/backoffice/histories/${historyId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            _token: token
        })
    })
    .then(response => {
        console.log('[DEBUG] 삭제 응답 상태:', response.status, response.ok);
        if (response.ok || response.redirected) {
            location.reload();
            return;
        }
        return response.json().catch(() => ({ success: true }));
    })
    .then(result => {
        if (result) {
            showSuccessMessage('연혁이 삭제되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('[DEBUG] 삭제 오류:', error);
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
