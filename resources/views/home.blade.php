@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="kicker">selected frames</span>
            <h1>night notes.</h1>
            <p class="lead">
                Три визуальные заметки: вечерний город, ночная архитектура и студийная атмосфера.
            </p>
        </div>
        <div class="hero-note">
            <p>Откройте любой кадр, чтобы посмотреть его в полном размере.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="kicker">archive</span>
                <h2>Последние записи</h2>
            </div>
            <span class="counter">{{ count($news) }} материала</span>
        </div>

        @forelse ($news as $item)
            @if ($loop->first)<div class="cards-grid">@endif
                <article class="news-card">
                    <a class="preview-link" href="{{ route('galery', ['id' => $item['id']]) }}">
                        <img class="preview-image" src="{{ asset($item['preview_image']) }}" alt="{{ $item['title'] }}">
                    </a>
                    <div class="news-content">
                        <div class="meta">{{ $item['date'] }} · {{ $item['tag'] }}</div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['description'] }}</p>
                        <a class="text-link" href="{{ route('galery', ['id' => $item['id']]) }}">Открыть →</a>
                    </div>
                </article>
            @if ($loop->last)</div>@endif
        @empty
            <div class="empty-state">Нет записей.</div>
        @endforelse
    </div>
</section>
@endsection
