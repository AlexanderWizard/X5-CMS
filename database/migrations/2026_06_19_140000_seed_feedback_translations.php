<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Строки UI для виджета «Обратная связь» (таблица translations — источник правды).
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            'feedback.nav'            => ['ru' => 'Обратная связь',     'en' => 'Feedback'],
            'feedback.col.id'         => ['ru' => 'ID',                 'en' => 'ID'],
            'feedback.col.name'       => ['ru' => 'Имя',                'en' => 'Name'],
            'feedback.col.email'      => ['ru' => 'E-mail',             'en' => 'E-mail'],
            'feedback.col.message'    => ['ru' => 'Сообщение',          'en' => 'Message'],
            'feedback.col.status'     => ['ru' => 'Обработано',         'en' => 'Processed'],
            'feedback.col.created_at' => ['ru' => 'Создано',            'en' => 'Created'],
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
            ->where('key', 'like', 'feedback.%')
            ->delete();
    }
};
