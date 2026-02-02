@extends('layouts.app')
@section('content')
<main class="sub_wrap pb0">
    
	<div class="inner">
		<div class="organizational_chart">
			{!! $chartContent !!}
		</div>
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