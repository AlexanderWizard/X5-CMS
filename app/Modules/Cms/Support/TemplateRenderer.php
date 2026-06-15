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
 */
class TemplateRenderer
{
    /**
     * Отрендерить тело шаблона по slug с переданными переменными.
     */
    public static function render(string $slug, array $vars = []): string
    {
        $template = Template::query()->where('slug', $slug)->first();

        if (!$template || blank($template->body)) {
            return '';
        }

        return Blade::render($template->body, static::cleanVars($vars));
    }

    /**
     * Инклюд частичного шаблона (вызывается из скомпилированной @partial).
     */
    public static function partial(string $slug, array $vars = []): string
    {
        return static::render($slug, $vars);
    }

    /** @var array<string, string> кэш блоков на время запроса */
    protected static array $blockCache = [];

    /**
     * Локализованное значение текстового блока по slug (вызывается из директивы @block).
     * Кэш на запрос с ключом «локаль:slug».
     */
    public static function block(string $slug): string
    {
        $key = \App\Modules\Cms\Models\Page::currentLocale() . ':' . $slug;

        if (!array_key_exists($key, static::$blockCache)) {
            $block = Block::query()->where('slug', $slug)->first();

            static::$blockCache[$key] = (string) ($block?->localized() ?? '');
        }

        return static::$blockCache[$key];
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
