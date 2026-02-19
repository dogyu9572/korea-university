/**
 * 문의 작성 - 멀티 파일 첨부 (최대 3개, 추가 방식, 취소 시 기존 유지)
 */
(function () {
    const MAX_FILES = 3;
    const MAX_SIZE = 10 * 1024 * 1024; // 10MB

    var selectedFiles = [];

    function getWrapFilesArray(wrap) {
        if (!wrap._inquiryWriteFiles) {
            wrap._inquiryWriteFiles = [];
        }
        return wrap._inquiryWriteFiles;
    }

    function renderFileList(wrap, files) {
        var fileInputDiv = wrap.querySelector('.file_input');
        if (!fileInputDiv) return;
        fileInputDiv.innerHTML = '';
        if (files.length === 0) {
            fileInputDiv.textContent = '선택된 파일 없음';
            fileInputDiv.classList.remove('w100p');
            return;
        }
        fileInputDiv.classList.add('w100p');
        files.forEach(function (file, index) {
            var div = document.createElement('div');
            div.className = 'file_item';
            div.setAttribute('data-index', String(index));
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn_remove';
            btn.textContent = file.name;
            div.appendChild(btn);
            fileInputDiv.appendChild(div);
        });
    }

    function setupFileInputs() {
        var wrap = document.querySelector('#inquiryWriteForm .file_inputs');
        if (!wrap) return;

        var input = wrap.querySelector('input[type="file"]');
        if (!input) return;

        input.addEventListener('change', function () {
            var files = Array.from(this.files || []);
            this.value = '';

            if (files.length === 0) {
                return;
            }

            var list = getWrapFilesArray(wrap);
            var added = 0;
            for (var i = 0; i < files.length && list.length < MAX_FILES; i++) {
                if (files[i].size > MAX_SIZE) {
                    alert('"' + files[i].name + '" 파일은 10MB를 초과할 수 없습니다.');
                    continue;
                }
                list.push(files[i]);
                added++;
            }
            if (added < files.length && list.length >= MAX_FILES) {
                alert('파일은 최대 3개까지 선택할 수 있습니다.');
            } else if (added < files.length) {
                alert('일부 파일이 10MB를 초과하여 제외되었습니다.');
            }

            if (list.length > MAX_FILES) {
                list.splice(MAX_FILES);
            }

            renderFileList(wrap, list);
        });

        wrap.addEventListener('click', function (e) {
            if (!e.target || e.target.className !== 'btn_remove') return;
            var item = e.target.closest('.file_item');
            if (!item) return;
            var index = parseInt(item.getAttribute('data-index'), 10);
            var list = getWrapFilesArray(wrap);
            list.splice(index, 1);
            renderFileList(wrap, list);
        });
    }

    function setupFormSubmit() {
        var form = document.getElementById('inquiryWriteForm');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var wrap = form.querySelector('.file_inputs');
            var list = wrap ? getWrapFilesArray(wrap) : [];

            var fd = new FormData(form);
            fd.delete('files[]');
            list.forEach(function (file) {
                fd.append('files[]', file);
            });

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            fetch(form.action, {
                method: 'POST',
                body: fd,
                redirect: 'manual',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(function (res) {
                if (res.type === 'redirect' || res.status === 302) {
                    var loc = res.headers.get('Location');
                    if (loc) {
                        window.location.href = loc;
                        return;
                    }
                }
                if (res.status === 422) {
                    if (submitBtn) submitBtn.disabled = false;
                    return res.json().then(function (data) {
                        if (data.errors) {
                            var msg = Object.values(data.errors).flat().join('\n');
                            alert(msg);
                        } else {
                            alert('입력 내용을 확인해주세요.');
                        }
                    }).catch(function () {
                        alert('입력 내용을 확인해주세요.');
                    });
                }
                if (res.ok) {
                    return res.json().then(function (data) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = form.action.replace('/store', '');
                        }
                    }).catch(function () {
                        window.location.href = form.action.replace('/store', '');
                    });
                }
                if (submitBtn) submitBtn.disabled = false;
                alert('등록 중 오류가 발생했습니다.');
            }).catch(function () {
                if (submitBtn) submitBtn.disabled = false;
                alert('등록 중 오류가 발생했습니다.');
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupFileInputs();
        setupFormSubmit();
    });
})();
