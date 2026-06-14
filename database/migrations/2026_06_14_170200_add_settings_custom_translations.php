<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Переводы вкладки «Произвольные» (custom key→value) раздела настроек.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            'settings.tab.custom'        => ['Произвольные', 'Custom'],
            'settings.custom.hint'       => [
                "Произвольные настройки key → value. В коде: Setting::get('key') или @setting('key') в шаблоне.",
                "Arbitrary key → value settings. In code: Setting::get('key') or @setting('key') in a template.",
            ],
            'settings.custom.add'        => ['Добавить настройку', 'Add setting'],
            'settings.field.custom_key'   => ['Ключ', 'Key'],
            'settings.field.custom_value' => ['Значение', 'Value'],
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
        DB::table('translations')
            ->where('group', 'admin')
            ->whereIn('key', [
                'settings.tab.custom', 'settings.custom.hint', 'settings.custom.add',
                'settings.field.custom_key', 'settings.field.custom_value',
            ])
            ->delete();
    }
};
