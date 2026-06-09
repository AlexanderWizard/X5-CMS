<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `action_logs` (
                `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
                `user_login`    VARCHAR(191)    NULL DEFAULT NULL,
                `event`         VARCHAR(32)     NOT NULL,
                `subject_type`  VARCHAR(64)     NULL DEFAULT NULL,
                `subject_label` VARCHAR(191)    NULL DEFAULT NULL,
                `subject_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
                `properties`    TEXT            NULL DEFAULT NULL,
                `ip_address`    VARCHAR(64)     NULL DEFAULT NULL,
                `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `action_logs_user_id_index` (`user_id`),
                KEY `action_logs_event_index` (`event`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `action_logs`");
    }
};
