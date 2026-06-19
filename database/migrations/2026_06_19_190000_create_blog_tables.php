<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Модуль «Блог»: статьи, категории, теги (+ сводная статья↔тег).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `blog_categories` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`       VARCHAR(191)    NOT NULL,
                `slug`       VARCHAR(191)    NOT NULL,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `blog_categories_slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `blog_tags` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`       VARCHAR(191)    NOT NULL,
                `slug`       VARCHAR(191)    NOT NULL,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `blog_tags_slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `blog_articles` (
                `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `category_id`  BIGINT UNSIGNED NULL DEFAULT NULL,
                `title`        VARCHAR(191)    NOT NULL,
                `slug`         VARCHAR(191)    NOT NULL,
                `excerpt`      TEXT            NULL DEFAULT NULL,
                `content`      LONGTEXT        NULL DEFAULT NULL,
                `image`        VARCHAR(255)    NULL DEFAULT NULL,
                `is_published` TINYINT(1)      NOT NULL DEFAULT 1,
                `published_at` TIMESTAMP       NULL DEFAULT NULL,
                `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `blog_articles_slug_unique` (`slug`),
                KEY `blog_articles_category_id_index` (`category_id`),
                CONSTRAINT `blog_articles_category_id_foreign`
                    FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `blog_article_tag` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `article_id` BIGINT UNSIGNED NOT NULL,
                `tag_id`     BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `blog_article_tag_unique` (`article_id`, `tag_id`),
                KEY `blog_article_tag_tag_id_index` (`tag_id`),
                CONSTRAINT `blog_article_tag_article_id_foreign`
                    FOREIGN KEY (`article_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE,
                CONSTRAINT `blog_article_tag_tag_id_foreign`
                    FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->seedDemoData();
    }

    private function seedDemoData(): void
    {
        if (DB::table('blog_articles')->exists()) {
            return;
        }

        $now = now();

        $catId = DB::table('blog_categories')->insertGetId([
            'name'       => 'Новости',
            'slug'       => 'news',
            'created_at' => $now,
        ]);

        $tagIds = [];
        foreach ([['Релиз', 'release'], ['Анонс', 'announce']] as [$name, $slug]) {
            $tagIds[] = DB::table('blog_tags')->insertGetId([
                'name'       => $name,
                'slug'       => $slug,
                'created_at' => $now,
            ]);
        }

        $articles = [
            [
                'title'        => 'Запуск блога',
                'slug'         => 'blog-launch',
                'excerpt'      => 'Мы открыли раздел блога — здесь будут новости и анонсы.',
                'content'      => '<p>Добро пожаловать в наш блог. Здесь мы публикуем новости, анонсы и заметки.</p>',
                'published_at' => $now->copy()->subDays(2),
            ],
            [
                'title'        => 'Первый анонс',
                'slug'         => 'first-announce',
                'excerpt'      => 'Краткий анонс ближайших обновлений продукта.',
                'content'      => '<p>В ближайшее время мы выпустим несколько важных обновлений.</p>',
                'published_at' => $now,
            ],
        ];

        foreach ($articles as $a) {
            $id = DB::table('blog_articles')->insertGetId([
                'category_id'  => $catId,
                'title'        => $a['title'],
                'slug'         => $a['slug'],
                'excerpt'      => $a['excerpt'],
                'content'      => $a['content'],
                'is_published' => 1,
                'published_at' => $a['published_at'],
                'created_at'   => $now,
            ]);

            DB::table('blog_article_tag')->insert([
                'article_id' => $id,
                'tag_id'     => $tagIds[array_rand($tagIds)],
            ]);
        }
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `blog_article_tag`');
        DB::statement('DROP TABLE IF EXISTS `blog_articles`');
        DB::statement('DROP TABLE IF EXISTS `blog_tags`');
        DB::statement('DROP TABLE IF EXISTS `blog_categories`');
    }
};
