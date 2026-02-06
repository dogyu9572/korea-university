@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="education_wrap">
        {!! $education_guide ?? '' !!}
    </div>
</main>
@endsection
