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

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
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

    function initIssuePlaceholders() {
        const container = document.querySelector('.certification-applications');
        if (!container) return;

        container.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-action="issue-exam-ticket"]');
            if (btn) {
                e.preventDefault();
                alert('수험표 발급 기능은 준비 중입니다.');
                return;
            }
            btn = e.target.closest('[data-action="issue-pass-confirmation"]');
            if (btn) {
                e.preventDefault();
                alert('합격확인서 발급 기능은 준비 중입니다.');
                return;
            }
        });
    }

    function init() {
        initBatchSaveScores();
        initIssuePlaceholders();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
