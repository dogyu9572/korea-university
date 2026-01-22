/**
 * 조직도 관리 페이지 JavaScript
 */

// Summernote 초기화 플래그
let summernoteInitialized = false;

// Summernote 에디터 초기화
function initSummernote() {
    console.log('[DEBUG] initSummernote 함수 호출됨');
    
    // 이미 초기화되었으면 중복 초기화 방지
    if (summernoteInitialized) {
        console.log('[DEBUG] Summernote가 이미 초기화되어 있습니다. 중복 초기화 방지.');
        return;
    }
    
    // jQuery와 Summernote 로드 확인
    if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
        console.error('[DEBUG] jQuery 또는 Summernote가 로드되지 않았습니다!');
        console.log('[DEBUG] jQuery 존재:', typeof $ !== 'undefined');
        console.log('[DEBUG] Summernote 존재:', typeof $.fn.summernote !== 'undefined');
        return;
    }
    
    const $editor = $('#chartContent');
    if ($editor.length === 0) {
        console.error('[DEBUG] chartContent 요소를 찾을 수 없습니다.');
        return;
    }
    
    // 기존 Summernote 인스턴스가 있으면 제거
    try {
        if ($editor.summernote('code') !== null) {
            console.log('[DEBUG] 기존 Summernote 인스턴스 제거 중...');
            $editor.summernote('destroy');
            // DOM 업데이트를 위한 짧은 대기 시간
            setTimeout(function() {
                initializeSummernoteEditor($editor);
            }, 50);
        } else {
            initializeSummernoteEditor($editor);
        }
    } catch (e) {
        // destroy 실패는 무시 (인스턴스가 없을 수 있음)
        console.log('[DEBUG] 기존 인스턴스 제거 시도 (없을 수 있음)');
        initializeSummernoteEditor($editor);
    }
}

// Summernote 에디터 실제 초기화 함수
function initializeSummernoteEditor($editor) {
    try {
        console.log('[DEBUG] Summernote 초기화 시작');
        $editor.summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0], this);
                },
                onInit: function() {
                    console.log('[DEBUG] Summernote 초기화 완료');
                    summernoteInitialized = true;
                    
                    // 드롭다운 이벤트 강제 활성화 및 깜빡임 문제 해결
                    setTimeout(function() {
                        // 모든 드롭다운 버튼에 대해 이벤트 재설정
                        $('.note-btn-group .dropdown-toggle').off('click.dropdownFix').on('click.dropdownFix', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // 현재 드롭다운 상태 확인
                            const $this = $(this);
                            const $dropdown = $this.next('.note-dropdown-menu');
                            
                            // 다른 드롭다운 닫기
                            $('.note-dropdown-menu').not($dropdown).hide();
                            
                            // 현재 드롭다운 토글
                            if ($dropdown.is(':visible')) {
                                $dropdown.hide();
                            } else {
                                $dropdown.show();
                            }
                        });
                        
                        // 드롭다운 외부 클릭 시 닫기
                        $(document).off('click.dropdownFix').on('click.dropdownFix', function(e) {
                            if (!$(e.target).closest('.note-btn-group').length) {
                                $('.note-dropdown-menu').hide();
                            }
                        });
                        
                        // 드롭다운 메뉴 항목 클릭 시 메뉴 닫기
                        $('.note-dropdown-menu .note-btn').off('click').on('click', function() {
                            $(this).closest('.note-dropdown-menu').hide();
                        });
                    }, 500);
                }
            }
        });
        console.log('[DEBUG] Summernote 초기화 성공');
    } catch (e) {
        console.error('[DEBUG] Summernote 초기화 실패:', e);
        summernoteInitialized = false;
    }
}

// 이미지 업로드 함수
function uploadImage(file, editor) {
    const formData = new FormData();
    formData.append('image', file);

    fetch('/backoffice/upload-image', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.uploaded) {
            $(editor).summernote('insertImage', result.url);
        } else {
            alert('이미지 업로드에 실패했습니다.');
        }
    })
    .catch(error => {
        alert('이미지 업로드 중 오류가 발생했습니다.');
    });
}

