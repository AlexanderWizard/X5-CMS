<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Мультиязычный контент блога: per-locale поля в JSON-колонке `i18n`
 * (MySQL 5.6 без JSON-типа → longText + array-cast в модели).
 *
 *   blog_articles.i18n   → { "en": {title,excerpt,content}, "ru": {...} }
 *   blog_categories.i18n → { "en": {name}, "ru": {name} }
 *   blog_tags.i18n       → { "en": {name}, "ru": {name} }
 *
 * Бэкфилл: текущие значения копируются во все активные локали как старт.
 */
return new class extends Migration
{
    public function up(): void
    {
        // MySQL 5.6: Schema::hasColumn падает (нет generation_expression) → SHOW COLUMNS.
        foreach (['blog_articles', 'blog_categories', 'blog_tags'] as $table) {
            if (!$this->hasColumn($table, 'i18n')) {
                DB::statement("ALTER TABLE `{$table}` ADD COLUMN `i18n` LONGTEXT NULL");
            }
        }

        $locales = \App\Modules\System\Models\Language::codes();
        if (empty($locales)) {
            $locales = ['en', 'ru'];
        }

        // Статьи
        foreach (DB::table('blog_articles')->get() as $row) {
            $fields = [
                'title'   => $row->title,
                'excerpt' => $row->excerpt,
                'content' => $row->content,
            ];
            $this->backfill('blog_articles', $row->id, $locales, $fields);
        }

        // Категории и теги (только name)
        foreach (['blog_categories', 'blog_tags'] as $table) {
            foreach (DB::table($table)->get() as $row) {
                $this->backfill($table, $row->id, $locales, ['name' => $row->name]);
            }
        }

        $this->seedEnglishDemo();
    }

    /**
     * Английские переводы для демо-записей (если язык 'en' активен) —
     * чтобы мультиязычность была видна сразу после установки.
     */
    private function seedEnglishDemo(): void
    {
        if (!in_array('en', \App\Modules\System\Models\Language::codes(), true)) {
            return;
        }

        $articlesEn = [
            'blog-launch'    => ['title' => 'Blog launch', 'excerpt' => 'We opened the blog section — news and announcements live here.', 'content' => '<p>Welcome to our blog. Here we publish news, announcements and notes.</p>'],
            'first-announce' => ['title' => 'First announcement', 'excerpt' => 'A short announcement of upcoming product updates.', 'content' => '<p>We will ship several important updates soon.</p>'],
        ];
        foreach ($articlesEn as $slug => $en) {
            $this->mergeLocale('blog_articles', $slug, 'en', $en);
        }

        $this->mergeLocale('blog_categories', 'news', 'en', ['name' => 'News']);
        $this->mergeLocale('blog_tags', 'release', 'en', ['name' => 'Release']);
        $this->mergeLocale('blog_tags', 'announce', 'en', ['name' => 'Announcement']);
    }

    private function mergeLocale(string $table, string $slug, string $locale, array $fields): void
    {
        $row = DB::table($table)->where('slug', $slug)->first();
        if (!$row) {
            return;
        }

        $i18n          = json_decode($row->i18n ?? '{}', true) ?: [];
        $i18n[$locale] = array_merge($i18n[$locale] ?? [], $fields);

        DB::table($table)->where('id', $row->id)->update([
            'i18n' => json_encode($i18n, JSON_UNESCAPED_UNICODE),
        ]);
    }

    private function hasColumn(string $table, string $column): bool
    {
        // SHOW COLUMNS не принимает плейсхолдеры → подставляем экранированный литерал.
        $like = str_replace(['%', '_'], ['\\%', '\\_'], $column);

        return !empty(DB::select("SHOW COLUMNS FROM `{$table}` LIKE '{$like}'"));
    }

    private function backfill(string $table, int $id, array $locales, array $fields): void
    {
        $i18n = [];
        foreach ($locales as $loc) {
            $i18n[$loc] = $fields;
        }

        DB::table($table)->where('id', $id)->update([
            'i18n' => json_encode($i18n, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function down(): void
    {
        foreach (['blog_articles', 'blog_categories', 'blog_tags'] as $table) {
            if ($this->hasColumn($table, 'i18n')) {
                DB::statement("ALTER TABLE `{$table}` DROP COLUMN `i18n`");
            }
        }
    }
};
