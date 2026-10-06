<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'night notes.')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="{{ route('home') }}">night notes.</a>
        <nav class="nav">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Главная</a>
            <a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">О нас</a>
            <a class="{{ request()->routeIs('contacts') ? 'active' : '' }}" href="{{ route('contacts') }}">Контакты</a>
            @auth

            <a class="{{ request()->routeIs('articles.*') ? 'active' : '' }}"
               href="{{ route('articles.index') }}">
                Новости
            </a>

            <span class="nav-user">
                {{ auth()->user()->name }}
            </span>

            <form class="nav-logout"
                  action="{{ route('logout') }}"
                  method="POST">

                @csrf

                <button type="submit">
                    Выйти
                </button>

            </form>

        @else

            <a class="{{ request()->routeIs('register*') ? 'active' : '' }}"
               href="{{ route('register') }}">
                Регистрация
            </a>

            <a class="{{ request()->routeIs('login*') ? 'active' : '' }}"
               href="{{ route('login') }}">
                Войти
            </a>

        @endauth
        </nav>
    </div>
</header>

<main>@yield('content')</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <span>night notes. · 2026</span>
        <span><strong>Орешкин Ярослав Сергеевич</strong> · <strong>251-3210</strong></span>
    </div>
</footer>
</body>
</html>
