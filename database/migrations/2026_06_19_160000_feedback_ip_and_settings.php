<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Запись IP отправителя в cms_feedback + дефолты настроек формы обратной связи:
 *  - feedback_enabled        — форма включена/выключена
 *  - feedback_limit_per_hour — максимум заявок в час со всего сайта (0 = без лимита)
 *  - feedback_limit_per_ip   — максимум заявок в час с одного IP (0 = без лимита)
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `cms_feedback`
            ADD COLUMN `ip_address` VARCHAR(64) NULL DEFAULT NULL AFTER `message`
        ");

        $defaults = [
            'feedback_enabled'        => '1',
            'feedback_limit_per_hour' => '60',
            'feedback_limit_per_ip'   => '5',
        ];

        $now = now();

        foreach ($defaults as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => $now],
            );
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `cms_feedback` DROP COLUMN `ip_address`");

        DB::table('settings')->whereIn('key', [
            'feedback_enabled', 'feedback_limit_per_hour', 'feedback_limit_per_ip',
        ])->delete();
    }
};
