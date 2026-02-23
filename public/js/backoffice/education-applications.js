// 교육 신청내역 관리 JavaScript

// show 페이지 기준 경로 (온라인 교육 신청내역일 때 data-base-path로 덮어씀)
function getApplicationsBasePath() {
    const container = document.querySelector('.education-applications');
    return container?.getAttribute('data-base-path') || '/backoffice/education-applications';
}

// 일괄 입금완료
function batchPaymentComplete() {
    const selected = getSelectedApplications();
    if (selected.length === 0) {
        alert('선택된 신청이 없습니다.');
        return;
    }
    
    if (confirm('선택된 ' + selected.length + '건을 입금완료로 변경하시겠습니까?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const route = getApplicationsBasePath() + '/batch-payment-complete';
        
        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                application_ids: selected
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('처리 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('처리 중 오류가 발생했습니다.');
        });
    }
}

// 일괄 이수
function batchComplete() {
    const selected = getSelectedApplications();
    if (selected.length === 0) {
        alert('선택된 신청이 없습니다.');
        return;
    }
    
    if (confirm('선택된 ' + selected.length + '건을 이수 처리하시겠습니까?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const route = getApplicationsBasePath() + '/batch-complete';
        
        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                application_ids: selected
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('처리 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('처리 중 오류가 발생했습니다.');
        });
    }
}

// 선택된 신청 ID 배열 반환
function getSelectedApplications() {
    const checkboxes = document.querySelectorAll('.application-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// 엑셀 다운로드 (선택된 항목만)
function exportExcel() {
    const selected = getSelectedApplications();
    if (selected.length === 0) {
        alert('다운로드할 항목을 선택해주세요.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const container = document.querySelector('.education-applications');
    const programId = container?.getAttribute('data-program-id');
    const basePath = getApplicationsBasePath();
    
    if (!programId) {
        alert('프로그램 정보를 찾을 수 없습니다.');
        return;
    }

    const route = basePath + '/' + programId + '/export';

    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'text/csv'
        },
        body: JSON.stringify({
            application_ids: selected
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('다운로드 실패');
        }
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'education_applications_' + programId + '_' + new Date().getTime() + '.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('엑셀 다운로드 중 오류가 발생했습니다.');
    });
}

// 신청별 즉시 업데이트 공통 경로
function getApplicationUpdateBaseUrl() {
    return '/backoffice/applications';
}

// 결제상태 업데이트
function updatePaymentStatus(applicationId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = getApplicationUpdateBaseUrl() + '/' + applicationId + '/payment-status';
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ payment_status: status })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                return;
            }
            alert('저장 중 오류가 발생했습니다.');
        })
        .catch(function () {
            alert('저장 중 오류가 발생했습니다.');
        });
}

// 세금계산서 상태 업데이트
function updateTaxInvoiceStatus(applicationId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = getApplicationUpdateBaseUrl() + '/' + applicationId + '/tax-invoice-status';
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ tax_invoice_status: status })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                return;
            }
            alert('저장 중 오류가 발생했습니다.');
        })
        .catch(function () {
            alert('저장 중 오류가 발생했습니다.');
        });
}

function updateCashReceiptStatus(applicationId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = getApplicationUpdateBaseUrl() + '/' + applicationId + '/cash-receipt-status';
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ cash_receipt_status: status })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                return;
            }
            alert('저장 중 오류가 발생했습니다.');
        })
        .catch(function () {
            alert('저장 중 오류가 발생했습니다.');
        });
}

// 수강상태 업데이트
function updateCourseStatus(applicationId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = getApplicationUpdateBaseUrl() + '/' + applicationId + '/course-status';
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ course_status: status })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                return;
            }
            alert('저장 중 오류가 발생했습니다.');
        })
        .catch(function () {
            alert('저장 중 오류가 발생했습니다.');
        });
}

// 이수 여부 업데이트
function updateCompletionStatus(applicationId, isCompleted) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = getApplicationUpdateBaseUrl() + '/' + applicationId + '/completion-status';
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ is_completed: isCompleted ? 1 : 0 })
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                return;
            }
            alert('저장 중 오류가 발생했습니다.');
        })
        .catch(function () {
            alert('저장 중 오류가 발생했습니다.');
        });
}

