@extends('layouts.app')
@section('content')
<main class="print_inbox">
	@isset($application)
		@include('print.partials.certificate_completion_finish_body', [
			'certificateType' => '이수증',
			'hoursLabel' => '이수시간',
		])
	@else
	<div class="print_area">
		<p class="ta_c">유효한 증빙이 없습니다.</p>
	</div>
	@endisset
</main>

@include('print.partials.scripts')

@endsection
