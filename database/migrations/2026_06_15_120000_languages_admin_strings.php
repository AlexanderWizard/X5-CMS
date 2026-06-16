<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Поддержка динамического списка языков (раздел «Языки» в админке):
 *   1) в шаблонах БД заменяем устаревшую константу Page::LOCALES на
 *      Language::codes() — список локалей теперь берётся из реестра языков;
 *   2) сеем строки интерфейса admin.languages.* (ru/en) для раздела «Языки».
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Шаблоны: Page::LOCALES → Language::codes()
        foreach (DB::table('templates')->get(['id', 'body']) as $tpl) {
            if ($tpl->body !== null && str_contains($tpl->body, 'Page::LOCALES')) {
                DB::table('templates')->where('id', $tpl->id)->update([
                    'body' => str_replace(
                        '\App\Modules\Cms\Models\Page::LOCALES',
                        '\App\Modules\System\Models\Language::codes()',
                        $tpl->body
                    ),
                ]);
            }
        }

        // 2) Переводы интерфейса для раздела «Языки»
        $strings = [
            'languages.nav'                => ['Языки', 'Languages'],
            'languages.model'              => ['Язык', 'Language'],
            'languages.model_plural'       => ['Языки', 'Languages'],
            'languages.field.code'         => ['Код', 'Code'],
            'languages.field.code_hint'    => ['ISO-код локали для URL: /en, /ru', 'Locale code used in URLs: /en, /ru'],
            'languages.field.name'         => ['Название', 'Name'],
            'languages.field.default'      => ['По умолчанию', 'Default'],
            'languages.field.default_hint' => ['Язык по умолчанию ровно один; на него ведут URL без префикса.', 'Exactly one default language; URLs without a prefix redirect to it.'],
            'languages.field.active'       => ['Активен', 'Active'],
            'languages.field.sort'         => ['Порядок', 'Sort order'],
            'languages.col.id'             => ['ID', 'ID'],
            'languages.col.code'           => ['Код', 'Code'],
            'languages.col.name'           => ['Название', 'Name'],
            'languages.col.default'        => ['По умолчанию', 'Default'],
            'languages.col.active'         => ['Активен', 'Active'],
            'languages.col.sort'           => ['Порядок', 'Sort'],
            'languages.col.created_at'     => ['Создан', 'Created'],
            'languages.action.add'         => ['Добавить язык', 'Add language'],
        ];

        $now = now();

        foreach ($strings as $key => [$ru, $en]) {
            foreach (['ru' => $ru, 'en' => $en] as $locale => $value) {
                DB::table('translations')->updateOrInsert(
                    ['group' => 'admin', 'key' => $key, 'locale' => $locale],
                    ['value' => $value, 'created_at' => $now]
                );
            }
        }
    }

    public function down(): void
    {
        DB::table('translations')
            ->where('group', 'admin')
            ->where('key', 'like', 'languages.%')
            ->delete();
    }
};
