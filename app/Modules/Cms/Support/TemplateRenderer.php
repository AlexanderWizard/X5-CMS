<?php

namespace App\Modules\Cms\Support;

use App\Modules\Cms\Models\Block;
use App\Modules\Cms\Models\Template;
use Illuminate\Support\Facades\Blade;

/**
 * Рендер шаблонов CMS из БД и инклюд частичных шаблонов.
 *
 * Используется Blade-директивой @partial('slug') внутри тел шаблонов:
 * подставляет тело шаблона с указанным slug, рендеря его Blade-разметку
 * с теми же переменными, что и у родителя.
 *
 * Поиск тел шаблонов и значений блоков по slug кэшируется через FrontCache
 * (см. body()/block()) — снимает повторные запросы в БД на каждый @partial/@block.
 */
class TemplateRenderer
{
    /**
     * Отрендерить тело шаблона по slug с переданными переменными.
     */
    public static function render(string $slug, array $vars = []): string
    {
        $body = static::body($slug);

        if (blank($body)) {
            return '';
        }

        return Blade::render($body, static::cleanVars($vars));
    }

    /**
     * Инклюд частичного шаблона (вызывается из скомпилированной @partial).
     */
    public static function partial(string $slug, array $vars = []): string
    {
        return static::render($slug, $vars);
    }

    /** @var array<string, string|null> request-мемоизация тел шаблонов по slug */
    protected static array $bodyCache = [];

    /**
     * Тело шаблона по slug. Request-мемоизация + кросс-запросный кэш FrontCache.
     */
    public static function body(string $slug): ?string
    {
        if (array_key_exists($slug, static::$bodyCache)) {
            return static::$bodyCache[$slug];
        }

        return static::$bodyCache[$slug] = FrontCache::remember(
            "tpl.{$slug}",
            fn () => Template::query()->where('slug', $slug)->value('body'),
        );
    }

    /** @var array<string, string> кэш блоков на время запроса */
    protected static array $blockCache = [];

    /**
     * Локализованное значение текстового блока по slug (вызывается из директивы @block).
     * Request-кэш по «локаль:slug» поверх кросс-запросного FrontCache.
     */
    public static function block(string $slug): string
    {
        $locale = \App\Modules\Cms\Models\Page::currentLocale();
        $key    = $locale . ':' . $slug;

        if (!array_key_exists($key, static::$blockCache)) {
            static::$blockCache[$key] = FrontCache::remember(
                "block.{$locale}.{$slug}",
                function () use ($slug, $locale): string {
                    $block = Block::query()->where('slug', $slug)->first();

                    return (string) ($block?->localized($locale) ?? '');
                },
            );
        }

        return static::$blockCache[$key];
    }

    /** Маркеры per-request разметки, при которых страницу нельзя кэшировать HTML-ом. */
    private const DYNAMIC_MARKERS = ['@csrf', 'csrf_token', 'old(', '$errors', 'session('];

    /**
     * Содержит ли цепочка шаблона (тело + рекурсивно все @partial) per-request
     * конструкции — CSRF-токен, old()/$errors, session(). Такие страницы целиком
     * кэшировать нельзя (заморозили бы токен/валидацию формы).
     *
     * @param array<int, string> $seen защита от циклических @partial
     */
    public static function chainIsDynamic(string $slug, array $seen = []): bool
    {
        if (in_array($slug, $seen, true)) {
            return false;
        }
        $seen[] = $slug;

        $body = static::body($slug);

        if (blank($body)) {
            return false;
        }

        foreach (self::DYNAMIC_MARKERS as $marker) {
            if (str_contains($body, $marker)) {
                return true;
            }
        }

        if (preg_match_all('/@partial\(\s*[\'"]([^\'"]+)[\'"]/', $body, $m)) {
            foreach ($m[1] as $child) {
                if (static::chainIsDynamic($child, $seen)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Убираем служебные переменные Blade перед передачей в дочерний рендер.
     */
    private static function cleanVars(array $vars): array
    {
        foreach (array_keys($vars) as $key) {
            if (str_starts_with($key, '__')) {
                unset($vars[$key]);
            }
        }

        unset($vars['app'], $vars['errors']);

        return $vars;
    }
}
