<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `firewall_rules` (
                `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `ip_address`  VARCHAR(64)     NOT NULL,
                `description` VARCHAR(255)    NULL DEFAULT NULL,
                `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
                `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `firewall_rules_active_index` (`is_active`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `firewall_rules`");
    }
};
