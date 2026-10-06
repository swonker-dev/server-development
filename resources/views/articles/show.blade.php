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

            @can('update', $article)
                <a class="article-button"
                   href="{{ route('articles.edit', $article) }}">
                    Изменить
                </a>
            @endcan

        </div>

    </div>
</section>


<section class="section">
    <div class="container narrow">

        <div class="content-card">
            <h2>Комментарии</h2>

            @can('create-comment')

                <form
                    action="{{ route('comments.store', $article) }}"
                    method="POST"
                >
                    @csrf

                    <div class="form-group">
                        <label for="body">
                            Новый комментарий
                        </label>

                        <textarea
                            id="body"
                            name="body"
                            rows="4"
                            placeholder="Напишите комментарий..."
                        >{{ old('body') }}</textarea>

                        @error('body')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button class="form-button"
                            type="submit">
                        Добавить комментарий
                    </button>
                </form>

            @endcan
        </div>


        @forelse ($article->comments as $comment)

            <div class="content-card">

                <span class="kicker">
                    {{ $comment->user->name }}
                </span>

                <p>
                    {{ $comment->body }}
                </p>

                @can('delete-comment')

                    <form
                        action="{{ route('comments.destroy', $comment) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            class="article-button danger"
                            type="submit"
                        >
                            Удалить комментарий
                        </button>

                    </form>

                @endcan

            </div>

        @empty

            <div class="content-card">
                <p>Комментариев пока нет.</p>
            </div>

        @endforelse

    </div>
</section>

@endsection