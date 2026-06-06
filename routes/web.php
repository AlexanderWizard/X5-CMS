<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Алиас для auth-редиректов (middleware auth:docs ищет именованный маршрут 'login')
Route::get('/login', fn () => redirect()->route('filament.docs.auth.login'))->name('login');
