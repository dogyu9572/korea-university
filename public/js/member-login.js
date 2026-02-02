(function () {
    'use strict';

    // 비밀번호 보기/숨기기
    document.querySelectorAll('.btn_trans_text').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var container = this.closest('.btn_set');
            var input = container ? container.querySelector('input[type="password"], input[type="text"]') : this.previousElementSibling;
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                if (container) container.classList.add('on');
                this.textContent = '인풋 문구 숨기기';
            } else {
                input.type = 'password';
                if (container) container.classList.remove('on');
                this.textContent = '인풋 문구 보기';
            }
        });
    });

    // 비밀번호 입력 제한 (영문/숫자/특수문자만)
    document.querySelectorAll('.pw').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]/g, '');
        });
    });
})();
