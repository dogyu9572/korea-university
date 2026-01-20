<!-- 학교 검색 -->
<div class="popup" id="searchSchool">
	<div class="dm" onclick="layerHide('searchSchool')"></div>
	<div class="inbox">
		<button type="button" class="btn_close" onclick="layerHide('searchSchool')">닫기</button>
		<div class="tit">학교 검색</div>
		<div class="search_area">
			<input type="text" class="w100p" placeholder="학교명을 검색해주세요.">
			<button type="button" class="btn">검색</button>
		</div>
		<div class="search_list">
			<label class="check"><input type="checkbox"><i></i><span>전국대학교</span></label>
			<label class="check"><input type="checkbox"><i></i><span>전국대학교</span></label>
			<label class="check"><input type="checkbox"><i></i><span>전국대학교</span></label>
			<label class="check"><input type="checkbox"><i></i><span>전국대학교</span></label>
			<label class="check"><input type="checkbox"><i></i><span>전국대학교</span></label>
		</div>
		<div class="btns_tac">
			<button type="button" class="btn btn_wbb">학교 등록</button>
		</div>
	</div>
</div>

<script>
//팝업
function layerShow(id) {
	$("#" + id).fadeIn(300);
}
function layerHide(id) {
	$("#" + id).fadeOut(300);
}

$("#searchSchool .btn_bwb").on("click", function () {
	let schools = [];
	$("#searchSchool .search_list input[type='checkbox']:checked").each(function () {
		schools.push($(this).siblings("span").text().trim());
	});
	if (schools.length) {
		$(".input_school").val(schools.join(", "));
	}
	layerHide("searchSchool");
});
</script>
