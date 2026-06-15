<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Переводы для виджета «Состояние сайта» на дашборде.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            'dash.overview'     => ['Состояние сайта', 'Site overview'],
            'dash.pages'        => ['Страницы', 'Pages'],
            'dash.templates'    => ['Шаблоны', 'Templates'],
            'dash.blocks'       => ['Блоки', 'Blocks'],
            'dash.users'        => ['Пользователи', 'Users'],
            'dash.translations' => ['Переводы', 'Translations'],
            'dash.actions'      => ['Действий за 7 дней', 'Actions · 7 days'],
            'dash.maintenance'  => ['Режим обслуживания', 'Maintenance'],
            'dash.firewall'     => ['Файрвол', 'Firewall'],
            'dash.published'    => [':n опубликовано', ':n published'],
            'dash.active'       => [':n активных', ':n active'],
            'dash.on'           => ['Включён', 'On'],
            'dash.off'          => ['Выключен', 'Off'],
            'dash.fw_rules'     => [':n активных правил', ':n active rules'],
            'dash.fw_open'      => ['Открыт', 'Open'],
            'dash.fw_open_desc' => ['доступ для всех IP', 'open to all IPs'],
        ];

        $now = now();
        $insert = [];
        foreach ($rows as $key => [$ru, $en]) {
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'ru', 'value' => $ru, 'created_at' => $now];
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'en', 'value' => $en, 'created_at' => $now];
        }
        DB::table('translations')->insertOrIgnore($insert);
    }

    public function down(): void
    {
        DB::table('translations')->where('group', 'admin')->where('key', 'like', 'dash.%')->delete();
    }
};