// 수료증 발급
function issueCertificate(applicationId) {
    // TODO: 수료증 발급 로직 구현 필요
    alert('수료증 발급 기능은 준비 중입니다.');
}

// 영수증 발급
function issueReceipt(applicationId) {
    // TODO: 영수증 발급 로직 구현 필요
    alert('영수증 발급 기능은 준비 중입니다.');
}

// ------------------------------
// 회원 검색 모달
// ------------------------------

/**
 * 회원 검색 목록 조회에 사용할 기본 URL을 반환합니다.
 * Blade에서 data-member-search-url 속성으로 주입합니다.
 */
function getMemberSearchBaseUrl() {
    const modal = document.getElementById('memberSearchModal');
    return modal?.getAttribute('data-member-search-url') || '/backoffice/members';
}

// 전역으로 노출: Blade의 onclick 핸들러에서 사용 (applicant 검색)
window.openMemberSearch = function (target) {
    window.memberSearchTarget = target || 'applicant';
    if (typeof $ === 'function' && $('#memberSearchModal').length) {
        $('#memberSearchModal').modal('show');
    }

    const typeEl = document.getElementById('member_search_type');
    const keywordEl = document.getElementById('member_search_keyword');
    if (typeEl) typeEl.value = '전체';
    if (keywordEl) keywordEl.value = '';

    // 처음 열릴 때는 전체 회원 리스트 바로 조회
    window.searchMembers(1);
};

// 세미나/해외연수: 룸메이트 회원 검색
window.openRoommateSearch = function () {
    window.openMemberSearch('roommate');
};

