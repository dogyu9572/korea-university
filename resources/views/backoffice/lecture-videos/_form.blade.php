@php
    $isEdit = isset($lectureVideo) && $lectureVideo->exists;
    $attachments = $isEdit ? $lectureVideo->attachments : collect([]);
@endphp

<form action="{{ $isEdit ? route('backoffice.lecture-videos.update', $lectureVideo) : route('backoffice.lecture-videos.store') }}" method="POST" enctype="multipart/form-data" id="lectureVideoForm">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="member-form-section">
        <h3 class="member-section-title">강의 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">썸네일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control @error('thumbnail') is-invalid @enderror" name="thumbnail" accept="image/*">
                    @if ($isEdit && $lectureVideo->thumbnail_path)
                        <div class="mt-2 thumbnail-container">
                            <img src="{{ $lectureVideo->thumbnail_path }}" alt="썸네일" class="thumbnail-image">
                            <button type="button" class="btn-delete-simple delete-thumbnail-btn">삭제</button>
                            <input type="hidden" name="delete_thumbnail" id="delete_thumbnail" value="0">
                        </div>
                    @endif
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">제목</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $lectureVideo->title ?? '') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">시간</label>
                <div class="member-form-field">
                    <input type="number" class="board-form-control @error('lecture_time') is-invalid @enderror" name="lecture_time" value="{{ old('lecture_time', $lectureVideo->lecture_time ?? '') }}" min="1" required>
                    <span style="margin-left: 8px;">분</span>
                    @error('lecture_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">강사명</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('instructor_name') is-invalid @enderror" name="instructor_name" value="{{ old('instructor_name', $lectureVideo->instructor_name ?? '') }}" required>
                    @error('instructor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">동영상 링크</label>
                <div class="member-form-field">
                    <input type="url" class="board-form-control @error('video_url') is-invalid @enderror" name="video_url" value="{{ old('video_url', $lectureVideo->video_url ?? '') }}">
                    @error('video_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">첨부파일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control" name="attachments[]" multiple>
                    @if ($attachments->count() > 0)
                        <div class="mt-3">
                            <span>첨부파일: </span>
                            @foreach ($attachments as $idx => $att)
                                <span>
                                    <a href="{{ $att->path }}" target="_blank">{{ $att->name }}</a>
                                    <button type="button" class="btn-delete-simple ms-2 delete-attachment-btn" data-attachment-id="{{ $att->id }}">삭제</button>
                                </span>
                                @if ($idx < $attachments->count() - 1), @endif
                            @endforeach
                            @foreach ($attachments as $att)
                                <input type="hidden" name="delete_attachments[]" id="delete_attachment_{{ $att->id }}" value="">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">강의사용여부</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="is_active_1" name="is_active" value="1" class="board-radio-input" @checked(old('is_active', $lectureVideo->is_active ?? true) == 1)>
                            <label for="is_active_1">Y</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="is_active_0" name="is_active" value="0" class="board-radio-input" @checked(old('is_active', $lectureVideo->is_active ?? false) == 0)>
                            <label for="is_active_0">N</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 저장</button>
        <a href="{{ route('backoffice.lecture-videos.index') }}" class="btn btn-secondary">취소</a>
    </div>
</form>
