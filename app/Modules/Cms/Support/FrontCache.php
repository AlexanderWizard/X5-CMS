<?php

namespace App\Modules\Cms\Support;

use App\Modules\Cms\Models\Block;
use App\Modules\Cms\Models\FooterColumn;
use App\Modules\Cms\Models\FooterLink;
use App\Modules\Cms\Models\MenuItem;
use App\Modules\Cms\Models\Page;
use App\Modules\Cms\Models\Template;
use App\Modules\Gallery\Models\Album;
use App\Modules\Gallery\Models\Photo;
use App\Modules\System\Models\Language;
use App\Modules\System\Models\Setting;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Кэш публичного фронта CMS.
 *
 * Все ключи содержат номер «поколения» (version). Сброс всего кэша = bump
 * поколения: старые ключи становятся недостижимы и вытесняются по TTL.
 * Такой подход не зависит от драйвера кэша (теги поддерживает не всякий store,
 * а здесь по умолчанию `database`).
 *
 * Уровни:
 *  1. Поиск тел шаблонов / значений блоков по slug (TemplateRenderer) — снимает
 *     повторные запросы в БД на каждый @partial/@block; рендер остаётся живым,
 *     поэтому CSRF-токен и пр. per-request данные НЕ замораживаются.
 *  2. Готовый HTML «статичных» страниц целиком (PageController) — только для
 *     страниц без per-request разметки в цепочке шаблона.
 */
class FrontCache
{
    private const VERSION_KEY = 'cms.front.ver';

    /** Модели, изменение которых сбрасывает фронт-кэш. */
    private const BUSTERS = [
        Page::class,
        Template::class,
        Block::class,
        MenuItem::class,
        FooterColumn::class,
        FooterLink::class,
        Setting::class,
        Language::class,
        Album::class,
        Photo::class,
    ];

    /**
     * Включён ли фронт-кэш. Источник правды — настройка сайта `front_cache`
     * (раздел «Настройки сайта» → вкладка «Кэш»), дефолт — config('cms.front_cache').
     */
    public static function enabled(): bool
    {
        return Setting::bool('front_cache', (bool) config('cms.front_cache', true));
    }

    public static function ttl(): int
    {
        return (int) config('cms.front_cache_ttl', 86400);
    }

    /** Текущее поколение кэша (входит во все ключи). */
    public static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /** Сбросить весь фронт-кэш — bump поколения. */
    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }

    /** Ключ с префиксом текущего поколения. */
    public static function key(string $suffix): string
    {
        return 'cms.front.v' . self::version() . '.' . $suffix;
    }

    /**
     * remember с учётом включённости кэша и его TTL.
     * Когда кэш выключен — просто исполняет колбэк.
     */
    public static function remember(string $suffix, Closure $callback): mixed
    {
        if (!self::enabled()) {
            return $callback();
        }

        return Cache::remember(self::key($suffix), self::ttl(), $callback);
    }

    /**
     * Подписать модели контента на сброс кэша.
     * Вызывается один раз из AppServiceProvider::boot().
     */
    public static function listen(): void
    {
        foreach (self::BUSTERS as $model) {
            /** @var class-string<Model> $model */
            $model::saved(static fn () => self::flush());
            $model::deleted(static fn () => self::flush());
        }
    }
}
