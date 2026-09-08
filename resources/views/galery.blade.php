@extends('layouts.app')

@section('title', $item['title'])

@section('content')
<section class="page-hero">
    <div class="container">
        <a class="back-link" href="{{ route('home') }}">← На главную</a>
        <span class="kicker">galery</span>
        <h1>{{ $item['title'] }}</h1>
        <p class="lead">{{ $item['description'] }}</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <figure class="gallery-card">
            <img class="full-image" src="{{ asset($item['full_image']) }}" alt="{{ $item['title'] }}">
            <figcaption>{{ $item['date'] }} · {{ $item['tag'] }}</figcaption>
        </figure>
    </div>
</section>
@endsection
