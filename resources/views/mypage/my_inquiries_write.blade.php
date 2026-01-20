@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<div class="otit mt0">문의내용 작성 <p class="abso"><strong class="c_blue_light">*</strong>는 필수입력 항목 입니다.</p></div>

	<div class="glbox dl_slice in_inputs board_write">
		<dl>
			<dt>분류<strong class="c_blue_light">*</strong></dt>
			<dd class="flex radios">
				<label class="radio"><input type="radio" name="type"><i></i>교육</label>
				<label class="radio"><input type="radio" name="type"><i></i>자격증</label>
				<label class="radio"><input type="radio" name="type"><i></i>세미나</label>
				<label class="radio"><input type="radio" name="type"><i></i>해외연수</label>
				<label class="radio"><input type="radio" name="type"><i></i>기타</label>
			</dd>
		</dl>
		<dl>
			<dt>문의 제목<strong class="c_blue_light">*</strong></dt>
			<dd><input type="text" class="w100p" placeholder="문의 제목을 입력해주세요."></dd>
		</dl>
		<dl>
			<dt>문의 내용<strong class="c_blue_light">*</strong></dt>
			<dd><textarea name="" id="" cols="30" rows="10" class="w100p" placeholder="문의 내용을 입력해주세요."></textarea></dd>
		</dl>
		<dl>
			<dt>파일첨부</dt>
			<dd class="file_inputs">
				<label class="file"><input type="file" multiple><span>파일선택</span></label>
				<p class="ne mt0">파일은 최대 3개까지 가능하고, 1개당 최대 10MB를 초과할 수 없습니다.</p>
				<div class="file_input w100p flex colm">선택된 파일 없음</div>
			</dd>
		</dl>
	</div>

	<div class="btns_tac">
		<a href="/mypage/my_inquiries" class="btn btn_bwb">목록</a>
		<button type="button" class="btn btn_wbb">등록하기</button>
	</div>
	
</main>

<script>
const MAX_FILES = 3;
const MAX_SIZE = 10 * 1024 * 1024; // 10MB

$(document).on("change", ".file_inputs input[type='file']", function () {
	const $input = $(this);
	const $wrap = $input.closest(".file_inputs");
	const $fileInput = $wrap.find(".file_input");

	let files = Array.from(this.files);

	// 개수 제한
	if (files.length > MAX_FILES) {
		alert("파일은 최대 3개까지 선택할 수 있습니다.");
		$input.val("");
		return;
	}

	// 용량 제한
	for (let file of files) {
		if (file.size > MAX_SIZE) {
			alert(`"${file.name}" 파일은 10MB를 초과할 수 없습니다.`);
			$input.val("");
			return;
		}
	}

	// UI 갱신
	$fileInput.empty().addClass("w100p");

	if (files.length === 0) {
		$fileInput.text("선택된 파일 없음");
		return;
	}

	files.forEach((file, index) => {
		$fileInput.append(`
			<div class="file_item" data-index="${index}">
				<button type="button" class="btn_remove">${file.name}</button>
			</div>
		`);
	});
});

// 개별 파일 삭제
$(document).on("click", ".btn_remove", function () {
	const $item = $(this).closest(".file_item");
	const removeIndex = $item.data("index");
	const $wrap = $(this).closest(".file_inputs");
	const $input = $wrap.find("input[type='file']");
	const $fileInput = $wrap.find(".file_input");

	let files = Array.from($input[0].files);
	files.splice(removeIndex, 1);

	// FileList 재생성
	const dataTransfer = new DataTransfer();
	files.forEach(file => dataTransfer.items.add(file));
	$input[0].files = dataTransfer.files;

	// UI 재렌더링
	$fileInput.empty();
	if (files.length === 0) {
		$fileInput.text("선택된 파일 없음");
		return;
	}

	files.forEach((file, index) => {
		$fileInput.append(`
			<div class="file_item" data-index="${index}">
				<button type="button" class="btn_remove">${file.name}</button>
			</div>
		`);
	});
});
</script>

@endsection