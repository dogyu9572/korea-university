/**
 * 강의 영상 관리 페이지 JavaScript
 */

(function() {
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
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initDeleteButtons();
            });
        } else {
            initDeleteButtons();
        }
    }

    init();
})();
