<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Таблица ролей
        DB::statement("
            CREATE TABLE IF NOT EXISTS `roles` (
                `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`        VARCHAR(191)    NOT NULL,
                `description` VARCHAR(255)    NULL DEFAULT NULL,
                `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `roles_name_unique` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Сводная таблица пользователь ↔ роль
        DB::statement("
            CREATE TABLE IF NOT EXISTS `role_user` (
                `id`      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `role_id` BIGINT UNSIGNED NOT NULL,
                `user_id` BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `role_user_unique` (`role_id`, `user_id`),
                KEY `role_user_user_id_index` (`user_id`),
                CONSTRAINT `role_user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
                CONSTRAINT `role_user_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `role_user`");
        DB::statement("DROP TABLE IF EXISTS `roles`");
    }
};
