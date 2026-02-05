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
    setupFileInputs();
    setupReceiptToggles();
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
