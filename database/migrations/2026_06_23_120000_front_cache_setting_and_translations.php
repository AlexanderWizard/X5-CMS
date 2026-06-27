<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Управление фронт-кэшем CMS в настройках сайта:
 *  - сид настройки `front_cache` = '1' (кэш включён по умолчанию);
 *  - переводы вкладки «Кэш» (группа admin).
 * insertOrIgnore — безопасно на повторных/fresh-прогонах.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // Настройка-тумблер (источник правды для FrontCache::enabled()).
        DB::table('settings')->insertOrIgnore([
            'key'        => 'front_cache',
            'value'      => '1',
            'created_at' => $now,
        ]);

        $rows = [
            // key => [ru, en]
            'settings.tab.cache' => ['Кэш', 'Cache'],

            'settings.field.front_cache'      => ['Кэш публичного фронта', 'Public front cache'],
            'settings.field.front_cache_hint' => [
                'Кэширует HTML «статичных» страниц и поиск шаблонов/блоков. Любая правка контента сбрасывает кэш автоматически. Выключайте только для отладки.',
                'Caches HTML of "static" pages and template/block lookups. Any content change flushes the cache automatically. Turn off only for debugging.',
            ],
            'settings.field.cache_status'       => ['Состояние', 'Status'],
            'settings.field.cache_status_value' => ['Поколение кэша: :gen', 'Cache generation: :gen'],

            'settings.cache.flush'   => ['Сбросить кэш', 'Flush cache'],
            'settings.cache.flushed' => ['Кэш фронта сброшен', 'Front cache flushed'],
        ];

        $insert = [];

        foreach ($rows as $key => [$ru, $en]) {
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'ru', 'value' => $ru, 'created_at' => $now];
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'en', 'value' => $en, 'created_at' => $now];
        }

        DB::table('translations')->insertOrIgnore($insert);
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'front_cache')->delete();

        DB::table('translations')
            ->where('group', 'admin')
            ->whereIn('key', [
                'settings.tab.cache',
                'settings.field.front_cache',
                'settings.field.front_cache_hint',
                'settings.field.cache_status',
                'settings.field.cache_status_value',
                'settings.cache.flush',
                'settings.cache.flushed',
            ])
            ->delete();
    }
};
