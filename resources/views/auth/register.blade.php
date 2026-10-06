@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Аккаунт</span>
        <h1>Регистрация</h1>
        <p class="lead">
            Создайте аккаунт, чтобы получить доступ к новостям.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        <form class="auth-form"
              action="{{ route('register.store') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="name">Имя</label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ваше имя"
                >

                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="example@mail.com"
                >

                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Минимум 6 символов"
                >

                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button class="form-button" type="submit">
                Зарегистрироваться
            </button>

        </form>

    </div>
</section>
@endsection