// 새 구성원 행 추가
function addNewMemberRow() {
    const tbody = $('#membersTableBody');
    
    if (tbody.length === 0) {
        return;
    }
    
    // 빈 행 메시지 제거
    tbody.find('.empty-row').remove();
    
    // 새 행은 처음부터 편집 가능한 상태로 추가 (disabled 없음)
    const newRow = `
        <tr data-id="new" class="new-member-row">
            <td>
                <select class="board-form-control form-control-sm member-category" name="category">
                    <option value="">선택하세요</option>
                    <option value="회장">회장</option>
                    <option value="부회장">부회장</option>
                    <option value="사무국">사무국</option>
                    <option value="지회">지회</option>
                    <option value="감사">감사</option>
                    <option value="고문">고문</option>
                </select>
            </td>
            <td>
                <input type="text" class="board-form-control form-control-sm member-name" name="name" value="">
            </td>
            <td>
                <input type="text" class="board-form-control form-control-sm member-position" name="position" value="">
            </td>
            <td>
                <input type="text" class="board-form-control form-control-sm member-affiliation" name="affiliation" value="">
            </td>
            <td>
                <input type="text" class="board-form-control form-control-sm member-phone" name="phone" value="">
            </td>
            <td>
                <input type="number" class="board-form-control form-control-sm member-sort-order" name="sort_order" value="0" min="0">
            </td>
            <td>
                <div class="board-btn-group btn-save-cancel-group">
                    <button type="button" class="btn btn-success btn-sm btn-save-new-member">
                        <i class="fas fa-check"></i> 저장
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm btn-cancel-new-member">
                        <i class="fas fa-times"></i> 취소
                    </button>
                </div>
            </td>
        </tr>
    `;
    
    tbody.prepend(newRow);
    
    // 새 행으로 스크롤
    const firstRow = tbody.find('tr:first');
    if (firstRow.length > 0 && firstRow.offset()) {
        $('html, body').animate({
            scrollTop: firstRow.offset().top - 100
        }, 300);
    }
}


