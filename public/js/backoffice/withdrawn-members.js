/**
 * 탈퇴회원 목록 페이지 전용 스크립트
 * - 선택삭제, 개별 삭제, 전체 선택 체크박스
 */

(function () {
    'use strict';

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function setupSelectAll() {
        var selectAll = document.getElementById('select-all');
        var checkboxes = document.querySelectorAll('.member-checkbox');
        if (!selectAll || !checkboxes.length) return;

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (cb) {
                cb.checked = selectAll.checked;
            });
        });

        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {
                var total = checkboxes.length;
                var checked = document.querySelectorAll('.member-checkbox:checked').length;
                selectAll.checked = total > 0 && total === checked;
            });
        });
    }

    function setupBulkDelete() {
        var btn = document.getElementById('btnForceDeleteMultiple');
        if (!btn) return;

        btn.addEventListener('click', function () {
            var checked = document.querySelectorAll('.member-checkbox:checked');
            var selectedIds = Array.prototype.map.call(checked, function (el) { return el.value; });

            if (selectedIds.length === 0) {
                alert('삭제할 회원을 선택해주세요.');
                return;
            }
            if (!confirm('선택한 ' + selectedIds.length + '명의 회원을 영구 삭제하시겠습니까?\n이 작업은 되돌릴 수 없습니다.')) {
                return;
            }

            var token = getCsrfToken();
            fetch('/backoffice/withdrawn/force-delete-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: selectedIds, _token: token })
            })
            .then(function (response) { return response.json(); })
            .then(function (result) {
                if (result.success) {
                    alert(result.message || '선택한 회원이 영구 삭제되었습니다.');
                    location.reload();
                } else {
                    alert(result.message || '삭제 중 오류가 발생했습니다.');
                }
            })
            .catch(function (err) {
                console.error('일괄 삭제 오류:', err);
                alert('삭제 중 오류가 발생했습니다.');
            });
        });
    }

    function setupSingleDelete() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-force-delete-member');
            if (!btn) return;

            e.preventDefault();
            var memberId = btn.getAttribute('data-id');
            if (!memberId) {
                alert('회원 ID를 찾을 수 없습니다.');
                return;
            }
            if (!confirm('정말로 이 회원을 영구 삭제하시겠습니까?\n이 작업은 되돌릴 수 없습니다.')) {
                return;
            }

            var token = getCsrfToken();
            fetch('/backoffice/withdrawn/' + memberId + '/force-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _token: token })
            })
            .then(function (response) {
                if (response.ok || response.redirected) {
                    alert('회원이 영구 삭제되었습니다.');
                    location.reload();
                    return null;
                }
                return response.json().catch(function () { return { success: false }; });
            })
            .then(function (result) {
                if (result && !result.success) {
                    alert(result.message || '삭제 중 오류가 발생했습니다.');
                }
            })
            .catch(function (err) {
                console.error('삭제 오류:', err);
                alert('삭제 중 오류가 발생했습니다.');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setupSelectAll();
            setupBulkDelete();
            setupSingleDelete();
        });
    } else {
        setupSelectAll();
        setupBulkDelete();
        setupSingleDelete();
    }
})();
