<div class="box">
	<div class="imgfit"><img src="{{ $item->card_data['thumb'] }}" alt=""></div>
	<div class="txt">
		<div class="tit"><span class="type {{ $item->card_data['type_class'] }}">{{ $item->type ?? '세미나' }}</span>{{ $item->card_data['name'] }}</div>
		<div class="info">
			<dl class="i1">
				<dt>정원</dt>
				<dd>{!! $item->card_data['has_remain'] ? '<strong class="c_iden">' . $item->card_data['enrolled'] . '명</strong>' : '<strong>' . $item->card_data['enrolled'] . '명</strong>' !!} / {{ $item->card_data['capacity_text'] }}</dd>
			</dl>
			<dl class="i2">
				<dt>교육대상</dt>
				<dd>{{ $item->card_data['target'] }}</dd>
			</dl>
			<dl class="i3">
				<dt>신청기간</dt>
				<dd>{{ $item->card_data['app_period'] ?: '-' }}</dd>
			</dl>
			<dl class="i4">
				<dt>교육기간</dt>
				<dd>{{ $item->card_data['period_text'] }}</dd>
			</dl>
			<dl class="i5">
				<dt>교육구분</dt>
				<dd>{{ $item->type ?? '세미나' }}</dd>
			</dl>
			<dl class="i6">
				<dt>교육장소</dt>
				<dd>{{ $item->card_data['location'] }}</dd>
			</dl>
			@if($item->card_data['fee_text'])
			<dl class="i7">
				<dt>참가비</dt>
				<dd>{{ $item->card_data['fee_text'] }}</dd>
			</dl>
			@endif
		</div>
		<div class="btns_tal">
			<a href="{{ $item->card_data['btn']['url'] }}" class="{{ $item->card_data['btn']['class'] }}">{{ $item->card_data['btn']['text'] }}</a>
			<a href="{{ $item->card_data['view_url'] }}" class="btn btn_link btn_bwb">상세보기</a>
		</div>
	</div>
</div>
