<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    $contacts = [
        ['name' => 'Email', 'value' => 'student@example.com', 'href' => 'mailto:student@example.com'],
        ['name' => 'Telegram', 'value' => '@username', 'href' => 'https://t.me/username'],
        ['name' => 'GitHub', 'value' => 'github.com/username', 'href' => 'https://github.com/username'],
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');
