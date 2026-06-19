<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Строки UI для модуля «Блог» (статьи / категории / теги).
 */
return new class extends Migration
{
    public function up(): void
    {
        $strings = [
            // Статьи
            'blog.articles.nav'          => ['ru' => 'Статьи',        'en' => 'Articles'],
            'blog.articles.model'        => ['ru' => 'Статья',        'en' => 'Article'],
            'blog.articles.model_plural' => ['ru' => 'Статьи',        'en' => 'Articles'],
            'blog.articles.field.title'        => ['ru' => 'Заголовок',  'en' => 'Title'],
            'blog.articles.field.slug'         => ['ru' => 'URL (slug)', 'en' => 'URL (slug)'],
            'blog.articles.field.category'     => ['ru' => 'Категория',  'en' => 'Category'],
            'blog.articles.field.tags'         => ['ru' => 'Теги',       'en' => 'Tags'],
            'blog.articles.field.excerpt'      => ['ru' => 'Анонс',      'en' => 'Excerpt'],
            'blog.articles.field.content'      => ['ru' => 'Содержимое', 'en' => 'Content'],
            'blog.articles.field.image'        => ['ru' => 'Изображение (URL)', 'en' => 'Image (URL)'],
            'blog.articles.field.is_published' => ['ru' => 'Опубликовано',     'en' => 'Published'],
            'blog.articles.field.published_at' => ['ru' => 'Дата публикации',  'en' => 'Published at'],
            'blog.articles.col.title'        => ['ru' => 'Заголовок',  'en' => 'Title'],
            'blog.articles.col.category'     => ['ru' => 'Категория',  'en' => 'Category'],
            'blog.articles.col.published_at' => ['ru' => 'Публикация', 'en' => 'Published'],
            'blog.articles.action.add'       => ['ru' => 'Добавить статью', 'en' => 'Add article'],

            // Категории
            'blog.categories.nav'          => ['ru' => 'Категории',  'en' => 'Categories'],
            'blog.categories.model'        => ['ru' => 'Категория',  'en' => 'Category'],
            'blog.categories.model_plural' => ['ru' => 'Категории',  'en' => 'Categories'],
            'blog.categories.field.name'   => ['ru' => 'Название',   'en' => 'Name'],
            'blog.categories.field.slug'   => ['ru' => 'URL (slug)', 'en' => 'URL (slug)'],
            'blog.categories.col.articles' => ['ru' => 'Статей',     'en' => 'Articles'],
            'blog.categories.action.add'   => ['ru' => 'Добавить категорию', 'en' => 'Add category'],

            // Теги
            'blog.tags.nav'          => ['ru' => 'Теги',       'en' => 'Tags'],
            'blog.tags.model'        => ['ru' => 'Тег',        'en' => 'Tag'],
            'blog.tags.model_plural' => ['ru' => 'Теги',       'en' => 'Tags'],
            'blog.tags.field.name'   => ['ru' => 'Название',   'en' => 'Name'],
            'blog.tags.field.slug'   => ['ru' => 'URL (slug)', 'en' => 'URL (slug)'],
            'blog.tags.col.articles' => ['ru' => 'Статей',     'en' => 'Articles'],
            'blog.tags.action.add'   => ['ru' => 'Добавить тег', 'en' => 'Add tag'],

            // Общее
            'blog.col.id'         => ['ru' => 'ID',       'en' => 'ID'],
            'blog.col.slug'       => ['ru' => 'Slug',     'en' => 'Slug'],
            'blog.col.created_at' => ['ru' => 'Создано',  'en' => 'Created'],
        ];

        $now = now();

        foreach ($strings as $key => $byLocale) {
            foreach ($byLocale as $locale => $value) {
                DB::table('translations')->updateOrInsert(
                    ['group' => 'admin', 'key' => $key, 'locale' => $locale],
                    ['value' => $value, 'created_at' => $now],
                );
            }
        }
    }

    public function down(): void
    {
        DB::table('translations')
            ->where('group', 'admin')
            ->where('key', 'like', 'blog.%')
            ->delete();
    }
};
