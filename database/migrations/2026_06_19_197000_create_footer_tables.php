<?php

use App\Modules\System\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Конструктор футера: колонки (footer_columns) и ссылки в них (footer_links).
 * Сидируется демо-структурой подвала.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `footer_columns` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title`      VARCHAR(191)    NOT NULL,
                `i18n`       LONGTEXT        NULL DEFAULT NULL,
                `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
                `sort_order` INT             NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `footer_links` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `column_id`  BIGINT UNSIGNED NOT NULL,
                `title`      VARCHAR(191)    NOT NULL,
                `i18n`       LONGTEXT        NULL DEFAULT NULL,
                `type`       VARCHAR(20)     NOT NULL DEFAULT 'url',
                `page_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
                `url`        VARCHAR(255)    NULL DEFAULT NULL,
                `new_tab`    TINYINT(1)      NOT NULL DEFAULT 0,
                `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
                `sort_order` INT             NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `footer_links_column_id_index` (`column_id`),
                KEY `footer_links_page_id_index` (`page_id`),
                CONSTRAINT `footer_links_column_id_foreign`
                    FOREIGN KEY (`column_id`) REFERENCES `footer_columns` (`id`) ON DELETE CASCADE,
                CONSTRAINT `footer_links_page_id_foreign`
                    FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        if (DB::table('footer_columns')->exists()) {
            return;
        }

        $this->seed();
    }

    private function seed(): void
    {
        $locales = Language::codes() ?: ['en', 'ru'];
        $now     = now();

        $columns = [
            [
                'title' => ['en' => 'Navigation', 'ru' => 'Навигация'],
                'links' => [
                    ['title' => ['en' => 'Home', 'ru' => 'Главная'], 'type' => 'home', 'url' => null],
                    ['title' => ['en' => 'Blog', 'ru' => 'Блог'],    'type' => 'url',  'url' => 'blog'],
                ],
            ],
            [
                'title' => ['en' => 'Resources', 'ru' => 'Ресурсы'],
                'links' => [
                    ['title' => ['en' => 'API docs', 'ru' => 'Документация API'], 'type' => 'url', 'url' => '/docs'],
                    ['title' => ['en' => 'Admin panel', 'ru' => 'Админка'],       'type' => 'url', 'url' => '/admin'],
                ],
            ],
        ];

        foreach ($columns as $ci => $col) {
            $colId = DB::table('footer_columns')->insertGetId([
                'title'      => $col['title'][Language::default()] ?? $col['title']['en'],
                'i18n'       => json_encode($this->titles($locales, $col['title']), JSON_UNESCAPED_UNICODE),
                'is_active'  => 1,
                'sort_order' => $ci,
                'created_at' => $now,
            ]);

            foreach ($col['links'] as $li => $link) {
                DB::table('footer_links')->insert([
                    'column_id'  => $colId,
                    'title'      => $link['title'][Language::default()] ?? $link['title']['en'],
                    'i18n'       => json_encode($this->titles($locales, $link['title']), JSON_UNESCAPED_UNICODE),
                    'type'       => $link['type'],
                    'url'        => $link['url'],
                    'is_active'  => 1,
                    'sort_order' => $li,
                ]);
            }
        }
    }

    /**
     * { locale: { title } } из карты ['en'=>..,'ru'=>..] с фолбэком на en.
     */
    private function titles(array $locales, array $map): array
    {
        $out = [];
        foreach ($locales as $loc) {
            $out[$loc] = ['title' => $map[$loc] ?? $map['en'] ?? reset($map)];
        }

        return $out;
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `footer_links`');
        DB::statement('DROP TABLE IF EXISTS `footer_columns`');
    }
};
