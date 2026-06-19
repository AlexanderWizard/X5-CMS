<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Доп. строки UI для раздела «Обратная связь» (FeedbackResource).
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            'feedback.model'            => ['ru' => 'Заявка',            'en' => 'Message'],
            'feedback.model_plural'     => ['ru' => 'Обратная связь',    'en' => 'Feedback'],
            'feedback.status.pending'   => ['ru' => 'Не обработано',      'en' => 'Pending'],
            'feedback.status.processed' => ['ru' => 'Обработано',         'en' => 'Processed'],
            'feedback.action.process'   => ['ru' => 'Пометить обработанным',   'en' => 'Mark as processed'],
            'feedback.action.unprocess' => ['ru' => 'Вернуть в необработанные', 'en' => 'Mark as pending'],
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
        $keys = [
            'feedback.model', 'feedback.model_plural',
            'feedback.status.pending', 'feedback.status.processed',
            'feedback.action.process', 'feedback.action.unprocess',
        ];

        DB::table('translations')
            ->where('group', 'admin')
            ->whereIn('key', $keys)
            ->delete();
    }
};
