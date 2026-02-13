@extends('backoffice.layouts.app')

@section('title', '회원 문의(상세)')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/members.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/education-programs.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success board-hidden-alert">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger board-hidden-alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.inquiries.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <!-- 문의 내용 영역 (읽기 전용) -->
            <div class="member-form-section">
                <h3 class="member-section-title">회원 문의(상세)</h3>
                
                <div class="member-form-list">
                    <div class="member-form-row">
                        <label class="member-form-label">분류</label>
                        <div class="member-form-field">
                            <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; width: 100%; max-width: none !important;">
                                {{ $inquiry->category }}
                            </div>
                        </div>
                    </div>

                    <div class="member-form-row">
                        <label class="member-form-label">제목</label>
                        <div class="member-form-field">
                            <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; width: 100%; max-width: none !important;">
                                {{ $inquiry->title }}
                            </div>
                        </div>
                    </div>

                    <div class="member-form-row">
                        <label class="member-form-label">내용</label>
                        <div class="member-form-field">
                            <div class="board-form-control" style="background-color: #f8f9fa; padding: 15px; min-height: 200px; border: 1px solid #dee2e6; width: 100%; max-width: none !important;">
                                {!! nl2br(e($inquiry->content)) !!}
                            </div>
                        </div>
                    </div>

                    @if($inquiry->files && $inquiry->files->count() > 0)
                    <div class="member-form-row">
                        <label class="member-form-label">첨부파일</label>
                        <div class="member-form-field">
                            <span>첨부파일: </span>
                            @foreach($inquiry->files as $index => $file)
                                <a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->file_name }}" target="_blank">{{ $file->file_name }}</a>@if($index < $inquiry->files->count() - 1), @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="member-form-row">
                        <label class="member-form-label">작성자</label>
                        <div class="member-form-field">
                            <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; width: 100%; max-width: none !important;">
                                {{ $inquiry->member->name ?? '자동입력' }}
                            </div>
                        </div>
                    </div>

                    <div class="member-form-row">
                        <label class="member-form-label">등록일</label>
                        <div class="member-form-field">
                            <div class="board-form-control" style="background-color: #f8f9fa; padding: 8px 12px; width: 100%; max-width: none !important;">
                                {{ $inquiry->created_at->format('Y.m.d') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 답변 영역 -->
            <div class="member-form-section">
                <h3 class="member-section-title">답변</h3>
                
                <form action="{{ route('backoffice.inquiries.reply', $inquiry->id) }}" method="POST" id="replyForm" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="member-form-list">
                        <div class="member-form-row">
                            <label class="member-form-label">작성자 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <input type="text" class="board-form-control @error('author') is-invalid @enderror" 
                                       id="author" name="author" value="{{ old('author', $reply ? $reply->author : (auth()->user() ? auth()->user()->name : '')) }}" 
                                       style="max-width: none !important;" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">답변상태 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <div class="board-radio-group">
                                    <div class="board-radio-item">
                                        <input type="radio" id="status_completed" name="status" value="답변완료" class="board-radio-input" 
                                               @checked(old('status', $reply ? $reply->status : '답변대기') == '답변완료') required>
                                        <label for="status_completed">답변완료</label>
                                    </div>
                                    <div class="board-radio-item">
                                        <input type="radio" id="status_pending" name="status" value="답변대기" class="board-radio-input" 
                                               @checked(old('status', $reply ? $reply->status : '답변대기') == '답변대기')>
                                        <label for="status_pending">답변대기</label>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">등록일</label>
                            <div class="member-form-field">
                                <input type="date" class="board-form-control @error('reply_date') is-invalid @enderror" 
                                       id="reply_date" name="reply_date" 
                                       value="{{ old('reply_date', $reply && $reply->reply_date ? $reply->reply_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                       style="max-width: none !important;">
                                @error('reply_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">첨부파일</label>
                            <div class="member-form-field">
                                <input type="file" class="board-form-control" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar"
                                       style="max-width: none !important;">
                                <small class="board-form-text">파일은 최대 3개까지 가능하고, 1개당 최대 10MB를 초과할 수 없습니다.</small>
                                
                                @if($reply && $reply->files && $reply->files->count() > 0)
                                <div class="mt-3">
                                    <span>첨부파일: </span>
                                    @foreach($reply->files as $index => $file)
                                        <span>
                                            <a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->file_name }}" target="_blank">{{ $file->file_name }}</a>
                                            <button type="button" class="btn-delete-simple ms-2 delete-reply-file-btn" data-file-id="{{ $file->id }}">
                                                삭제
                                            </button>
                                        </span>
                                        @if($index < $reply->files->count() - 1), @endif
                                    @endforeach
                                    @foreach($reply->files as $file)
                                        <input type="hidden" name="delete_reply_files[]" id="delete_reply_file_{{ $file->id }}" value="">
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="member-form-row">
                            <label class="member-form-label">내용 <span class="required">*</span></label>
                            <div class="member-form-field">
                                <textarea class="board-form-control summernote-editor @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="15" required
                                          data-original-content="{{ old('content', $reply ? htmlspecialchars($reply->content, ENT_QUOTES, 'UTF-8') : '') }}">{{ old('content', $reply ? $reply->content : '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="board-form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <a href="{{ route('backoffice.inquiries.index') }}" class="btn btn-secondary">취소</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('js/backoffice/board-post-form.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const replyForm = document.getElementById('replyForm');
    const formSubmitBtn = replyForm ? replyForm.querySelector('button[type="submit"]') : null;
    
    console.log('문의 상세 폼 초기화');
    console.log('Form:', replyForm);
    console.log('Form submit button:', formSubmitBtn);
    
    // 답변상태 기본값 설정
    if (!$('input[name="status"]:checked').length) {
        $('#status_pending').prop('checked', true);
    }
    
    // form 제출 이벤트 리스너 (다른 게시판과 동일한 방식)
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            // Summernote 에디터 내용을 textarea에 동기화
            if (typeof jQuery !== 'undefined' && typeof $('#content').summernote !== 'undefined') {
                const content = $('#content').summernote('code') || '';
                // Summernote가 빈 값이면 textarea의 원본 값 확인 (기존 답변 내용)
                if (!content || content.trim() === '' || content === '<p><br></p>' || content === '<p></p>') {
                    const originalContent = $('#content').data('original-content') || $('#content').attr('data-original') || '';
                    if (originalContent) {
                        $('#content').val(originalContent);
                    } else {
                        $('#content').val('');
                    }
                } else {
                    $('#content').val(content);
                }
            }
            
            // 필수 필드 검증
            const content = $('#content').val() || '';
            const cleanContent = content.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
            
            if (!cleanContent) {
                e.preventDefault();
                alert('답변 내용을 입력해주세요.');
                if (typeof jQuery !== 'undefined' && typeof $('#content').summernote !== 'undefined') {
                    $('#content').summernote('focus');
                } else {
                    $('#content').focus();
                }
                return false;
            }
            
            // 답변상태 기본값 설정
            if (!$('input[name="status"]:checked').length) {
                $('#status_pending').prop('checked', true);
            }
            
            // 작성자 필드 검증
            const author = $('#author').val() || '';
            if (!author.trim()) {
                e.preventDefault();
                alert('작성자를 입력해주세요.');
                $('#author').focus();
                return false;
            }
            
            // 검증 통과 시 폼 제출 진행 (다른 게시판과 동일)
        });
    }
    
    // 답변 첨부파일 삭제 버튼 처리
    document.querySelectorAll('.delete-reply-file-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const fileId = this.getAttribute('data-file-id');
            const deleteInput = document.getElementById('delete_reply_file_' + fileId);
            const fileItem = this.closest('span');
            
            if (confirm('첨부파일을 삭제하시겠습니까?')) {
                if (deleteInput) {
                    deleteInput.value = fileId;
                }
                fileItem.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
