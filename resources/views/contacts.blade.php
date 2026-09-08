@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <span class="kicker">Контакты</span>
        <h1>Связаться</h1>
        <p class="lead">Если хотите предложить материал или просто написать — контакты ниже.</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        <div class="contacts-grid">
            @foreach ($contacts as $contact)
                <a class="contact-card" href="{{ $contact['href'] }}">
                    <span class="contact-name">{{ $contact['name'] }}</span>
                    <strong>{{ $contact['value'] }}</strong>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
