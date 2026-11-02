@extends('layouts.app')

@section('title', 'Модерация комментариев')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Комментарии</span>
        <h1>На проверке</h1>
        <p class="lead">Новые комментарии ожидают вашего решения. Принятые комментарии появятся на страницах статей.</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        @if (session('success'))
            <div class="success-message" role="status">{{ session('success') }}</div>
        @endif

        <div class="section-heading">
            <h2>Ожидают модерации</h2>
            <span class="counter">Всего: {{ $comments->total() }}</span>
        </div>

        @forelse ($comments as $comment)
            <article class="content-card">
                <span class="kicker">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                <h2>{{ $comment->user->name }}</h2>
                <p>
                    К статье
                    <a class="text-link" href="{{ route('articles.show', $comment->article) }}">{{ $comment->article->title }}</a>
                </p>
                <p class="comment-body">{{ $comment->body }}</p>

                <div class="article-actions">
                    <form action="{{ route('comments.approve', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="article-button" type="submit">Принять</button>
                    </form>

                    <form action="{{ route('comments.reject', $comment) }}" method="POST"
                          onsubmit="return confirm('Отклонить и удалить комментарий?')">
                        @csrf
                        @method('DELETE')
                        <button class="article-button danger" type="submit">Отклонить</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">Все комментарии проверены. Новых комментариев пока нет.</div>
        @endforelse

        @if ($comments->hasPages())
            <div class="pagination">
                @if ($comments->onFirstPage())
                    <span class="page-link disabled">← Назад</span>
                @else
                    <a class="page-link" href="{{ $comments->previousPageUrl() }}">← Назад</a>
                @endif

                <span class="page-info">Страница {{ $comments->currentPage() }} из {{ $comments->lastPage() }}</span>

                @if ($comments->hasMorePages())
                    <a class="page-link" href="{{ $comments->nextPageUrl() }}">Вперёд →</a>
                @else
                    <span class="page-link disabled">Вперёд →</span>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
