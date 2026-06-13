<?php

namespace App\Modules\Cms\Support;

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
