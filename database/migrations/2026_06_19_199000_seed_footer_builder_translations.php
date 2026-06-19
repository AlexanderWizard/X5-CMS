<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Строки UI для конструктора футера (FooterColumnResource + ссылки).
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            'cms.footer.nav'          => ['ru' => 'Футер',        'en' => 'Footer'],
            'cms.footer.model'        => ['ru' => 'Колонка',      'en' => 'Column'],
            'cms.footer.model_plural' => ['ru' => 'Футер',        'en' => 'Footer'],

            'cms.footer.field.title'     => ['ru' => 'Заголовок колонки', 'en' => 'Column title'],
            'cms.footer.field.is_active' => ['ru' => 'Активна',           'en' => 'Active'],

            'cms.footer.links.label'  => ['ru' => 'Ссылки',         'en' => 'Links'],
            'cms.footer.links.add'    => ['ru' => 'Добавить ссылку', 'en' => 'Add link'],
            'cms.footer.links.title'  => ['ru' => 'Подпись',        'en' => 'Label'],
            'cms.footer.links.type'   => ['ru' => 'Тип ссылки',     'en' => 'Link type'],
            'cms.footer.links.page'   => ['ru' => 'Страница',       'en' => 'Page'],
            'cms.footer.links.url'    => ['ru' => 'URL',            'en' => 'URL'],
            'cms.footer.links.url_hint' => ['ru' => 'Абсолютный (https://… или /docs) — как есть; иначе внутренний путь с префиксом языка (напр. blog).', 'en' => 'Absolute (https://… or /docs) — as is; otherwise an internal path with a language prefix (e.g. blog).'],
            'cms.footer.links.new_tab'   => ['ru' => 'В новой вкладке', 'en' => 'New tab'],
            'cms.footer.links.is_active' => ['ru' => 'Активна',        'en' => 'Active'],

            'cms.footer.type.home' => ['ru' => 'Главная',          'en' => 'Home'],
            'cms.footer.type.page' => ['ru' => 'Страница CMS',     'en' => 'CMS page'],
            'cms.footer.type.url'  => ['ru' => 'Произвольный URL', 'en' => 'Custom URL'],

            'cms.footer.col.links'  => ['ru' => 'Ссылок', 'en' => 'Links'],
            'cms.footer.action.add' => ['ru' => 'Добавить колонку', 'en' => 'Add column'],
        ];

        $now = now();

        foreach ($strings as $key => $byLocale) {
            foreach ($byLocale as $locale => $value) {
                DB::table('translations')->updateOrInsert(
                    ['group' => 'admin', 'key' => $key, 'locale' => $locale],
                    ['value' => $value, 'created_at' => $now],
                );
            }
        }
    }

    public function down(): void
    {
        DB::table('translations')
            ->where('group', 'admin')
            ->where('key', 'like', 'cms.footer.%')
            ->delete();
    }
};
