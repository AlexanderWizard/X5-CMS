<?php

use App\Http\Middleware\CheckMaintenance;
use App\Modules\Cms\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Алиас для auth-редиректов (middleware auth:admin ищет именованный маршрут 'login')
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');

// Публичный сайт CMS с языковым префиксом /{locale}/... (en|ru), под режимом обслуживания
Route::middleware(CheckMaintenance::class)
    ->prefix('{locale}')
    ->where(['locale' => 'en|ru'])
    ->group(function () {
        // Главная лендинга: /en, /ru
        Route::get('/', [PageController::class, 'home'])->name('cms.home');

        // Страницы по иерархическому slug-пути: /en/about, /ru/services/web.
        // Исключаем служебные префиксы; catch-all регистрируется последним.
        Route::get('/{path}', [PageController::class, 'show'])
            ->where('path', '^(?!admin|api|docs|login)[A-Za-z0-9\-_/]+$')
            ->name('cms.page');
    });

// URL без языка → редирект на язык по умолчанию (en).
Route::get('/', fn () => redirect('/' . \App\Modules\Cms\Models\Page::DEFAULT_LOCALE));
Route::get('/{path}', fn (string $path) => redirect('/' . \App\Modules\Cms\Models\Page::DEFAULT_LOCALE . '/' . $path))
    ->where('path', '^(?!admin|api|docs|login|en|ru)[A-Za-z0-9\-_/]+$');
