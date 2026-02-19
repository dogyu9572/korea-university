/**
 * 교육 안내 관리 페이지 JavaScript
 */

(function() {
    const EDITOR_IDS = [
        'education_guide',
        'certification_guide',
        'expert_level_1',
        'expert_level_2',
        'seminar_guide',
        'overseas_training_guide'
    ];

    function initSummernote() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
            return;
        }

        $('.summernote-editor').each(function() {
            const $ta = $(this);
            if ($ta.data('summernote')) {
                return;
            }
            $ta.summernote({
                height: 300,
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

    function syncEditorsToForm() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') {
            return;
        }
        EDITOR_IDS.forEach(function(id) {
            const $el = $('#' + id);
            if ($el.length && typeof $el.summernote === 'function') {
                try {
                    const code = $el.summernote('code');
                    $el.val(code != null ? code : '');
                } catch (e) {}
            }
        });
    }

    function onFormSubmit(e) {
        syncEditorsToForm();
    }

    function init() {
        initSummernote();
        const $form = document.getElementById('educationForm');
        if ($form) {
            $form.addEventListener('submit', onFormSubmit);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
