/**
 * 자격증 신청내역 상세 페이지 전용 (성적 저장 등)
 */
(function () {
    function getBasePath() {
        const container = document.querySelector('.certification-applications');
        return container ? (container.getAttribute('data-base-path') || '/backoffice/certification-applications') : '';
    }

    function getProgramId() {
        const container = document.querySelector('.certification-applications');
        return container ? container.getAttribute('data-program-id') : null;
    }

    function batchSaveScores() {
        const programId = getProgramId();
        const basePath = getBasePath();
        if (!programId || !basePath) {
            alert('페이지 정보를 찾을 수 없습니다.');
            return;
        }

        const inputs = document.querySelectorAll('.score-input');
        const scores = {};
        let hasValue = false;
        inputs.forEach(function (input) {
            const id = input.getAttribute('data-application-id');
            if (id) {
                const val = input.value.trim();
                scores[id] = val === '' ? null : parseInt(val, 10);
                if (val !== '') {
                    hasValue = true;
                }
            }
        });

        if (!hasValue) {
            alert('저장할 성적을 입력해주세요.');
            return;
        }

        const container = document.querySelector('.certification-applications');
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = meta ? meta.getAttribute('content') : (container ? container.getAttribute('data-csrf-token') || '' : '');
        const url = basePath + '/' + programId + '/scores';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ scores: scores })
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (result.ok && result.data && result.data.success) {
                    alert(result.data.message || '성적이 저장되었습니다.');
                    window.location.reload();
                } else {
                    alert(result.data && result.data.message ? result.data.message : '저장 중 오류가 발생했습니다.');
                }
            })
            .catch(function (err) {
                console.error('성적 저장 실패:', err);
                alert('저장 중 오류가 발생했습니다.');
            });
    }

    function initBatchSaveScores() {
        const container = document.querySelector('.certification-applications');
        if (!container) return;

        const btn = container.querySelector('[data-action="batch-save-scores"]');
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                batchSaveScores();
            });
        }
    }

    function getPrintBaseUrl() {
        const container = document.querySelector('.certification-applications');
        return container ? (container.getAttribute('data-print-base-url') || '/backoffice/print') : '/backoffice/print';
    }

    function initIssueDocuments() {
        const container = document.querySelector('.certification-applications');
        if (!container) return;

        const printBase = getPrintBaseUrl();
        const actions = {
            'issue-exam-ticket': 'admission_ticket',
            'issue-pass-confirmation': 'certificate_qualification',
            'issue-certificate': 'qualification_certificate',
            'issue-receipt': 'receipt'
        };

        container.addEventListener('click', function (e) {
            for (var dataAction in actions) {
                var btn = e.target.closest('[data-action="' + dataAction + '"]');
                if (btn) {
                    e.preventDefault();
                    var applicationId = btn.getAttribute('data-application-id');
                    if (!applicationId) return;
                    var path = actions[dataAction];
                    var url = printBase + '/' + path + '/' + applicationId;
                    window.open(url, '_blank');
                    return;
                }
            }
        });
    }

    function init() {
        initBatchSaveScores();
        initIssueDocuments();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
