@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="otit mt0">문의내용 작성 <p class="abso"><strong class="c_blue_light">*</strong>는 필수입력 항목 입니다.</p></div>

	<form action="{{ route('mypage.my_inquiries.store') }}" method="POST" enctype="multipart/form-data" id="inquiryWriteForm">
		@csrf
		<div class="glbox dl_slice in_inputs board_write">
			<dl>
				<dt>분류<strong class="c_blue_light">*</strong></dt>
				<dd class="flex radios">
					@foreach($categories as $cat)
					<label class="radio"><input type="radio" name="category" value="{{ $cat }}" @checked(old('category') === $cat)><i></i>{{ $cat }}</label>
					@endforeach
				</dd>
				@error('category')<dd class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>문의 제목<strong class="c_blue_light">*</strong></dt>
				<dd><input type="text" name="title" class="w100p" placeholder="문의 제목을 입력해주세요." value="{{ old('title') }}"></dd>
				@error('title')<dd class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>문의 내용<strong class="c_blue_light">*</strong></dt>
				<dd><textarea name="content" cols="30" rows="10" class="w100p" placeholder="문의 내용을 입력해주세요.">{{ old('content') }}</textarea></dd>
				@error('content')<dd class="join_field_error" style="color:#c00;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</dd>@enderror
			</dl>
			<dl>
				<dt>파일첨부</dt>
				<dd class="file_inputs">
					<label class="file"><input type="file" name="files[]" multiple><span>파일선택</span></label>
					<p class="ne mt0">파일은 최대 3개까지 가능하고, 1개당 최대 10MB를 초과할 수 없습니다.</p>
					<div class="file_input w100p flex colm">선택된 파일 없음</div>
				</dd>
			</dl>
		</div>

		<div class="btns_tac">
			<a href="{{ route('mypage.my_inquiries') }}" class="btn btn_bwb">목록</a>
			<button type="submit" class="btn btn_wbb">등록하기</button>
		</div>
	</form>
	
</main>

@push('scripts')
<script src="{{ asset('js/mypage/inquiry-write.js') }}"></script>
@endpush

@endsection