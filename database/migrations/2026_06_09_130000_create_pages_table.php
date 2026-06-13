<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `pages` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent_id`  BIGINT UNSIGNED NULL DEFAULT NULL,
                `title`      VARCHAR(191)    NOT NULL,
                `slug`       VARCHAR(191)    NOT NULL,
                `content`    LONGTEXT        NULL DEFAULT NULL,
                `is_home`    TINYINT(1)      NOT NULL DEFAULT 0,
                `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
                `sort_order` INT             NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `pages_slug_unique` (`slug`),
                KEY `pages_parent_id_index` (`parent_id`),
                CONSTRAINT `pages_parent_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Главная страница лендинга (корень дерева)
        $exists = DB::table('pages')->where('is_home', 1)->exists();

        if (!$exists) {
            DB::table('pages')->insert([
                'parent_id'  => null,
                'title'      => 'Главная',
                'slug'       => 'home',
                'content'    => null,
                'is_home'    => 1,
                'is_active'  => 1,
                'sort_order' => 0,
            ]);
        }
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `pages`");
    }
};
