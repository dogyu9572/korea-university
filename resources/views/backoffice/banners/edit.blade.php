@extends('backoffice.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/banners.css') }}">
@endsection

@section('title', '배너 수정')

@section('content')
<div class="board-container">
    <div class="board-header">      
        <a href="{{ route('backoffice.banners.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="board-alert board-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="board-card">
                <div class="board-card-body">
                    <form action="{{ route('backoffice.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- 이미지 제거를 위한 숨겨진 필드 -->
                        <input type="hidden" name="remove_image" id="remove_image" value="0">

                        <!-- 배너명 -->
                        <div class="board-form-group">
                            <label for="title" class="board-form-label">배너명 <span class="required">*</span></label>
                            <input type="text" class="board-form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $banner->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 1. 배너 이미지 -->
                        <div class="board-form-group">
                            <label for="image" class="board-form-label">1. 배너 이미지</label>
                            <div class="board-file-upload">
                                <div class="board-file-input-wrapper">
                                    <input type="file" class="board-file-input" 
                                           id="image" name="image" accept=".jpg,.jpeg,.png">
                                    <div class="board-file-input-content">
                                        <i class="fas fa-image"></i>
                                        <span class="board-file-input-text">파일 첨부</span>
                                        <span class="board-file-input-subtext">JPG, PNG 형식 업로드 가능</span>
                                    </div>
                                </div>
                                <div class="board-file-preview" id="imagePreview">
                                    @if($banner->desktop_image)
                                        <img src="{{ asset('storage/' . $banner->desktop_image) }}" alt="현재 배너 이미지" class="thumbnail-preview">
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImagePreview('image', 'imagePreview')">
                                            <i class="fas fa-trash"></i> 배너 이미지 제거
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <small class="board-form-text">※배너 이미지는 0000*0000 SIZE, JPG/PNG 형식 업로드 가능</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. 클릭시 이동 링크(URL) -->
                        <div class="board-form-group">
                            <label for="url" class="board-form-label">2. 클릭시 이동 링크(URL)</label>
                            <input type="url" class="board-form-control @error('url') is-invalid @enderror" 
                                   id="url" name="url" value="{{ old('url', $banner->url) }}" placeholder="https://example.com">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 3. 현재창/새창 -->
                        <div class="board-form-group">
                            <label class="board-form-label">3. 현재창/새창</label>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="url_target_self" name="url_target" value="_self" class="board-radio-input" @checked(old('url_target', $banner->url_target ?? '_self') == '_self')>
                                    <label for="url_target_self">현재창</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="url_target_blank" name="url_target" value="_blank" class="board-radio-input" @checked(old('url_target', $banner->url_target) == '_blank')>
                                    <label for="url_target_blank">새창</label>
                                </div>
                            </div>
                        </div>

                        <!-- 4. 정렬 -->
                        <div class="board-form-group">
                            <label for="sort_order" class="board-form-label">4. 정렬</label>
                            <input type="number" class="board-form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                            <small class="board-form-text">※숫자가 높을수록 앞쪽에 정렬</small>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 5. 노출여부 -->
                        <div class="board-form-group">
                            <label class="board-form-label">5. 노출여부</label>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="is_active_1" name="is_active" value="1" class="board-radio-input" @checked(old('is_active', $banner->is_active) == '1')>
                                    <label for="is_active_1">Y</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="is_active_0" name="is_active" value="0" class="board-radio-input" @checked(old('is_active', $banner->is_active) == '0')>
                                    <label for="is_active_0">N</label>
                                </div>
                            </div>
                        </div>

                        <!-- 등록일 -->
                        <div class="board-form-group">
                            <label for="created_at" class="board-form-label">등록일</label>
                            <input type="date" class="board-form-control @error('created_at') is-invalid @enderror" 
                                   id="created_at" name="created_at" value="{{ old('created_at', $banner->created_at ? $banner->created_at->format('Y-m-d') : date('Y-m-d')) }}">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 작성자 -->
                        <div class="board-form-group">
                            <label class="board-form-label">작성자</label>
                            <input type="text" class="board-form-control" value="{{ auth()->user()->name ?? '관리자' }}" readonly>
                        </div>

                        <div class="board-form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 저장
                            </button>
                            <a href="{{ route('backoffice.banners.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> 목록
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/banners.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const existingPreview = imagePreview.querySelector('.thumbnail-preview');
                    if (existingPreview) {
                        existingPreview.src = e.target.result;
                    } else {
                        imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="미리보기" class="thumbnail-preview">';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function removeImagePreview(inputId, previewId) {
    document.getElementById(inputId).value = '';
    document.getElementById('remove_image').value = '1';
    document.getElementById(previewId).innerHTML = '';
}
</script>
@endsection
