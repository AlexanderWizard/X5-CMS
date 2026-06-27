<?php

namespace App\Modules\Gallery\Sitemap;

use App\Modules\Cms\Support\Sitemap\SitemapSource;
use App\Modules\Cms\Support\Sitemap\SitemapUrl;
use App\Modules\Gallery\Models\Album;

/**
 * Поставщик карты сайта для галереи: список альбомов + страницы альбомов.
 *
 * Реализует контракт ядра CMS. Удаление модуля Gallery убирает этот класс —
 * карта сайта просто перестаёт включать галерею, ничего не ломается.
 */
class GallerySitemap implements SitemapSource
{
    public function entries(): iterable
    {
        // Список альбомов.
        yield new SitemapUrl(fn (string $locale): string => url($locale . '/gallery'));

        // Страницы активных альбомов.
        foreach (Album::query()->activeFeed()->get() as $album) {
            yield new SitemapUrl(
                fn (string $locale): string => url($locale . '/gallery/' . $album->slug),
                $album->updated_at ?? $album->created_at,
            );
        }
    }
}
