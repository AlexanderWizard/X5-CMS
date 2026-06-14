<?php

namespace App\Providers;

use App\Modules\System\Support\DatabaseTranslationLoader;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Переводы интерфейса грузим из БД (таблица translations) поверх файлов.
        // TranslationServiceProvider отложенный и биндит свой FileLoader лениво,
        // поэтому используем extend — он применяется ПОВЕРХ позднего биндинга.
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new DatabaseTranslationLoader($app['files'], $app['path.lang']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // MySQL 5.6 совместимость — ограничение длины строковых индексов
        Builder::defaultStringLength(191);

        // Директива @partial('slug') — инклюд шаблона CMS из БД
        Blade::directive('partial', function (string $expression): string {
            return "<?php echo \\App\\Modules\\Cms\\Support\\TemplateRenderer::partial({$expression}, get_defined_vars()); ?>";
        });

        // Директива @block('slug') — вывод переиспользуемого текстового блока
        Blade::directive('block', function (string $expression): string {
            return "<?php echo \\App\\Modules\\Cms\\Support\\TemplateRenderer::block({$expression}); ?>";
        });

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
