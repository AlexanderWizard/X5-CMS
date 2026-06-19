<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Строки UI для конструктора меню (MenuItemResource).
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            'cms.menu.nav'          => ['ru' => 'Меню сайта',  'en' => 'Site menu'],
            'cms.menu.model'        => ['ru' => 'Пункт меню',  'en' => 'Menu item'],
            'cms.menu.model_plural' => ['ru' => 'Меню сайта',  'en' => 'Site menu'],

            'cms.menu.field.title'    => ['ru' => 'Подпись',          'en' => 'Label'],
            'cms.menu.field.type'     => ['ru' => 'Тип ссылки',       'en' => 'Link type'],
            'cms.menu.field.page'     => ['ru' => 'Страница',         'en' => 'Page'],
            'cms.menu.field.url'      => ['ru' => 'URL',              'en' => 'URL'],
            'cms.menu.field.url_hint' => ['ru' => 'Абсолютный (https://… или /docs) — как есть; иначе — внутренний путь с префиксом языка (напр. blog).', 'en' => 'Absolute (https://… or /docs) — used as is; otherwise an internal path with a language prefix (e.g. blog).'],
            'cms.menu.field.new_tab'   => ['ru' => 'В новой вкладке',  'en' => 'Open in new tab'],
            'cms.menu.field.is_active' => ['ru' => 'Активен',          'en' => 'Active'],

            'cms.menu.type.home' => ['ru' => 'Главная',           'en' => 'Home'],
            'cms.menu.type.page' => ['ru' => 'Страница CMS',      'en' => 'CMS page'],
            'cms.menu.type.url'  => ['ru' => 'Произвольный URL',  'en' => 'Custom URL'],

            'cms.menu.col.title'  => ['ru' => 'Подпись',  'en' => 'Label'],
            'cms.menu.col.type'   => ['ru' => 'Тип',      'en' => 'Type'],
            'cms.menu.col.target' => ['ru' => 'Ссылка',   'en' => 'Target'],

            'cms.menu.action.add' => ['ru' => 'Добавить пункт', 'en' => 'Add item'],
            'cms.menu.reorder_hint' => ['ru' => 'Перетащите строки, чтобы изменить порядок.', 'en' => 'Drag rows to reorder.'],
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
            ->where('key', 'like', 'cms.menu.%')
            ->delete();
    }
};
