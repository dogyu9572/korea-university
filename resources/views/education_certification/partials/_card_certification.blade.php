<div class="box">
	<div class="imgfit"><img src="{{ $item->card_data['thumb'] }}" alt=""></div>
	<div class="txt">
		<div class="tit"><span class="type c3">자격증</span>{{ $item->card_data['name'] }}</div>
		<div class="info">
			<dl class="i1">
				<dt>응시자격</dt>
				<dd>{{ $item->card_data['eligibility'] }}</dd>
			</dl>
			<dl class="i3">
				<dt>접수기간</dt>
				<dd>{{ $item->card_data['app_period'] ?: '-' }}</dd>
			</dl>
			<dl class="i4">
				<dt>시험일</dt>
				<dd>{{ $item->card_data['exam_date'] ?: '-' }}</dd>
			</dl>
		</div>
		<div class="btns_tal">
			<a href="{{ $item->card_data['btn']['url'] }}" class="{{ $item->card_data['btn']['class'] }}">{{ $item->card_data['btn']['text'] }}</a>
			<a href="{{ $item->card_data['view_url'] }}" class="btn btn_link btn_bwb">상세보기</a>
		</div>
	</div>
</div>
