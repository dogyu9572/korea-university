@extends('layouts.app')
@section('content')
<main class="sub_wrap pb0">
    
	<div class="inner">
		@if($chartContent)
			<div class="organizational_chart_content">
				{!! $chartContent !!}
			</div>
		@else
			<div class="organizational_chart">
				<div class="general">총회</div>
				<div class="side right">
					<div class="box">감사</div>
				</div>
				<div class="middle box dotc">이사회</div>
				<div class="head box dotc">회장</div>
				<div class="side left">
					<div class="box">고문</div>
				</div>
				<div class="btm">
					<div class="box">사무국</div>
				</div>
			</div>
		@endif
	</div>
	
	<div class="organizational_list gbox">
		<div class="inner">
			<div class="box">
				@if($membersByCategory && $membersByCategory->count() > 0)
					@foreach(['회장', '부회장', '사무국', '지회', '감사', '고문'] as $category)
						@if($membersByCategory->has($category) && $membersByCategory[$category]->count() > 0)
							<div class="btit">{{ $category }}</div>
							<ul>
								@foreach($membersByCategory[$category] as $member)
									<li>
										<div class="names">
											<span>{{ $member->position ?: $category }}</span>
											<div class="name">{{ $member->name }}</div>
										</div>
										<div class="info">
											@if($member->affiliation)
												<p class="i1"><strong>소속기관</strong>{{ $member->affiliation }}</p>
											@endif
											@if($member->phone)
												<p class="i2"><strong>연락처</strong>{{ $member->phone }}</p>
											@endif
										</div>
									</li>
								@endforeach
							</ul>
						@endif
					@endforeach
				@else
					<div class="empty-state">
						<p>등록된 구성원이 없습니다.</p>
					</div>
				@endif
			</div>
		</div>
	</div>
	
</main>
@endsection