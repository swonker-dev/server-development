@extends('layouts.app')

@section('title', 'О нас')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">О нас</span>
        <h1>О проекте night notes.</h1>
        <p class="lead">
            Это небольшой визуальный дневник о городской эстетике, музыке и пространствах,
            в которых обычные детали начинают ощущаться немного иначе.
        </p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        <div class="content-card">
            <h2>Идея</h2>
            <p>
                В основе проекта — фотографии, связанные с вечерним светом, архитектурой,
                дорогой и музыкой. Здесь нет строгой темы: важнее настроение самого кадра.
            </p>
        </div>
        <div class="content-card">
            <h2>Что здесь можно найти</h2>
            <ul>
                <li>городские пейзажи и архитектуру;</li>
                <li>ночные улицы и свет;</li>
                <li>музыкальную и студийную атмосферу.</li>
            </ul>
        </div>
    </div>
</section>
@endsection
