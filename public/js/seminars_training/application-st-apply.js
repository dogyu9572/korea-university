function setFileLabel(wrapper, filename) {
    const label = wrapper.querySelector('.file_input');
    if (!label) {
        return;
    }
    if (filename) {
        label.classList.add('w100p');
        label.innerHTML = '<button type="button">' + filename + '</button>';
    } else {
        label.classList.remove('w100p');
        label.textContent = '선택된 파일 없음';
    }
}

function toggleSectionInputs(selector, enabled) {
    const box = document.querySelector(selector);
    if (!box) {
        return;
    }
    const inputs = box.querySelectorAll('input, select, textarea');
    inputs.forEach(function (input) {
        input.disabled = !enabled;
        if (!enabled && (input.type === 'radio' || input.type === 'checkbox')) {
            input.checked = false;
        }
        if (!enabled && input.type === 'file') {
            input.value = '';
        }
    });
    if (!enabled) {
        box.querySelectorAll('.file_inputs').forEach(function (wrap) {
            wrap._storedFile = null;
            setFileLabel(wrap, '');
        });
    }
}

function isSeminarApplyForm(form) {
    return (form.action || '').toString().indexOf('application_st_apply') !== -1;
}

/** 1인실/비숙박 선택 시 룸메이트 영역 숨김, 2인1실일 때만 표시 */
function setupRoommateSectionToggle() {
    var section = document.getElementById('roommate_section');
    var form = document.querySelector('.application_form');
    if (!section || !form) {
        return;
    }
    var noRoommateTypesStr = section.getAttribute('data-no-roommate-types') || '';
    var noRoommateTypes = noRoommateTypesStr ? noRoommateTypesStr.split(',').map(function (s) { return s.trim(); }) : [];

    function updateVisibility() {
        var checked = form.querySelector('input[name="fee_type"]:checked');
        var feeType = checked ? (checked.value || '').trim() : '';
        var hide = noRoommateTypes.indexOf(feeType) !== -1;
        section.style.display = hide ? 'none' : '';
    }

    updateVisibility();
    form.querySelectorAll('input[name="fee_type"]').forEach(function (radio) {
        radio.addEventListener('change', updateVisibility);
    });
}

function setupFormSubmit() {
    /* 세미나 신청: 항상 브라우저 기본 제출. 파일은 input에 있으면 그대로 전송됨. 검증 실패 시 파일은 서버 TempUploadSessionHelper로 유지. */
}

function setupFileInputs() {
    document.addEventListener('change', function (event) {
        const target = event.target;
        if (!target || target.type !== 'file') {
            return;
        }
        const wrap = target.closest('.file_inputs');
        if (!wrap) {
            return;
        }
        if (!target.files || target.files.length === 0) {
            return;
        }
        wrap._storedFile = target.files[0];
        setFileLabel(wrap, target.files[0].name);
    });

    document.addEventListener('click', function (event) {
        const target = event.target;
        if (!target || target.tagName !== 'BUTTON') {
            return;
        }
        const wrap = target.closest('.file_inputs');
        if (!wrap) {
            return;
        }
        if (wrap.hasAttribute('data-temp-file')) {
            const fileKey = wrap.getAttribute('data-temp-file');
            if (fileKey && !wrap.querySelector('input[name="clear_' + fileKey + '"]')) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'clear_' + fileKey;
                hidden.value = '1';
                wrap.appendChild(hidden);
            }
            wrap._storedFile = null;
            setFileLabel(wrap, '');
            wrap.removeAttribute('data-temp-file');
            return;
        }
        /* 파일명 버튼 클릭: 파일 지우지 말고 선택 창만 띄움. disabled(미발행)일 땐 건드리지 않음 */
        if (target.closest('.file_input')) {
            var input = wrap.querySelector('input[type="file"]');
            if (input && !input.disabled) {
                event.preventDefault();
                input.click();
            }
            return;
        }
        wrap._storedFile = null;
        const input = wrap.querySelector('input[type="file"]');
        if (input) {
            input.value = '';
        }
        setFileLabel(wrap, '');
    });
}

function setupReceiptToggles() {
    const cashRadios = document.querySelectorAll('input[name="has_cash_receipt"]');
    const taxRadios = document.querySelectorAll('input[name="has_tax_invoice"]');

    function updateCash() {
        const checked = document.querySelector('input[name="has_cash_receipt"]:checked');
        const enabled = checked ? checked.value === '1' : false;
        toggleSectionInputs('[data-cash-box]', enabled);
    }

    function updateTax() {
        const checked = document.querySelector('input[name="has_tax_invoice"]:checked');
        const enabled = checked ? checked.value === '1' : false;
        toggleSectionInputs('[data-tax-box]', enabled);
    }

    cashRadios.forEach(function (radio) {
        radio.addEventListener('change', updateCash);
    });
    taxRadios.forEach(function (radio) {
        radio.addEventListener('change', updateTax);
    });

    updateCash();
    updateTax();
}

