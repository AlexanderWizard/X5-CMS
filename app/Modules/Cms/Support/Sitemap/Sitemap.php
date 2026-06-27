<?php

namespace App\Modules\Cms\Support\Sitemap;

use Throwable;

/**
 * Реестр поставщиков карты сайта.
 *
 * Авто-обнаружение по соглашению (как discoverResources в Filament): сканирует
 * `app/Modules/* /Sitemap/*.php`, берёт классы, реализующие SitemapSource.
 * Ядро ни на один модуль не ссылается напрямую — удаление модуля вместе с его
 * папкой просто убирает его из карты, ничего не ломая.
 */
class Sitemap
{
    /** @var array<int, SitemapSource>|null мемоизация на запрос */
    protected static ?array $sources = null;

    /**
     * Все доступные поставщики (отсортированы по имени класса для стабильного порядка).
     *
     * @return array<int, SitemapSource>
     */
    public static function sources(): array
    {
        if (static::$sources !== null) {
            return static::$sources;
        }

        $base    = app_path();
        $pattern = $base . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . '*'
            . DIRECTORY_SEPARATOR . 'Sitemap' . DIRECTORY_SEPARATOR . '*.php';

        $classes = [];

        foreach (glob($pattern) ?: [] as $file) {
            // .../app/Modules/Blog/Sitemap/BlogSitemap.php → App\Modules\Blog\Sitemap\BlogSitemap
            $relative = substr($file, strlen($base) + 1, -4);
            $class    = 'App\\' . str_replace(['/', '\\'], '\\', $relative);

            if (class_exists($class)
                && in_array(SitemapSource::class, class_implements($class) ?: [], true)) {
                $classes[$class] = $class;
            }
        }

        ksort($classes);

        $sources = [];

        foreach ($classes as $class) {
            try {
                $sources[] = new $class();
            } catch (Throwable) {
                // Битый поставщик не должен ронять всю карту — пропускаем.
                continue;
            }
        }

        return static::$sources = $sources;
    }

    /** Сбросить кэш поставщиков (для тестов). */
    public static function flush(): void
    {
        static::$sources = null;
    }
}
