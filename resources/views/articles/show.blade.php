@extends('layouts.app')

@section('title', $article->title)

@section('content')
<section class="page-hero">
    <div class="container narrow">

        <span class="kicker">
            {{ $article->created_at->format('d.m.Y') }}
        </span>

        <h1>{{ $article->title }}</h1>

        <p class="lead">
            {{ $article->description }}
        </p>

        <div class="article-actions">
            <a class="article-button secondary"
               href="{{ route('articles.index') }}">
                ← К списку
            </a>

            <a class="article-button"
               href="{{ route('articles.edit', $article) }}">
                Изменить
            </a>
        </div>

    </div>
</section>
@endsection