function formatPhoneInput(val) {
    var digits = (val || '').replace(/\D/g, '');
    if (digits.length === 0) return '';
    if (digits.length <= 3 && digits.indexOf('010') === 0) return digits;
    if (digits.length <= 3 && digits.indexOf('01') === 0) return digits;
    if (digits.length <= 2 && digits.indexOf('02') === 0) return digits;
    if (digits.length <= 2) return digits;
    if (digits.length <= 11 && digits.indexOf('010') === 0) {
        if (digits.length <= 7) return digits.slice(0, 3) + (digits.length > 3 ? '-' + digits.slice(3) : '');
        return digits.slice(0, 3) + '-' + digits.slice(3, 7) + (digits.length > 7 ? '-' + digits.slice(7, 11) : '');
    }
    if (digits.length <= 10 && digits.indexOf('02') === 0) {
        if (digits.length <= 5) return digits.slice(0, 2) + (digits.length > 2 ? '-' + digits.slice(2) : '');
        return digits.slice(0, 2) + '-' + digits.slice(2, 5) + (digits.length > 5 ? '-' + digits.slice(5, 10) : '');
    }
    if (digits.length <= 10 && /^01[1-9]/.test(digits)) {
        if (digits.length <= 6) return digits.slice(0, 3) + (digits.length > 3 ? '-' + digits.slice(3) : '');
        return digits.slice(0, 3) + '-' + digits.slice(3, 6) + (digits.length > 6 ? '-' + digits.slice(6, 10) : '');
    }
    return digits.slice(0, 13);
}

function setupPhoneFormat() {
    document.querySelectorAll('input[name="contact_person_phone"]').forEach(function (input) {
        input.addEventListener('input', function () {
            var start = this.selectionStart;
            var oldLen = this.value.length;
            var formatted = formatPhoneInput(this.value);
            this.value = formatted;
            var newLen = formatted.length;
            var diff = newLen - oldLen;
            var newStart = Math.max(0, Math.min(start + diff, formatted.length));
            this.setSelectionRange(newStart, newStart);
        });
    });
}

function layerShow(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'block';
    }
}

function layerHide(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'none';
    }
}

var lastRoommate = null;

function setupRoommateSearch() {
    var form = document.querySelector('.application_form');
    var url = form && form.getAttribute('data-roommate-check-url');
    var searchBtn = document.querySelector('[data-action="roommate-search"]');
    var phoneInput = document.getElementById('roommate_phone_input');
    if (!url || !searchBtn || !phoneInput) {
        return;
    }
    searchBtn.addEventListener('click', function () {
        var raw = (phoneInput.value || '').trim();
        if (raw === '') {
            alert('휴대폰 번호를 입력해 주세요.');
            return;
        }
        lastRoommate = null;
        var fullUrl = url + (url.indexOf('?') !== -1 ? '&' : '?') + 'phone=' + encodeURIComponent(raw);
        fetch(fullUrl, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.found) {
                    lastRoommate = { id: data.id, name: data.name, phone: data.phone || '' };
                    layerShow('confirmInfo');
                } else if (data && data.reason === 'self') {
                    alert('본인은 룸메이트로 선택할 수 없습니다.');
                } else {
                    layerShow('noInfo');
                }
            })
            .catch(function () {
                layerShow('noInfo');
            });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // 유효성 검사 실패 후 복귀 시 룸메이트 입력값 복원 표시 (hidden은 old()로 채워지므로 성공 메시지만 표시)
    var roommateIdEl = document.getElementById('roommate_member_id');
    var roommateSuccessMsg = document.getElementById('roommateSuccessMsg');
    if (roommateIdEl && roommateIdEl.value && roommateSuccessMsg) {
        roommateSuccessMsg.classList.remove('hide');
    }

    // 유효성 검사 실패 시 첫 번째 에러 위치로 스크롤 (맨 위로 가지 않도록 즉시 실행)
    if (document.querySelector('.application_form[data-join-errors]')) {
        var errs = document.querySelectorAll('.application_form .join_field_error');
        var firstErr = null;
        for (var i = 0; i < errs.length; i++) {
            if (errs[i].offsetParent !== null && errs[i].textContent.trim()) {
                firstErr = errs[i];
                break;
            }
        }
        if (firstErr) {
            var dl = firstErr.closest('dl');
            var input = dl ? dl.querySelector('input:not([type="hidden"]):not([readonly]), select') : null;
            var scrollTarget = (input && input.offsetParent) ? input : (dl || firstErr);
            scrollTarget.style.scrollMarginTop = '120px';
            scrollTarget.scrollIntoView({ behavior: 'instant', block: 'start' });
            scrollTarget.style.scrollMarginTop = '';
            if (input) input.focus();
        }
    }

    setupFileInputs();
    setupFormSubmit();
    setupReceiptToggles();
    setupPhoneFormat();
    setupRoommateSectionToggle();
    setupRoommateSearch();

    var btnRoommateApply = document.getElementById('btnRoommateApply');
    if (btnRoommateApply) {
        btnRoommateApply.addEventListener('click', function () {
            if (lastRoommate) {
                var idEl = document.getElementById('roommate_member_id');
                var nameEl = document.getElementById('roommate_name');
                var phoneEl = document.getElementById('roommate_phone');
                if (idEl) idEl.value = lastRoommate.id;
                if (nameEl) nameEl.value = lastRoommate.name || '';
                if (phoneEl) phoneEl.value = lastRoommate.phone || '';
            }
            layerHide('confirmInfo');
            var successMsg = document.getElementById('roommateSuccessMsg');
            if (successMsg) {
                successMsg.classList.remove('hide');
            }
        });
    }
});
