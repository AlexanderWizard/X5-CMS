<?php

use App\Modules\Cms\Models\Page;
use App\Modules\System\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Конструктор верхнего меню: таблица menu_items.
 * Сидируется из текущего фактического меню (Главная + страницы + Блог + API).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `menu_items` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title`      VARCHAR(191)    NOT NULL,
                `i18n`       LONGTEXT        NULL DEFAULT NULL,
                `type`       VARCHAR(20)     NOT NULL DEFAULT 'url',
                `page_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
                `url`        VARCHAR(255)    NULL DEFAULT NULL,
                `new_tab`    TINYINT(1)      NOT NULL DEFAULT 0,
                `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
                `sort_order` INT             NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `menu_items_page_id_index` (`page_id`),
                CONSTRAINT `menu_items_page_id_foreign`
                    FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        if (DB::table('menu_items')->exists()) {
            return;
        }

        $locales = Language::codes() ?: ['en', 'ru'];
        $sort    = 0;
        $now     = now();

        $insert = function (array $i18n, string $type, ?int $pageId, ?string $url) use (&$sort, $now): void {
            $def = Language::default();
            DB::table('menu_items')->insert([
                'title'      => $i18n[$def]['title'] ?? reset($i18n)['title'] ?? 'Menu',
                'i18n'       => json_encode($i18n, JSON_UNESCAPED_UNICODE),
                'type'       => $type,
                'page_id'    => $pageId,
                'url'        => $url,
                'is_active'  => 1,
                'sort_order' => $sort++,
                'created_at' => $now,
            ]);
        };

        // 1. Главная
        $insert($this->byLocale($locales, ['en' => 'Home', 'ru' => 'Главная']), 'home', null, null);

        // 2. Страницы верхнего уровня (как было в авто-меню)
        foreach (Page::navItems() as $page) {
            $titles = [];
            foreach ($locales as $loc) {
                $titles[$loc] = ['title' => $page->tr('title', $loc)];
            }
            $insert($titles, 'page', $page->id, null);
        }

        // 3. Блог
        $insert($this->byLocale($locales, ['en' => 'Blog', 'ru' => 'Блог']), 'url', null, 'blog');

        // 4. API / документация
        $insert($this->byLocale($locales, ['en' => 'API', 'ru' => 'API']), 'url', null, '/docs');
    }

    /**
     * Строит i18n-массив подписей: { locale: { title } } с фолбэком на 'en'.
     */
    private function byLocale(array $locales, array $map): array
    {
        $out = [];
        foreach ($locales as $loc) {
            $out[$loc] = ['title' => $map[$loc] ?? $map['en'] ?? reset($map)];
        }

        return $out;
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `menu_items`');
    }
};
