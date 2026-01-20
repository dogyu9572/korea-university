@extends('layouts.app')
@section('content')
<main class="sub_wrap terms_wrap">

	<div class="terms_title"><div class="inner">{{$sName}}</div></div>
    
	<div class="inner">
		@include('terms.txt_terms')
	</div>
	
</main>
@endsection