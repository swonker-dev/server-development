@extends('layouts.app')

@section('title', 'Новости')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Новости</span>

        <h1>Последние записи</h1>

        <p class="lead">
            Небольшая подборка заметок и историй.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        @forelse ($articles as $article)

            <article class="content-card">
                <span class="kicker">
                    {{ $article->created_at->format('d.m.Y') }}
                </span>

                <h2>{{ $article->title }}</h2>

                <p>
                    {{ $article->description }}
                </p>
            </article>

        @empty

            <div class="content-card">
                <p>Новостей пока нет.</p>
            </div>

        @endforelse

    </div>
</section>
@endsection