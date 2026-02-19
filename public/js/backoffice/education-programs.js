/**
 * 교육 프로그램 관리 페이지 JavaScript
 */

(function() {
    // Summernote 에디터 초기화
    function initSummernote() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
            return;
        }

        const $editors = $('.summernote-editor');
        if ($editors.length === 0) {
            return;
        }

        $editors.each(function() {
            const $editor = $(this);
            
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
                styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre'],
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0], this);
                    }
                }
            });
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
        $('.summernote-editor').each(function() {
            const $editor = $(this);
            if ($editor.data('summernote')) {
                try {
                    const code = $editor.summernote('code');
                    $editor.val(code != null ? code : '');
                } catch (e) {
                    console.error('Summernote 동기화 실패:', e);
                }
            }
        });
    }

    // 참가비/환불규정 금액 필드 - 소수점 제거
    function initFeeInputs() {
        const feeInputs = document.querySelectorAll('input[name^="fee_"], input[name^="refund_"][name$="_fee"]');
        feeInputs.forEach(function(input) {
            // 표시 시 정수로 변환
            if (input.value && parseFloat(input.value) % 1 === 0) {
                input.value = parseInt(input.value);
            }
            
            // 입력 시 정수로 변환
            input.addEventListener('input', function() {
                const value = this.value.replace(/[^0-9]/g, '');
                if (value !== '') {
                    this.value = parseInt(value);
                } else {
                    this.value = '';
                }
            });
            
            // 포커스 아웃 시 정수로 변환
            input.addEventListener('blur', function() {
                if (this.value && !isNaN(this.value)) {
                    this.value = parseInt(this.value) || '';
                }
            });
        });
    }

    // 첨부파일/썸네일 삭제 버튼 처리
    function initDeleteButtons() {
        // 첨부파일 삭제
        document.querySelectorAll('.delete-attachment-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const attachmentId = this.getAttribute('data-attachment-id');
                const deleteInput = document.getElementById('delete_attachment_' + attachmentId);
                const attachmentItem = this.closest('span');
                
                if (confirm('첨부파일을 삭제하시겠습니까?')) {
                    if (deleteInput) {
                        deleteInput.value = attachmentId;
                    }
                    attachmentItem.style.display = 'none';
                }
            });
        });
        
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
                initFeeInputs();
                initDeleteButtons();
            });
        } else {
            setTimeout(initSummernote, 500);
            initFeeInputs();
            initDeleteButtons();
        }

        // 폼 제출 전 Summernote 동기화
        $('#educationProgramForm, #onlineEducationForm').on('submit', function() {
            syncSummernoteBeforeSubmit();
        });
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
