/**
 * 자격증 관리 페이지 JavaScript
 */

(function() {
    // Summernote 에디터 초기화
    function initSummernote() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
            return;
        }

        const $editor = $('#content');
        if ($editor.length === 0) {
            return;
        }

        if ($editor.data('summernote')) {
            $editor.summernote('destroy');
        }

        $editor.summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0], this);
                }
            }
        });
    }

    // 이미지 업로드
    function uploadImage(file, editor) {
        const formData = new FormData();
        formData.append('image', file);
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            formData.append('_token', token.getAttribute('content'));
        }

        fetch('/backoffice/upload-image', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(result) {
            if (result.uploaded) {
                $(editor).summernote('insertImage', result.url);
            } else {
                alert('이미지 업로드에 실패했습니다.');
            }
        })
        .catch(function() {
            alert('이미지 업로드 중 오류가 발생했습니다.');
        });
    }

    // 폼 제출 전 Summernote 동기화
    function syncSummernoteBeforeSubmit() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
            return;
        }
        const $editor = $('#content');
        if ($editor.length && $editor.summernote) {
            try {
                const code = $editor.summernote('code');
                $editor.val(code != null ? code : '');
            } catch (e) {
                console.error('Summernote 동기화 실패:', e);
            }
        }
    }

    // 썸네일 삭제 버튼 처리
    function initDeleteButtons() {
        // 썸네일 삭제
        const deleteThumbnailBtn = document.querySelector('.delete-thumbnail-btn');
        if (deleteThumbnailBtn) {
            deleteThumbnailBtn.addEventListener('click', function() {
                const deleteInput = document.getElementById('delete_thumbnail');
                const thumbnailContainer = this.closest('div');
                
                if (confirm('썸네일을 삭제하시겠습니까?')) {
                    if (deleteInput) {
                        deleteInput.value = '1';
                    }
                    thumbnailContainer.style.display = 'none';
                }
            });
        }
    }

    // 초기화
    function init() {
        // Summernote 초기화
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initSummernote, 500);
                initDeleteButtons();
            });
        } else {
            setTimeout(initSummernote, 500);
            initDeleteButtons();
        }

        // 폼 제출 전 동기화
        const $form = $('#certificationForm');
        if ($form.length) {
            $form.on('submit', function() {
                syncSummernoteBeforeSubmit();
            });
        }
    }

    // jQuery 로드 대기
    if (typeof $ !== 'undefined') {
        init();
    } else {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ !== 'undefined') {
                    init();
                } else {
                    setTimeout(function() {
                        if (typeof $ !== 'undefined') {
                            init();
                        }
                    }, 1000);
                }
            });
        } else {
            setTimeout(function() {
                if (typeof $ !== 'undefined') {
                    init();
                }
            }, 1000);
        }
    }
})();
