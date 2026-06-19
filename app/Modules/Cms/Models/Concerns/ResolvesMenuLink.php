<?php

namespace App\Modules\Cms\Models\Concerns;

use App\Modules\Cms\Models\Page;
use App\Modules\System\Models\Language;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Общая логика «ссылки» для конструкторов меню и футера.
 *
 * Подключающая модель должна иметь колонки: type, page_id, url, title, i18n (array-cast).
 * Типы:
 *   home — локализованная главная (/{locale})
 *   page — страница CMS (по page_id)
 *   url  — произвольный адрес (см. resolvedUrl)
 */
trait ResolvesMenuLink
{
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Локализованная подпись: текущая локаль → дефолт → legacy-колонка title.
     */
    public function tr(string $field = 'title', ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $locale = Language::isValid($locale) ? $locale : Language::default();
        $i18n   = $this->i18n ?? [];

        return $i18n[$locale][$field]
            ?? $i18n[Language::default()][$field]
            ?? $this->getAttribute($field);
    }

    /**
     * Итоговый URL под заданной локалью.
     */
    public function resolvedUrl(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return match ($this->type) {
            'home'  => url($locale),
            'page'  => $this->page ? $this->page->urlFor($locale) : url($locale),
            default => $this->normalizeUrl((string) $this->url, $locale),
        };
    }

    /**
     * Произвольный URL: схема (http) / ведущий слэш / якорь — как есть;
     * иначе — локализованный внутренний путь /{locale}/{path}.
     */
    protected function normalizeUrl(string $url, string $locale): string
    {
        $url = trim($url);

        if ($url === '') {
            return url($locale);
        }

        if (preg_match('~^([a-z]+:)?//~i', $url) || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return $url;
        }

        return url($locale . '/' . ltrim($url, '/'));
    }

    /**
     * legacy-колонка title = подпись локали по умолчанию (фолбэк/поиск в админке).
     * Вызывать в saving().
     */
    protected function syncTitleDefault(): void
    {
        $i18n        = $this->i18n ?? [];
        $this->title = $i18n[Language::default()]['title']
            ?? ($this->title ?: 'Untitled');
    }
}
