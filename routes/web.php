<?php

use App\Http\Middleware\CheckMaintenance;
use App\Modules\Blog\Http\Controllers\BlogController;
use App\Modules\Cms\Http\Controllers\FeedbackController;
use App\Modules\Cms\Http\Controllers\PageController;
use App\Modules\System\Models\Language;
use Illuminate\Support\Facades\Route;

// Алиас для auth-редиректов (middleware auth:admin ищет именованный маршрут 'login')
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');

// Список языков управляется в админке → regex локалей строится из реестра.
// ВНИМАНИЕ: при route:cache список фиксируется — после правки языков нужен route:clear.
$localeRegex = implode('|', array_map('preg_quote', Language::codes()));
$default     = Language::default();

// Публичный сайт CMS с языковым префиксом /{locale}/..., под режимом обслуживания
Route::middleware(CheckMaintenance::class)
    ->prefix('{locale}')
    ->where(['locale' => $localeRegex])
    ->group(function () {
        // Главная лендинга: /en, /ru, ...
        Route::get('/', [PageController::class, 'home'])->name('cms.home');

        // Блог: лента статей и отдельная статья (ДО catch-all cms.page).
        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])
            ->where('slug', '[A-Za-z0-9\-_]+')
            ->name('blog.show');

        // Страницы по иерархическому slug-пути: /en/about, /ru/services/web.
        Route::get('/{path}', [PageController::class, 'show'])
            ->where('path', '^(?!admin|api|docs|login)[A-Za-z0-9\-_/]+$')
            ->name('cms.page');
    });

// Приём формы обратной связи с публичного сайта (POST, защищён CSRF из web-группы).
Route::post('/feedback', [FeedbackController::class, 'store'])->name('cms.feedback');

// URL без языка → редирект на язык по умолчанию.
Route::get('/', fn () => redirect('/' . Language::default()));
Route::get('/{path}', fn (string $path) => redirect('/' . Language::default() . '/' . $path))
    ->where('path', '^(?!admin|api|docs|login|' . $localeRegex . ')[A-Za-z0-9\-_/]+$');
