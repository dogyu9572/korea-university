@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
		@include('print.partials.certificate_completion_finish_body', [
			'certificateType' => '수료증',
			'hoursLabel' => '수료시간',
		])
	@else
	<div class="print_area">
		<p class="ta_c">유효한 증빙이 없습니다.</p>
	</div>
	@endisset
</main>

@include('print.partials.scripts')

@endsection