// 구성원 수정
function updateMember(memberId) {
    const row = $(`tr[data-id="${memberId}"]`);
    
    if (row.length === 0) {
        showErrorMessage('구성원 정보를 찾을 수 없습니다.');
        return;
    }
    
    const data = {
        category: row.find('.member-category').val(),
        name: row.find('.member-name').val(),
        position: row.find('.member-position').val(),
        affiliation: row.find('.member-affiliation').val(),
        phone: row.find('.member-phone').val(),
        sort_order: row.find('.member-sort-order').val() || 0,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    // 필수 필드 검증
    if (!data.category || data.category.trim() === '') {
        alert('분류를 선택해주세요.');
        row.find('.member-category').focus();
        return;
    }
    
    if (!data.name || data.name.trim() === '') {
        alert('이름을 입력해주세요.');
        row.find('.member-name').focus();
        return;
    }

    fetch(`/backoffice/organizational-members/${memberId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showSuccessMessage('구성원 정보가 수정되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
            return;
        }
        return response.json().then(err => {
            throw new Error(err.message || '수정에 실패했습니다.');
        }).catch(() => {
            throw new Error('수정에 실패했습니다.');
        });
    })
    .catch(error => {
        showErrorMessage(error.message || '수정 중 오류가 발생했습니다.');
    });
}

// 새 구성원 생성
function createMember(row) {
    if (!row || row.length === 0) {
        showErrorMessage('구성원 정보를 찾을 수 없습니다.');
        return;
    }
    
    // 각 필드 값 추출
    const categorySelect = row.find('.member-category');
    const nameInput = row.find('.member-name');
    
    // select 요소 직접 확인
    const categorySelectElement = categorySelect[0];
    const categoryValue = categorySelectElement ? categorySelectElement.value : '';
    
    const data = {
        category: categoryValue || categorySelect.val() || '',
        name: nameInput.val() || '',
        position: row.find('.member-position').val() || '',
        affiliation: row.find('.member-affiliation').val() || '',
        phone: row.find('.member-phone').val() || '',
        sort_order: parseInt(row.find('.member-sort-order').val()) || 0,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    // 필수 필드 검증
    if (!data.category || data.category.trim() === '') {
        alert('분류를 선택해주세요.');
        if (categorySelectElement) {
            categorySelectElement.focus();
        }
        return;
    }
    
    if (!data.name || data.name.trim() === '') {
        alert('이름을 입력해주세요.');
        nameInput.focus();
        return;
    }

    fetch('/backoffice/organizational-members', {
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
        // 성공 응답 (200, 201, 302 등)
        if (response.ok || response.status === 302 || response.redirected) {
            showSuccessMessage('구성원이 추가되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
            return;
        }
        
        // JSON 응답 시도
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(result => {
                showSuccessMessage('구성원이 추가되었습니다.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
                return result;
            }).catch(() => {
                showSuccessMessage('구성원이 추가되었습니다.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        }
        
        // HTML 응답인 경우 (리다이렉트)
        showSuccessMessage('구성원이 추가되었습니다.');
        setTimeout(() => {
            location.reload();
        }, 1000);
    })
    .catch(error => {
        showErrorMessage(error.message || '추가 중 오류가 발생했습니다.');
    });
}

// 구성원 삭제
function deleteMember(memberId) {
    if (!confirm('정말로 삭제하시겠습니까?')) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/backoffice/organizational-members/${memberId}`, {
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
        if (response.ok || response.redirected) {
            location.reload();
            return;
        }
        return response.json().catch(() => ({ success: true }));
    })
    .then(result => {
        if (result) {
            showSuccessMessage('구성원이 삭제되었습니다.');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => {
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

// 페이지 초기화
function initPage() {
    console.log('[DEBUG] initPage 함수 호출됨');
    
    // Summernote 초기화
    console.log('[DEBUG] Summernote 초기화 시작');
    initSummernote();
    
    // 이벤트 핸들러 등록
    console.log('[DEBUG] 이벤트 핸들러 등록 시작');
    initEventHandlers();
    console.log('[DEBUG] 이벤트 핸들러 등록 완료');
}

// 이벤트 핸들러 등록
function initEventHandlers() {
    console.log('[DEBUG] initEventHandlers 함수 호출됨');
    
    if (typeof $ === 'undefined') {
        console.error('[DEBUG] jQuery가 로드되지 않았습니다.');
        return;
    }

    // 구성원 추가 버튼
    $(document).on('click', '#btnAddMember', function(e) {
        e.preventDefault();
        addNewMemberRow();
    });

    // 새 구성원 저장 버튼
    $(document).on('click', '.btn-save-new-member', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = $(this).closest('tr');
        if (row.length === 0) {
            showErrorMessage('행을 찾을 수 없습니다.');
            return;
        }
        createMember(row);
    });

    // 새 구성원 취소 버튼
    $(document).on('click', '.btn-cancel-new-member', function() {
        const row = $(this).closest('tr');
        row.remove();
        
        // 행이 없으면 빈 행 메시지 표시
        if ($('#membersTableBody tr').length === 0) {
            $('#membersTableBody').html('<tr class="empty-row"><td colspan="7" class="text-center">등록된 구성원이 없습니다.</td></tr>');
        }
    });

    // 구성원 저장 버튼 (수정 버튼 역할)
    $(document).on('click', '.btn-save-member', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const memberId = $(this).data('id');
        if (!memberId) {
            alert('구성원 ID를 찾을 수 없습니다.');
            return;
        }
        updateMember(memberId);
    });

    // 구성원 삭제 버튼
    $(document).on('click', '.btn-delete-member', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const memberId = $(this).data('id');
        if (!memberId) {
            alert('구성원 ID를 찾을 수 없습니다.');
            return;
        }
        deleteMember(memberId);
    });

    // 조직도 폼 제출 전 Summernote 내용 동기화
    $(document).on('submit', '#chartForm', function(e) {
        console.log('[DEBUG] 조직도 폼 submit 이벤트 발생');
        e.preventDefault();
        
        // Summernote 내용을 textarea에 동기화
        if (typeof $('#chartContent').summernote === 'function') {
            console.log('[DEBUG] Summernote 내용 동기화 중...');
            const content = $('#chartContent').summernote('code');
            $('#chartContent').val(content);
            console.log('[DEBUG] Summernote 내용 동기화 완료, 길이:', content ? content.length : 0);
        } else {
            console.log('[DEBUG] Summernote 함수를 찾을 수 없음');
        }
        
        // 폼 제출
        console.log('[DEBUG] 폼 제출 시도');
        this.submit();
    });
    
    // 조직도 저장 버튼 클릭
    $(document).on('click', '#btnSaveChart', function(e) {
        console.log('[DEBUG] 조직도 저장 버튼 클릭 (jQuery 이벤트)');
        console.log('[DEBUG] 버튼 요소:', this);
        e.preventDefault();
        e.stopPropagation();
        
        const form = document.getElementById('chartForm');
        console.log('[DEBUG] 찾은 폼:', form);
        if (!form) {
            console.error('[DEBUG] 폼을 찾을 수 없습니다.');
            alert('폼을 찾을 수 없습니다.');
            return;
        }
        
        // Summernote 내용을 textarea에 동기화
        if (typeof $('#chartContent').summernote === 'function') {
            console.log('[DEBUG] Summernote 내용 동기화 중...');
            const content = $('#chartContent').summernote('code');
            $('#chartContent').val(content);
            console.log('[DEBUG] Summernote 내용 동기화 완료, 길이:', content ? content.length : 0);
        } else {
            console.log('[DEBUG] Summernote 함수를 찾을 수 없음');
        }
        
        // 폼 제출
        console.log('[DEBUG] 폼 제출 시도');
        form.submit();
    });
}

// DOMContentLoaded 이벤트로 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('[DEBUG] DOMContentLoaded 이벤트 발생');
    
    // jQuery와 Summernote 로드 확인
    function waitForDependencies() {
        if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined' && typeof $.fn.summernote !== 'undefined') {
            console.log('[DEBUG] 모든 의존성 로드 확인됨');
            console.log('[DEBUG] jQuery 존재:', typeof $ !== 'undefined');
            console.log('[DEBUG] Summernote 존재:', typeof $.fn.summernote !== 'undefined');
            
            // jQuery ready 이벤트로 초기화
            $(document).ready(function() {
                console.log('[DEBUG] jQuery ready 이벤트 발생');
                initPage();
            });
        } else {
            console.log('[DEBUG] 의존성 대기 중... (jQuery:', typeof $ !== 'undefined', ', Summernote:', typeof $.fn !== 'undefined' && typeof $.fn.summernote !== 'undefined', ')');
            setTimeout(waitForDependencies, 50);
        }
    }
    
    waitForDependencies();
    
    // 바닐라 JS 이벤트 핸들러 (안전장치)
    const btnSaveChart = document.getElementById('btnSaveChart');
    if (btnSaveChart) {
        console.log('[DEBUG] 조직도 저장 버튼 요소 찾음 (바닐라 JS)');
        btnSaveChart.addEventListener('click', function(e) {
            console.log('[DEBUG] 조직도 저장 버튼 클릭 (바닐라 JS 이벤트)');
            e.preventDefault();
            e.stopPropagation();
            
            const form = document.getElementById('chartForm');
            if (!form) {
                console.error('[DEBUG] 폼을 찾을 수 없습니다.');
                alert('폼을 찾을 수 없습니다.');
                return;
            }
            
            // Summernote 내용을 textarea에 동기화
            if (typeof $ !== 'undefined' && typeof $('#chartContent').summernote === 'function') {
                console.log('[DEBUG] Summernote 내용 동기화 중...');
                const content = $('#chartContent').summernote('code');
                $('#chartContent').val(content);
                console.log('[DEBUG] Summernote 내용 동기화 완료');
            } else {
                console.log('[DEBUG] jQuery 또는 Summernote 함수를 찾을 수 없음');
            }
            
            // 폼 제출
            console.log('[DEBUG] 폼 제출 시도');
            form.submit();
        });
    } else {
        console.error('[DEBUG] 조직도 저장 버튼을 찾을 수 없습니다.');
    }
    
    // 구성원 추가 버튼 (바닐라 JS)
    const btnAddMember = document.getElementById('btnAddMember');
    if (btnAddMember) {
        btnAddMember.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof $ !== 'undefined') {
                addNewMemberRow();
            }
        });
    }
});
