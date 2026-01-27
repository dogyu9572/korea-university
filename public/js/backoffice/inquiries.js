/**
 * 문의 관리 페이지 JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // jQuery 로드 확인
    function waitForJQuery() {
        if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
            initPage();
        } else {
            setTimeout(waitForJQuery, 50);
        }
    }
    
    waitForJQuery();
    
    // 바닐라 JS 이벤트 핸들러
    initVanillaEventHandlers();
});

// 페이지 초기화
function initPage() {
    if (typeof $ === 'undefined') {
        return;
    }
    
    initEventHandlers();
}

// 이벤트 핸들러 등록
function initEventHandlers() {
    if (typeof $ === 'undefined') {
        return;
    }

    // 전체 선택 체크박스
    $('#select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.inquiry-checkbox').prop('checked', isChecked);
    });

    // 개별 체크박스 변경 시 전체 선택 체크박스 상태 업데이트
    $(document).on('change', '.inquiry-checkbox', function() {
        const totalCheckboxes = $('.inquiry-checkbox').length;
        const checkedCheckboxes = $('.inquiry-checkbox:checked').length;
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // 선택삭제 버튼
    $('#btnDeleteMultiple').on('click', function() {
        const selectedIds = $('.inquiry-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('삭제할 문의를 선택해주세요.');
            return;
        }

        if (!confirm('선택한 ' + selectedIds.length + '개의 문의를 삭제하시겠습니까?')) {
            return;
        }

            // AJAX로 일괄 삭제
        $.ajax({
            url: '/backoffice/inquiries/delete-multiple',
            method: 'POST',
            data: {
                ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message || '삭제에 실패했습니다.');
                }
            },
            error: function(xhr) {
                alert('삭제 중 오류가 발생했습니다.');
                console.error(xhr);
            }
        });
    });
}

// 바닐라 JS 이벤트 핸들러
function initVanillaEventHandlers() {
    const selectAllCheckbox = document.getElementById('select-all');
    const inquiryCheckboxes = document.querySelectorAll('.inquiry-checkbox');
    const deleteMultipleBtn = document.getElementById('btnDeleteMultiple');

    // 전체 선택 체크박스
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            inquiryCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // 개별 체크박스 변경 시 전체 선택 체크박스 상태 업데이트
    inquiryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (selectAllCheckbox) {
                const totalCheckboxes = inquiryCheckboxes.length;
                const checkedCheckboxes = document.querySelectorAll('.inquiry-checkbox:checked').length;
                selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
            }
        });
    });

    // 선택삭제 버튼
    if (deleteMultipleBtn) {
        deleteMultipleBtn.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.inquiry-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('삭제할 문의를 선택해주세요.');
                return;
            }

            if (!confirm('선택한 ' + selectedIds.length + '개의 문의를 삭제하시겠습니까?')) {
                return;
            }

            // CSRF 토큰 가져오기
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('CSRF 토큰을 찾을 수 없습니다.');
                return;
            }

            // Fetch API로 일괄 삭제
            fetch('/backoffice/inquiries/delete-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || '삭제에 실패했습니다.');
                }
            })
            .catch(error => {
                alert('삭제 중 오류가 발생했습니다.');
                console.error(error);
            });
        });
    }
}
