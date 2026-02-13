@extends('layouts.app')
@section('content')
<main class="sub_wrap inner">
    <div class="education_wrap">
        {!! preg_replace('/style=(["\'])(?:(?!\1).)*?\1/i', '', $education_guide ?? '') !!}
    </div>
</main>
@endsection