window.searchMembers = function (page = 1) {
    const typeEl = document.getElementById('member_search_type');
    const keywordEl = document.getElementById('member_search_keyword');
    if (!typeEl || !keywordEl) {
        return;
    }

    const searchType = typeEl.value;
    const keyword = keywordEl.value;

    const baseUrl = getMemberSearchBaseUrl();
    const urlObj = new URL(baseUrl, window.location.origin);

    // 검색어가 있으면 필터, 없으면 전체 목록 (백엔드는 search_term 사용)
    if (keyword.trim()) {
        urlObj.searchParams.set('search_term', keyword.trim());
    }
    if (searchType && searchType !== '전체') {
        urlObj.searchParams.set('search_type', searchType);
    }
    urlObj.searchParams.set('page', page);
    urlObj.searchParams.set('per_page', 20);

    fetch(urlObj.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            // HTML 뷰를 그대로 받기 위해 Accept는 HTML 우선으로 지정
            'Accept': 'text/html, */*;q=0.1'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const rows = doc.querySelectorAll('table.board-table tbody tr');

            const tbody = document.getElementById('memberSearchResults');
            const paginationDiv = document.getElementById('memberSearchPagination');
            if (!tbody) return;

            if (rows.length > 0) {
                let htmlContent = '';
                rows.forEach((row, index) => {
                    const cells = row.querySelectorAll('td');
                    // members.index.blade.php 컬럼 구조:
                    // 0: 체크박스, 1: No, 2: 가입구분, 3: ID, 4: 학교명, 5: 이름, 6: 휴대폰번호, 7: 이메일주소 ...
                    if (cells.length >= 8) {
                        const memberId = row.querySelector('input[type="checkbox"]')?.value || '';
                        const loginId = cells[3]?.textContent?.trim() || '-';
                        const school = cells[4]?.textContent?.trim() || '';
                        const name = cells[5]?.textContent?.trim() || '-';
                        const phone = cells[6]?.textContent?.trim() || '-';
                        const email = cells[7]?.textContent?.trim() || '';

                        htmlContent += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${loginId}</td>
                                <td>${name}</td>
                                <td>${phone}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="selectMember('${memberId}', '${loginId.replace(/'/g, "\\'")}', '${name.replace(/'/g, "\\'")}', '${phone.replace(/'/g, "\\'")}', '${email.replace(/'/g, "\\'")}', '${school.replace(/'/g, "\\'")}')">
                                        선택
                                    </button>
                                </td>
                            </tr>
                        `;
                    }
                });
                tbody.innerHTML = htmlContent || '<tr><td colspan="5" class="text-center">검색 결과가 없습니다.</td></tr>';
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">검색 결과가 없습니다.</td></tr>';
            }

            // 페이지네이션: members.index의 페이지네이션을 그대로 복사
            if (paginationDiv) {
                const pagination = doc.querySelector('.pagination');
                if (pagination) {
                    paginationDiv.innerHTML = '';
                    const cloned = pagination.cloneNode(true);
                    paginationDiv.appendChild(cloned);

                    paginationDiv.querySelectorAll('a.page-link').forEach(link => {
                        const href = link.getAttribute('href') || '';
                        let targetPage = 1;
                        try {
                            const u = new URL(href, window.location.origin);
                            targetPage = parseInt(u.searchParams.get('page') || '1', 10) || 1;
                        } catch (e) {
                            // ignore parse error
                        }
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            window.searchMembers(targetPage);
                        });
                        link.setAttribute('href', '#');
                    });
                } else {
                    paginationDiv.innerHTML = '';
                }
            }
        })
        .catch(error => {
            console.error('회원 검색 실패:', error);
            alert('회원 검색 기능을 사용할 수 없습니다. 신청자 ID를 직접 입력해주세요.');
        });
};

window.selectMember = function (memberId, loginId, name, phone, email, school) {
    if (window.memberSearchTarget === 'roommate') {
        const roommateIdEl = document.getElementById('roommate_member_id');
        const roommateNameEl = document.getElementById('roommate_name');
        const roommatePhoneEl = document.getElementById('roommate_phone');
        const roommateDisplayEl = document.getElementById('roommate_display');
        if (roommateIdEl) roommateIdEl.value = memberId || '';
        if (roommateNameEl) roommateNameEl.value = name || '';
        if (roommatePhoneEl) roommatePhoneEl.value = phone || '';
        if (roommateDisplayEl) roommateDisplayEl.value = (name && phone) ? name + ' / ' + phone : (name || phone || '');
        window.memberSearchTarget = 'applicant';
    } else {
        const idEl = document.getElementById('member_id');
        const loginIdEl = document.getElementById('member_login_id');
        const nameEl = document.getElementById('applicant_name');
        const phoneEl = document.getElementById('phone_number');
        const emailEl = document.getElementById('email');
        const affEl = document.getElementById('affiliation');

        if (idEl) idEl.value = memberId || '';
        if (loginIdEl) loginIdEl.value = loginId || '';
        if (nameEl) nameEl.value = name || '';
        if (phoneEl) phoneEl.value = phone || '';
        if (emailEl) emailEl.value = email || '';
        if (affEl) affEl.value = school || '';
    }

    if (typeof $ === 'function') {
        $('#memberSearchModal').modal('hide');
    }
};

// ------------------------------
// 첨부파일 삭제 버튼 (수정 화면)
// ------------------------------

function initApplicationAttachmentDeleteButtons() {
    const buttons = document.querySelectorAll('.delete-attachment-btn');
    if (!buttons.length) return;

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const attachmentId = this.getAttribute('data-attachment-id');
            const deleteInput = document.getElementById('delete_attachment_' + attachmentId);
            const attachmentItem = this.closest('span');

            if (confirm('첨부파일을 삭제하시겠습니까?')) {
                if (deleteInput) {
                    deleteInput.value = attachmentId;
                }
                if (attachmentItem) {
                    attachmentItem.style.display = 'none';
                }
            }
        });
    });
}

// 회원 검색 폼 엔터/submit 처리
function initMemberSearchForm() {
    const form = document.getElementById('memberSearchForm');
    const keywordEl = document.getElementById('member_search_keyword');
    const searchBtn = document.querySelector('#memberSearchModal [data-action="member-search"]');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            window.searchMembers(1);
        });
    }

    if (keywordEl) {
        keywordEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.searchMembers(1);
            }
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', function () {
            window.searchMembers(1);
        });
    }
}

// create/edit 페이지: 회원 검색 버튼 바인딩
function initCreateEditPageHandlers() {
    const clearAttachmentBtn = document.querySelector('[data-action="clear-attachment-files"]');
    if (clearAttachmentBtn) {
        clearAttachmentBtn.addEventListener('click', function () {
            if (!confirm('사업자등록증 선택 파일을 삭제하시겠습니까?')) return;
            const row = this.closest('.form-row-inline');
            const fileInput = row && row.querySelector('input[type="file"][name="attachments[]"]');
            if (fileInput) fileInput.value = '';
        });
    }
    const openSearchBtn = document.querySelector('[data-action="open-member-search"]');
    if (openSearchBtn) {
        openSearchBtn.addEventListener('click', function () {
            window.openMemberSearch('applicant');
        });
    }
    const openRoommateSearchBtn = document.querySelector('[data-action="open-roommate-search"]');
    if (openRoommateSearchBtn) {
        openRoommateSearchBtn.addEventListener('click', function () {
            window.openRoommateSearch();
        });
    }
    const openRoommateRequestsBtn = document.querySelector('[data-action="open-roommate-requests"]');
    if (openRoommateRequestsBtn) {
        openRoommateRequestsBtn.addEventListener('click', function () {
            const form = document.getElementById('applicationForm');
            const listUrl = form?.getAttribute('data-roommate-requests-url');
            if (!listUrl) return;
            const modal = document.getElementById('roommateRequestsModal');
            const tbody = document.getElementById('roommateRequestsResults');
            if (!modal || !tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">요청 내역을 불러오는 중입니다.</td></tr>';
            $(modal).modal('show');
            fetch(listUrl, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    const list = (data && data.list) ? data.list : [];
                    if (list.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">받은 룸메이트 요청이 없습니다.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = list.map(function (row) {
                        return '<tr>' +
                            '<td>' + (row.no || '') + '</td>' +
                            '<td>' + (row.login_id || '') + '</td>' +
                            '<td>' + (row.name || '') + '</td>' +
                            '<td>' + (row.phone_number || '') + '</td>' +
                            '<td><button type="button" class="btn btn-primary btn-sm" data-roommate-approve="' + row.application_id + '">승인</button></td>' +
                            '<td><button type="button" class="btn btn-outline-secondary btn-sm" data-roommate-reject="' + row.application_id + '">거절</button></td>' +
                            '</tr>';
                    }).join('');
                })
                .catch(function () {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">요청 내역을 불러오지 못했습니다.</td></tr>';
                });
        });
    }
    document.addEventListener('click', function (e) {
        const approveBtn = e.target.closest('[data-roommate-approve]');
        const rejectBtn = e.target.closest('[data-roommate-reject]');
        const form = document.getElementById('applicationForm');
        const listUrl = form?.getAttribute('data-roommate-requests-url');
        const approveUrl = form?.getAttribute('data-roommate-requests-approve-url');
        const rejectUrl = form?.getAttribute('data-roommate-requests-reject-url');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (approveBtn && approveUrl && csrfToken) {
            e.preventDefault();
            const applicationId = approveBtn.getAttribute('data-roommate-approve');
            if (!applicationId) return;
            approveBtn.disabled = true;
            fetch(approveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ requesting_application_id: applicationId })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data && data.success) {
                        if (data.roommate) {
                            const idEl = document.getElementById('roommate_member_id');
                            const nameEl = document.getElementById('roommate_name');
                            const phoneEl = document.getElementById('roommate_phone');
                            const displayEl = document.getElementById('roommate_display');
                            if (idEl) idEl.value = data.roommate.member_id || '';
                            if (nameEl) nameEl.value = data.roommate.name || '';
                            if (phoneEl) phoneEl.value = data.roommate.phone || '';
                            if (displayEl) displayEl.value = (data.roommate.name && data.roommate.phone) ? data.roommate.name + ' / ' + data.roommate.phone : (data.roommate.name || data.roommate.phone || '');
                        }
                        $('#roommateRequestsModal').modal('hide');
                        alert(data.message || '승인 완료되었습니다.');
                    } else {
                        alert(data.message || '처리 중 오류가 발생했습니다.');
                    }
                })
                .catch(function () { alert('처리 중 오류가 발생했습니다.'); })
                .finally(function () { approveBtn.disabled = false; });
            return;
        }
        if (rejectBtn && rejectUrl && csrfToken) {
            e.preventDefault();
            const applicationId = rejectBtn.getAttribute('data-roommate-reject');
            if (!applicationId) return;
            if (!confirm('해당 룸메이트 요청을 거절하시겠습니까?')) return;
            rejectBtn.disabled = true;
            fetch(rejectUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ requesting_application_id: applicationId })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data && data.success) {
                        alert('거절하였습니다.');
                        var openBtn = document.querySelector('[data-action="open-roommate-requests"]');
                        if (openBtn) openBtn.click();
                    } else {
                        alert(data.message || '처리 중 오류가 발생했습니다.');
                    }
                })
                .catch(function () { alert('처리 중 오류가 발생했습니다.'); })
                .finally(function () { rejectBtn.disabled = false; });
            return;
        }
    });
    // 세미나/해외연수: 참가비 타입 동기화 (회원교/비회원교 + 2인1실/1인실/비숙박 -> fee_type, 금액 표시)
    const feeTypeEl = document.getElementById('fee_type');
    const feeSchoolInput = document.querySelector('input[name="fee_school_type"]');
    const feeAccomRadios = document.querySelectorAll('input[name="fee_accommodation"]');
    const feeTypeBlock = document.getElementById('fee-type-block');
    const participationFeeEl = document.getElementById('participation_fee');
    const participationFeeDisplay = document.getElementById('participation_fee_display');
    if (feeTypeEl && (feeSchoolInput || feeAccomRadios.length)) {
        function getFeeSchoolValue() {
            if (!feeSchoolInput) return '';
            if (feeSchoolInput.type === 'hidden') return feeSchoolInput.value;
            var checked = document.querySelector('input[name="fee_school_type"]:checked');
            return checked ? checked.value : '';
        }
        function formatAmount(num) {
            if (num === null || num === '' || isNaN(num)) return '';
            return Number(num).toLocaleString('ko-KR') + '원';
        }
        function updateFeeType() {
            var schoolVal = getFeeSchoolValue();
            var accom = document.querySelector('input[name="fee_accommodation"]:checked');
            var accomVal = accom ? accom.value : '';
            var prefix = (schoolVal === '비회원교') ? 'guest' : 'member';
            var suffix = (accomVal === '1인실') ? 'single' : ((accomVal === '비숙박') ? 'no_stay' : 'twin');
            var feeType = prefix + '_' + suffix;
            feeTypeEl.value = feeType;

            if (feeTypeBlock && participationFeeEl && participationFeeDisplay) {
                var dataKey = 'fee-' + prefix + '-' + (suffix === 'no_stay' ? 'no-stay' : suffix);
                var amount = feeTypeBlock.getAttribute('data-' + dataKey);
                if (amount !== null && amount !== '') {
                    var num = parseFloat(amount);
                    participationFeeEl.value = isNaN(num) ? '' : num;
                    participationFeeDisplay.textContent = formatAmount(num);
                } else {
                    participationFeeEl.value = '';
                    participationFeeDisplay.textContent = '';
                }
            }
        }
        if (feeSchoolInput && feeSchoolInput.type !== 'hidden') {
            feeSchoolInput.addEventListener('change', updateFeeType);
        }
        feeAccomRadios.forEach(function (r) { r.addEventListener('change', updateFeeType); });
        updateFeeType();
    }
}

// 자격증 증명사진 삭제 버튼
function initIdPhotoDeleteButton() {
    const btn = document.getElementById('btnDeleteIdPhoto');
    if (!btn) return;
    btn.addEventListener('click', function () {
        const deleteInput = document.getElementById('delete_id_photo');
        const wrapper = this.closest('.mt-2');
        if (confirm('증명사진을 삭제하시겠습니까?')) {
            if (deleteInput) deleteInput.value = '1';
            if (wrapper) wrapper.style.display = 'none';
        }
    });
}

// show 페이지: 전체 선택 체크박스
function initSelectAllApplicationCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    if (!selectAll) return;
    selectAll.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.application-checkbox');
        checkboxes.forEach(function (cb) {
            cb.checked = selectAll.checked;
        });
    });
}

// show 페이지: 일괄/결제/이수/삭제 등 data-action 바인딩
function initShowPageHandlers() {
    const container = document.querySelector('.education-applications');
    if (!container) return;

    container.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action="batch-payment-complete"]');
        if (btn) {
            e.preventDefault();
            batchPaymentComplete();
            return;
        }
        const btnComplete = e.target.closest('[data-action="batch-complete"]');
        if (btnComplete) {
            e.preventDefault();
            batchComplete();
            return;
        }
        const btnCert = e.target.closest('[data-action="issue-certificate"]');
        if (btnCert) {
            if (container.classList.contains('certification-applications')) {
                return;
            }
            e.preventDefault();
            var id = btnCert.getAttribute('data-application-id');
            if (id) issueCertificate(id);
            return;
        }
        const btnReceipt = e.target.closest('[data-action="issue-receipt"]');
        if (btnReceipt) {
            if (container.classList.contains('certification-applications')) {
                return;
            }
            e.preventDefault();
            var id = btnReceipt.getAttribute('data-application-id');
            if (id) issueReceipt(id);
            return;
        }
        const btnExport = e.target.closest('[data-action="export-excel"]');
        if (btnExport) {
            e.preventDefault();
            exportExcel();
            return;
        }
    });

    container.addEventListener('change', function (e) {
        const el = e.target.closest('[data-action="update-payment-status"]');
        if (el && el.nodeName === 'SELECT') {
            var id = el.getAttribute('data-application-id');
            if (id) updatePaymentStatus(id, el.value);
            return;
        }
        const taxEl = e.target.closest('[data-action="update-tax-invoice-status"]');
        if (taxEl && taxEl.nodeName === 'SELECT') {
            var id = taxEl.getAttribute('data-application-id');
            if (id) updateTaxInvoiceStatus(id, taxEl.value);
            return;
        }
        const cashEl = e.target.closest('[data-action="update-cash-receipt-status"]');
        if (cashEl && cashEl.nodeName === 'SELECT') {
            var id = cashEl.getAttribute('data-application-id');
            if (id) updateCashReceiptStatus(id, cashEl.value);
            return;
        }
        const courseEl = e.target.closest('[data-action="update-course-status"]');
        if (courseEl && courseEl.nodeName === 'SELECT') {
            var id = courseEl.getAttribute('data-application-id');
            if (id) updateCourseStatus(id, courseEl.value);
            return;
        }
        const radio = e.target.closest('[data-action="update-completion-status"]');
        if (radio && radio.nodeName === 'INPUT') {
            var id = radio.getAttribute('data-application-id');
            var completed = radio.getAttribute('data-completed') === '1';
            if (id) updateCompletionStatus(id, completed);
            return;
        }
    });

    container.addEventListener('submit', function (e) {
        const form = e.target.closest('form[data-action="confirm-delete"]');
        if (form) {
            e.preventDefault();
            if (confirm('정말 삭제하시겠습니까?')) {
                form.submit();
            }
            return;
        }
    });
}

// index 페이지: 표시 개수 변경 처리
function initIndexPageHandlers() {
    const perPageSelect = document.getElementById('per_page');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function () {
            const form = document.getElementById('perPageForm');
            if (form) {
                form.submit();
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initApplicationAttachmentDeleteButtons();
    initIdPhotoDeleteButton();
    initMemberSearchForm();
    initCreateEditPageHandlers();
    initSelectAllApplicationCheckboxes();
    initShowPageHandlers();
    initIndexPageHandlers();
});
