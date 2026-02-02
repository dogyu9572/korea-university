@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    
	<ul class="round_tabs slim mb80">
		<li><a href="/about/history">연혁</a></li>
		<li class="on"><a href="/about/bylaws">정관</a></li>
	</ul>
	
	<div class="bylaws_wrap">
		{!! $bylawsContent !!}
	</div>
	
</main>
@endsection