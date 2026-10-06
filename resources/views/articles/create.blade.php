@extends('layouts.app')

@section('title', 'Новая статья')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Новости</span>
        <h1>Новая статья</h1>
        <p class="lead">
            Заполните поля ниже.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        <form class="content-card"
              action="{{ route('articles.store') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="title">Заголовок</label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Название статьи"
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
                    placeholder="Текст статьи"
                >{{ old('description') }}</textarea>

                @error('description')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            <button class="form-button" type="submit">
                Создать статью
            </button>

        </form>

    </div>
</section>
@endsection