<?php

namespace App\Modules\Blog\Models\Concerns;

use App\Modules\System\Models\Language;

/**
 * Локализованный контент в JSON-колонке `i18n` ({ locale: { field: value } }).
 *
 * Подключающая модель должна:
 *  - иметь 'i18n' в $fillable и каст 'array';
 *  - объявить const I18N_FIELDS — список переводимых полей (legacy-колонок).
 */
trait HasI18n
{
    /**
     * Текущая локаль сайта (валидная из реестра языков, иначе — по умолчанию).
     */
    public static function currentLocale(): string
    {
        $locale = app()->getLocale();

        return Language::isValid($locale) ? $locale : Language::default();
    }

    /**
     * Локализованное значение поля: текущая локаль → локаль по умолчанию → legacy-колонка.
     */
    public function tr(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: static::currentLocale();
        $i18n   = $this->i18n ?? [];

        return $i18n[$locale][$field]
            ?? $i18n[Language::default()][$field]
            ?? $this->getAttribute($field);
    }

    /**
     * Синхронизация legacy-колонок со значениями локали по умолчанию
     * (чтобы поиск/сортировка/фолбэки в админке работали). Вызывать в saving().
     */
    protected function syncI18nDefaults(): void
    {
        $i18n = $this->i18n ?? [];
        $base = $i18n[Language::default()] ?? (reset($i18n) ?: []);

        foreach (static::I18N_FIELDS as $field) {
            if (array_key_exists($field, $base) && filled($base[$field])) {
                $this->setAttribute($field, $base[$field]);
            }
        }
    }
}
