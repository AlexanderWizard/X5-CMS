<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Модуль «Галерея»: фотоальбомы и фотографии (с EXIF-метаданными).
 *
 * Аналог модуля Gallery с alexanderwizard.com, адаптированный под Laravel+Filament:
 *  - gallery_albums — альбомы (древовидности нет, плоский список);
 *  - gallery_photos — фотографии альбома (path = базовый путь без расширения
 *    на публичном диске storage/app/public, варианты .jpg / _tmb.jpg / _tmb2.jpg).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `gallery_albums` (
                `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title`        VARCHAR(191)    NOT NULL,
                `slug`         VARCHAR(191)    NOT NULL,
                `description`  TEXT            NULL DEFAULT NULL,
                `i18n`         LONGTEXT        NULL DEFAULT NULL,
                `photos_count` INT             NOT NULL DEFAULT 0,
                `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
                `sort_order`   INT             NOT NULL DEFAULT 0,
                `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `gallery_albums_slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `gallery_photos` (
                `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `album_id`      BIGINT UNSIGNED NOT NULL,
                `path`          VARCHAR(255)    NULL DEFAULT NULL,
                `title`         VARCHAR(250)    NULL DEFAULT NULL,
                `tags`          VARCHAR(250)    NULL DEFAULT NULL,
                `i18n`          LONGTEXT        NULL DEFAULT NULL,
                `width`         INT             NOT NULL DEFAULT 0,
                `height`        INT             NOT NULL DEFAULT 0,
                `size`          INT             NOT NULL DEFAULT 0,
                `camera`        VARCHAR(64)     NULL DEFAULT NULL,
                `lens`          VARCHAR(64)     NULL DEFAULT NULL,
                `shutter_speed` VARCHAR(32)     NULL DEFAULT NULL,
                `focal_length`  VARCHAR(32)     NULL DEFAULT NULL,
                `iso`           INT             NULL DEFAULT NULL,
                `taken_at`      VARCHAR(32)     NULL DEFAULT NULL,
                `year`          INT             NULL DEFAULT NULL,
                `sort_order`    INT             NOT NULL DEFAULT 0,
                `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
                `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `gallery_photos_album_id_index` (`album_id`),
                CONSTRAINT `gallery_photos_album_id_foreign`
                    FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->seedDemo();
    }

    private function seedDemo(): void
    {
        if (DB::table('gallery_albums')->exists()) {
            return;
        }

        $now = now();

        $albums = [
            ['Природа', 'nature',    'Пейзажи и макро.',          1],
            ['Города',  'cities',    'Городская архитектура.',     2],
            ['Поездки', 'travel',    'Снимки из путешествий.',     3],
        ];

        foreach ($albums as [$title, $slug, $desc, $sort]) {
            DB::table('gallery_albums')->insert([
                'title'      => $title,
                'slug'       => $slug,
                'description' => $desc,
                'sort_order' => $sort,
                'is_active'  => 1,
                'created_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `gallery_photos`');
        DB::statement('DROP TABLE IF EXISTS `gallery_albums`');
    }
};
