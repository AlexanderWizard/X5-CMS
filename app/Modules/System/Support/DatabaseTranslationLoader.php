<?php

namespace App\Modules\System\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Translation\FileLoader;
use Throwable;

/**
 * Загрузчик переводов из БД (таблица translations).
 *
 * Расширяет файловый загрузчик: сначала берёт строки из файлов (нужно для
 * vendor-переводов Filament/фреймворка и namespaced-групп), затем подмешивает
 * сверху строки из БД — БД имеет приоритет. Благодаря этому строки интерфейса
 * (группа admin) живут в таблице и редактируются через админку, а lang/*.php
 * не обязателен.
 *
 * Устойчив к отсутствию таблицы (миграции, fresh) — при ошибке БД молча
 * возвращает только файловые строки. Результат мемоизируется на время запроса.
 *
 * @see App\Modules\System\Models\Translation
 * @see App\Providers\AppServiceProvider — регистрация как 'translation.loader'
 */
class DatabaseTranslationLoader extends FileLoader
{
    /** Кэш строк из БД на запрос: [locale][group] => array */
    protected array $dbCache = [];

    public function load($locale, $group, $namespace = null): array
    {
        $fileLines = parent::load($locale, $group, $namespace);

        // namespaced-группы (vendor::group) оставляем файловыми
        if ($namespace !== null && $namespace !== '*') {
            return $fileLines;
        }

        return array_replace($fileLines, $this->loadFromDatabase($locale, $group));
    }

    /**
     * Плоский массив [key => value] из таблицы для локали и группы.
     * Ключи плоские с точками (users.nav) — translator достаёт их по литералу.
     */
    protected function loadFromDatabase(string $locale, string $group): array
    {
        if (isset($this->dbCache[$locale][$group])) {
            return $this->dbCache[$locale][$group];
        }

        try {
            $lines = DB::table('translations')
                ->where('locale', $locale)
                ->where('group', $group)
                ->pluck('value', 'key')
                ->all();
        } catch (Throwable) {
            // таблицы ещё нет (миграции) или БД недоступна — тихий фолбэк на файлы
            $lines = [];
        }

        return $this->dbCache[$locale][$group] = $lines;
    }
}
