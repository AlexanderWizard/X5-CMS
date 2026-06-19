<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Строки UI: колонка IP в разделе «Обратная связь» + вкладка/поля настроек формы.
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            'feedback.col.ip' => ['ru' => 'IP', 'en' => 'IP'],

            'settings.tab.feedback' => ['ru' => 'Обратная связь', 'en' => 'Feedback'],

            'settings.field.feedback_enabled' => [
                'ru' => 'Форма обратной связи включена',
                'en' => 'Feedback form enabled',
            ],
            'settings.field.feedback_enabled_hint' => [
                'ru' => 'Выключите, чтобы скрыть форму на сайте и прекратить приём заявок.',
                'en' => 'Disable to hide the form on the site and stop accepting messages.',
            ],
            'settings.field.feedback_limit_per_hour' => [
                'ru' => 'Лимит заявок в час (всего)',
                'en' => 'Limit per hour (total)',
            ],
            'settings.field.feedback_limit_per_ip' => [
                'ru' => 'Лимит заявок в час с одного IP',
                'en' => 'Limit per hour per IP',
            ],
            'settings.field.feedback_limit_hint' => [
                'ru' => '0 — без ограничения.',
                'en' => '0 — no limit.',
            ],
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
            ->whereIn('key', [
                'feedback.col.ip',
                'settings.tab.feedback',
                'settings.field.feedback_enabled',
                'settings.field.feedback_enabled_hint',
                'settings.field.feedback_limit_per_hour',
                'settings.field.feedback_limit_per_ip',
                'settings.field.feedback_limit_hint',
            ])
            ->delete();
    }
};
