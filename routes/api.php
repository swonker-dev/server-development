<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json($request->user()->load('role'));
        })->name('user');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('articles', ArticleController::class)
            ->middlewareFor('show', 'track.article.views');
    });
});
