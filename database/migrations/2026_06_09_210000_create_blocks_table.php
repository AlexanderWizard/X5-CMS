<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `blocks` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`       VARCHAR(191)    NOT NULL,
                `slug`       VARCHAR(191)    NOT NULL,
                `value`      TEXT            NULL DEFAULT NULL,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `blocks_slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Примеры блоков
        $samples = [
            ['name' => 'Телефон', 'slug' => 'phone',   'value' => '+373 77788888'],
            ['name' => 'E-mail',  'slug' => 'email',   'value' => 'info@example.com'],
            ['name' => 'Адрес',   'slug' => 'address', 'value' => 'Chisinau'],
        ];

        foreach ($samples as $s) {
            if (!DB::table('blocks')->where('slug', $s['slug'])->exists()) {
                DB::table('blocks')->insert($s);
            }
        }
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS `blocks`");
    }
};
