@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Аккаунт</span>
        <h1>Вход</h1>

        <p class="lead">
            Войдите в свой аккаунт.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form class="auth-form"
              action="{{ route('login.store') }}"
              method="POST">

            @csrf

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
                    placeholder="Ваш пароль"
                >

                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button class="form-button" type="submit">
                Войти
            </button>

        </form>

    </div>
</section>
@endsection