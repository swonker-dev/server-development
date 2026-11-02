<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'night notes.')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/js/app.js')
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

            @can('create', \App\Article::class)

                <a class="{{ request()->routeIs('articles.create') ? 'active' : '' }}"
                   href="{{ route('articles.create') }}">
                    Создать новость
                </a>
            
            @endcan

            <a class="{{ request()->routeIs('articles.*') ? 'active' : '' }}"
               href="{{ route('articles.index') }}">
                Новости
            </a>

            @can('moderate-comments')
                <a class="{{ request()->routeIs('comments.*') ? 'active' : '' }}"
                   href="{{ route('comments.moderation') }}">
                    Модерация
                </a>
            @endcan

            @if (auth()->user()->role?->name === 'reader')
                @php($unreadNotifications = auth()->user()->unreadNotifications)
                <details class="notification-menu">
                    <summary>
                        Уведомления
                        <span class="notification-count">{{ $unreadNotifications->count() }}</span>
                    </summary>
                    <div class="notification-dropdown">
                        <p class="notification-heading">Новые статьи</p>
                        @forelse ($unreadNotifications as $notification)
                            <form action="{{ route('notifications.open', $notification->id) }}" method="POST">
                                @csrf
                                <button class="notification-link" type="submit">
                                    {{ $notification->data['title'] }}
                                    <time datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->format('d.m.Y H:i') }}</time>
                                </button>
                            </form>
                        @empty
                            <p class="notification-empty">Непрочитанных уведомлений нет.</p>
                        @endforelse
                    </div>
                </details>
            @endif

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

<div id="article-notifications"></div>

<footer class="site-footer">
    <div class="container footer-inner">
        <span>night notes. · 2026</span>
        <span><strong>Орешкин Ярослав Сергеевич</strong> · <strong>251-3210</strong></span>
    </div>
</footer>
</body>
</html>
