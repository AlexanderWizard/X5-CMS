<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Алиас для auth-редиректов (middleware auth:admin ищет именованный маршрут 'login')
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');
