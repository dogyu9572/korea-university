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
    const tbody = document.getElementById('membersTableBody');
    if (!tbody) return;

    tbody.querySelectorAll('.empty-row').forEach(el => el.remove());

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

    tbody.insertAdjacentHTML('afterbegin', newRow);

    const firstRow = tbody.querySelector('tr');
    if (firstRow) {
        firstRow.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}


// 구성원 수정
function updateMember(memberId) {
    const row = document.querySelector(`tr[data-id="${memberId}"]`);
    if (!row) {
        showErrorMessage('구성원 정보를 찾을 수 없습니다.');
        return;
    }

    const getVal = (sel) => (row.querySelector(sel)?.value ?? '').trim();
    const data = {
        category: getVal('.member-category'),
        name: getVal('.member-name'),
        position: getVal('.member-position'),
        affiliation: getVal('.member-affiliation'),
        phone: getVal('.member-phone'),
        sort_order: parseInt(row.querySelector('.member-sort-order')?.value, 10) || 0,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    };

    if (!data.category) {
        alert('분류를 선택해주세요.');
        row.querySelector('.member-category')?.focus();
        return;
    }
    if (!data.name) {
        alert('이름을 입력해주세요.');
        row.querySelector('.member-name')?.focus();
        return;
    }

    fetch(`/backoffice/organizational-members/${memberId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(result => {
                    showSuccessMessage(result.message || '구성원 정보가 수정되었습니다.');
                    setTimeout(() => location.reload(), 500);
                });
            }
            showSuccessMessage('구성원 정보가 수정되었습니다.');
            setTimeout(() => location.reload(), 500);
            return;
        }
        if (response.status === 422) {
            return response.json().then(err => {
                const msg = err.errors ? Object.values(err.errors).flat().join(' ') : (err.message || '입력값을 확인해주세요.');
                throw new Error(msg);
            });
        }
        throw new Error('수정에 실패했습니다.');
    })
    .catch(error => {
        showErrorMessage(error.message || '수정 중 오류가 발생했습니다.');
    });
}

// 새 구성원 생성
function createMember(row) {
    if (!row || !row.querySelector) {
        showErrorMessage('구성원 정보를 찾을 수 없습니다.');
        return;
    }

    const getVal = (sel) => (row.querySelector(sel)?.value ?? '').trim();
    const data = {
        category: getVal('.member-category'),
        name: getVal('.member-name'),
        position: getVal('.member-position'),
        affiliation: getVal('.member-affiliation'),
        phone: getVal('.member-phone'),
        sort_order: parseInt(row.querySelector('.member-sort-order')?.value, 10) || 0,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    };

    if (!data.category) {
        alert('분류를 선택해주세요.');
        row.querySelector('.member-category')?.focus();
        return;
    }
    if (!data.name) {
        alert('이름을 입력해주세요.');
        row.querySelector('.member-name')?.focus();
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
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(result => {
                    showSuccessMessage(result.message || '구성원이 추가되었습니다.');
                    setTimeout(() => location.reload(), 500);
                });
            }
            showSuccessMessage('구성원이 추가되었습니다.');
            setTimeout(() => location.reload(), 500);
            return;
        }
        if (response.status === 422) {
            return response.json().then(err => {
                const msg = err.errors ? Object.values(err.errors).flat().join(' ') : (err.message || '입력값을 확인해주세요.');
                throw new Error(msg);
            });
        }
        throw new Error('추가에 실패했습니다.');
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
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: token })
    })
    .then(response => {
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(result => {
                    showSuccessMessage(result.message || '구성원이 삭제되었습니다.');
                    setTimeout(() => location.reload(), 500);
                });
            }
            showSuccessMessage('구성원이 삭제되었습니다.');
            setTimeout(() => location.reload(), 500);
            return;
        }
        throw new Error('삭제에 실패했습니다.');
    })
    .catch(error => {
        showErrorMessage(error.message || '삭제 중 오류가 발생했습니다.');
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
    // Summernote 초기화
    initSummernote();
    
    // 이벤트 핸들러 등록
    console.log('[DEBUG] 이벤트 핸들러 등록 시작');
    initEventHandlers();
}

// 이벤트 핸들러 등록
function initEventHandlers() {
    function syncSummernoteToTextarea() {
        if (typeof $ !== 'undefined' && typeof $.fn.summernote !== 'undefined') {
            const editor = document.getElementById('chartContent');
            if (editor) editor.value = $(editor).summernote('code');
        }
    }

    document.addEventListener('click', function(e) {
        const target = e.target.closest('#btnAddMember, .btn-save-new-member, .btn-cancel-new-member, .btn-save-member, .btn-delete-member, #btnSaveChart');
        if (!target) return;

        e.preventDefault();
        e.stopPropagation();

        if (target.id === 'btnAddMember') {
            addNewMemberRow();
            return;
        }
        if (target.classList.contains('btn-save-new-member')) {
            const row = target.closest('tr');
            if (row) createMember(row);
            else showErrorMessage('행을 찾을 수 없습니다.');
            return;
        }
        if (target.classList.contains('btn-cancel-new-member')) {
            const row = target.closest('tr');
            if (row) row.remove();
            const tbody = document.getElementById('membersTableBody');
            if (tbody && !tbody.querySelector('tr')) {
                tbody.innerHTML = '<tr class="empty-row"><td colspan="7" class="text-center">등록된 구성원이 없습니다.</td></tr>';
            }
            return;
        }
        if (target.classList.contains('btn-save-member')) {
            const memberId = target.dataset.id;
            if (memberId) updateMember(memberId);
            else alert('구성원 ID를 찾을 수 없습니다.');
            return;
        }
        if (target.classList.contains('btn-delete-member')) {
            const memberId = target.dataset.id;
            if (memberId) deleteMember(memberId);
            else alert('구성원 ID를 찾을 수 없습니다.');
            return;
        }
        if (target.id === 'btnSaveChart') {
            const form = document.getElementById('chartForm');
            if (!form) {
                alert('폼을 찾을 수 없습니다.');
                return;
            }
            syncSummernoteToTextarea();
            form.submit();
        }
    });

    const chartFormEl = document.getElementById('chartForm');
    if (chartFormEl) {
        chartFormEl.addEventListener('submit', function(e) {
            e.preventDefault();
            syncSummernoteToTextarea();
            this.submit();
        });
    }
}

// DOM 준비 여부에 상관없이 초기화 실행
function initOrganizational() {
    function waitForDependencies() {
        if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined' && typeof $.fn.summernote !== 'undefined') {
            initPage();
        } else {
            setTimeout(waitForDependencies, 50);
        }
    }
    waitForDependencies();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOrganizational);
} else {
    initOrganizational();
}
