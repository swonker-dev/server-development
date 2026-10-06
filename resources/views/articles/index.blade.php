@extends('layouts.app')

@section('title', 'Новости')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Новости</span>
        <h1>Последние записи</h1>
        <p class="lead">
            Здесь можно просматривать и управлять статьями.
        </p>

        <a class="article-button"
           href="{{ route('articles.create') }}">
            + Добавить статью
        </a>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($articles as $article)

            <article class="content-card">
                <span class="kicker">
                    {{ $article->created_at->format('d.m.Y') }}
                </span>

                <h2>{{ $article->title }}</h2>

                <p>{{ $article->description }}</p>

                <div class="article-actions">
                    <a class="article-button secondary"
                       href="{{ route('articles.show', $article) }}">
                        Открыть
                    </a>

                    <a class="article-button secondary"
                       href="{{ route('articles.edit', $article) }}">
                        Изменить
                    </a>

                    <form
                        action="{{ route('articles.destroy', $article) }}"
                        method="POST"
                        onsubmit="return confirm('Удалить статью?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="article-button danger"
                                type="submit">
                            Удалить
                        </button>
                    </form>
                </div>
            </article>

        @empty

            <div class="content-card">
                <p>Новостей пока нет.</p>
            </div>

        @endforelse


        @if ($articles->hasPages())
            <div class="pagination">

                @if ($articles->onFirstPage())
                    <span class="page-link disabled">
                        ← Назад
                    </span>
                @else
                    <a class="page-link"
                       href="{{ $articles->previousPageUrl() }}">
                        ← Назад
                    </a>
                @endif


                <span class="page-info">
                    Страница {{ $articles->currentPage() }}
                    из {{ $articles->lastPage() }}
                </span>


                @if ($articles->hasMorePages())
                    <a class="page-link"
                       href="{{ $articles->nextPageUrl() }}">
                        Вперёд →
                    </a>
                @else
                    <span class="page-link disabled">
                        Вперёд →
                    </span>
                @endif

            </div>
        @endif

    </div>
</section>
@endsection