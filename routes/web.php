<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    $contacts = [
        ['name' => 'Email', 'value' => 'shadowir265@gmail.com', 'href' => 'mailto:shadowir265@gmail.com'],
        ['name' => 'Telegram', 'value' => '@swonker', 'href' => 'https://t.me/swonker'],
        ['name' => 'GitHub', 'value' => 'github.com/swonker-dev', 'href' => 'https://github.com/swonker-dev'],
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');

Route::get('/galery/{id}', [MainController::class, 'galery'])
    ->whereNumber('id')
    ->name('galery');

Route::get('/signin', [AuthController::class, 'create'])
    ->name('signin');

Route::post('/signin', [AuthController::class, 'registration'])
    ->name('signin.registration');

    Route::resource('articles', ArticleController::class);
