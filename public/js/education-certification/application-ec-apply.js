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

function isEducationCertForm(form) {
    const action = (form.action || '').toString();
    return action.indexOf('application_ec_apply/store') !== -1 ||
        action.indexOf('application_ec_e_learning/store') !== -1 ||
        action.indexOf('application_ec_receipt/store') !== -1;
}

function setupFormSubmit() {
    document.querySelectorAll('form.application_form').forEach(function (form) {
        if (!isEducationCertForm(form)) {
            return;
        }
        form.addEventListener('submit', function (event) {
            const fileWraps = form.querySelectorAll('.file_inputs');
            let hasStoredFile = false;
            fileWraps.forEach(function (wrap) {
                if (wrap._storedFile) {
                    hasStoredFile = true;
                }
            });
            if (!hasStoredFile) {
                return;
            }
            event.preventDefault();
            const fd = new FormData(form);
            fileWraps.forEach(function (wrap) {
                const input = wrap.querySelector('input[type="file"]');
                if (!input || !input.name) {
                    return;
                }
                fd.delete(input.name);
                if (wrap._storedFile) {
                    fd.append(input.name, wrap._storedFile);
                }
            });
            fetch(form.action, {
                method: 'POST',
                body: fd,
                redirect: 'manual',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            }).then(function (res) {
                if (res.type === 'opaqueredirect' || res.status === 302) {
                    var loc = res.headers.get('Location');
                    if (loc) {
                        window.location.href = loc;
                    }
                    return;
                }
                if (res.status === 422) {
                    window.location.href = form.action;
                    return;
                }
                alert('제출 중 오류가 발생했습니다.');
            }).catch(function () {
                alert('제출 중 오류가 발생했습니다.');
            });
        });
    });
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

function setupSchoolPopup() {
    const registerButton = document.getElementById('popSchoolRegister');
    if (!registerButton) {
        return;
    }
    registerButton.addEventListener('click', function () {
        const selected = document.querySelector('input[name="pop_school_select"]:checked');
        if (!selected) {
            return;
        }
        const targets = document.querySelectorAll('.input_school');
        targets.forEach(function (input) {
            input.value = selected.value || '';
        });
    });
}

function setupFeeTypeRestriction() {
    const typeInput = document.getElementById('memberSchoolType');
    if (!typeInput) {
        return;
    }

    const memberType = typeInput.value;
    if (memberType !== 'member' && memberType !== 'guest') {
        return;
    }

    const feeRadios = document.querySelectorAll('input[name="fee_type"]');
    if (!feeRadios.length) {
        return;
    }

    // 잘못된 그룹 선택 시 알림 및 선택 취소
    feeRadios.forEach(function (radio) {
        const group = radio.getAttribute('data-fee-group');
        if (!group) {
            return;
        }

        radio.addEventListener('change', function (event) {
            if (!radio.checked) {
                return;
            }

            if (group === memberType) {
                return;
            }

            event.preventDefault();
            radio.checked = false;

            if (memberType === 'member') {
                alert('회원교 소속만 선택할 수 있는 참가비입니다.');
            } else {
                alert('비회원교 소속만 선택할 수 있는 참가비입니다.');
            }
        });
    });

    // 초기 로딩 시 회원 유형에 맞는 첫 번째 항목 자동 선택
    let hasValidChecked = false;
    feeRadios.forEach(function (radio) {
        const group = radio.getAttribute('data-fee-group');
        if (radio.checked) {
            if (group !== memberType) {
                radio.checked = false;
            } else {
                hasValidChecked = true;
            }
        }
    });

    if (!hasValidChecked) {
        for (let i = 0; i < feeRadios.length; i++) {
            const radio = feeRadios[i];
            if (radio.getAttribute('data-fee-group') === memberType) {
                radio.checked = true;
                break;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // 유효성 검사 실패 시 첫 번째 에러 위치로 스크롤
    var form = document.querySelector('.application_form[data-join-errors]');
    if (form) {
        var errs = form.querySelectorAll('.join_field_error');
        var firstErr = null;
        for (var i = 0; i < errs.length; i++) {
            if (errs[i].offsetParent !== null && errs[i].textContent.trim()) {
                firstErr = errs[i];
                break;
            }
        }
        if (firstErr) {
            var scrollTarget = firstErr.closest('dl') || firstErr.closest('tr') || firstErr.closest('.tbl') || firstErr;
            var input = scrollTarget.querySelector('input:not([type="hidden"]):not([readonly]), select');
            if (!input && scrollTarget !== firstErr) {
                input = firstErr.closest('dd') && firstErr.closest('dd').querySelector('input:not([type="hidden"]):not([readonly]), select');
            }
            var focusTarget = (input && input.offsetParent) ? input : null;
            scrollTarget.style.scrollMarginTop = '120px';
            scrollTarget.scrollIntoView({ behavior: 'instant', block: 'start' });
            scrollTarget.style.scrollMarginTop = '';
            if (focusTarget) focusTarget.focus();
        }
    }

    setupFileInputs();
    setupFormSubmit();
    setupReceiptToggles();
    setupSchoolPopup();
    setupPhoneFormat();
    setupFeeTypeRestriction();
});
