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
            setFileLabel(wrap, '');
        });
    }
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
        const file = target.files && target.files[0];
        setFileLabel(wrap, file ? file.name : '');
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

document.addEventListener('DOMContentLoaded', function () {
    setupFileInputs();
    setupReceiptToggles();
    setupSchoolPopup();
});
