@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="kicker">visual diary</span>
            <h1>night notes.</h1>
            <p class="lead">
                Небольшой визуальный архив о музыке, вечернем городе и кадрах,
                которые хочется сохранить чуть дольше обычного.
            </p>
        </div>
        <div class="hero-note">
            <p>
                Иногда одно место, один кадр или одна песня запоминаются сильнее целого дня.
                Здесь собраны именно такие моменты.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        <div class="content-card">
            <h2>О сайте</h2>
            <p>
                night notes. — личный визуальный архив без строгих правил: вечерние улицы,
                архитектура, студийная атмосфера, музыка и детали, которые остаются где-то
                между фотографией и воспоминанием.
            </p>
        </div>
    </div>
</section>
@endsection
