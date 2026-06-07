<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // MySQL 5.6 совместимость — ограничение длины строковых индексов
        Builder::defaultStringLength(191);

        // Применяем локаль пользователя как только auth будет доступен
        $this->callAfterResolving('auth', function () {
            $user = auth('admin')->user();

            if (!$user) {
                return;
            }

            if (in_array($user->locale, ['ru', 'en'])) {
                App::setLocale($user->locale);
            }
        });

        // Часовой пояс для всех дат Filament — через FilamentTimezone
        FilamentTimezone::set(function () {
            $user = auth('admin')->user();

            if ($user && !empty($user->timezone) && in_array($user->timezone, timezone_identifiers_list())) {
                return $user->timezone;
            }

            return config('app.timezone', 'UTC');
        });
    }
}
