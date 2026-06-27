<?php

namespace App\Modules\Cms\Sitemap;

use App\Modules\Cms\Models\Page;
use App\Modules\Cms\Support\Sitemap\SitemapSource;
use App\Modules\Cms\Support\Sitemap\SitemapUrl;

/**
 * Поставщик карты сайта для страниц CMS (включая главную).
 */
class PageSitemap implements SitemapSource
{
    public function entries(): iterable
    {
        foreach (Page::query()->where('is_active', 1)->get() as $page) {
            yield new SitemapUrl(
                fn (string $locale): string => $page->urlFor($locale),
                $page->created_at,
            );
        }
    }
}
