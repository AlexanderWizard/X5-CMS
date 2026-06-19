<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Таблица сообщений формы обратной связи с публичного сайта (модуль CMS).
 * Отдельна от messages_queue — это пользовательские заявки, а не API-очередь.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `cms_feedback` (
                `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`         VARCHAR(191)    NOT NULL,
                `email`        VARCHAR(191)    NOT NULL,
                `message`      TEXT            NOT NULL,
                `is_processed` TINYINT(1)      NOT NULL DEFAULT 0,
                `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `cms_feedback_processed_index` (`is_processed`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `cms_feedback`");
    }
};
