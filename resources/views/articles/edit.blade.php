@extends('layouts.app')

@section('title', 'Редактирование статьи')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Новости</span>
        <h1>Редактирование</h1>

        <p class="lead">
            Измените необходимые данные статьи.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        <form class="content-card"
              action="{{ route('articles.update', $article) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Заголовок</label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title', $article->title) }}"
                >

                @error('title')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            <div class="form-group">
                <label for="description">Описание</label>

                <textarea
                    id="description"
                    name="description"
                    rows="7"
                >{{ old('description', $article->description) }}</textarea>

                @error('description')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            <button class="form-button" type="submit">
                Сохранить изменения
            </button>

        </form>

    </div>
</section>
@endsection