<?php

namespace App\Modules\Blog\Sitemap;

use App\Modules\Blog\Models\Article;
use App\Modules\Cms\Support\Sitemap\SitemapSource;
use App\Modules\Cms\Support\Sitemap\SitemapUrl;

/**
 * Поставщик карты сайта для блога: лента + опубликованные статьи.
 *
 * Реализует контракт ядра CMS (App\Modules\Cms\Support\Sitemap\SitemapSource).
 * Удаление модуля Blog убирает этот класс — карта сайта просто перестаёт
 * включать блог, ничего больше не ломается.
 */
class BlogSitemap implements SitemapSource
{
    public function entries(): iterable
    {
        // Лента блога.
        yield new SitemapUrl(fn (string $locale): string => url($locale . '/blog'));

        // Опубликованные статьи.
        foreach (Article::query()->publishedFeed()->get() as $article) {
            yield new SitemapUrl(
                fn (string $locale): string => url($locale . '/blog/' . $article->slug),
                $article->published_at,
            );
        }
    }
}
