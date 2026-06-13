<?php

use App\Modules\Cms\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Алиас для auth-редиректов (middleware auth:admin ищет именованный маршрут 'login')
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');

// Главная страница лендинга (CMS)
Route::get('/', [PageController::class, 'home'])->name('cms.home');

// Публичные страницы CMS по иерархическому slug-пути (about, services/web).
// Catch-all — регистрируется последним; исключает служебные префиксы admin/api/docs/login.
Route::get('/{path}', [PageController::class, 'show'])
    ->where('path', '^(?!admin|api|docs|login)[A-Za-z0-9\-_/]+$')
    ->name('cms.page');
