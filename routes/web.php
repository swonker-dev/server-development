<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

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

    Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');


Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');


Route::middleware('auth:sanctum')->group(function () {

    Route::resource('articles', ArticleController::class);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::post(
        '/articles/{article}/comments',
        [CommentController::class, 'store']
    )->name('comments.store');
    
    
    Route::delete(
        '/comments/{comment}',
        [CommentController::class, 'destroy']
    )->name('comments.destroy');

});



