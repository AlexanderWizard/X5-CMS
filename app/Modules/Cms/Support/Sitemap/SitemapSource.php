<?php

namespace App\Modules\Cms\Support\Sitemap;

/**
 * Поставщик ссылок для sitemap.xml.
 *
 * Каждый модуль, желающий попасть в карту сайта, кладёт класс-реализацию
 * в `app/Modules/{Модуль}/Sitemap/` — реестр Sitemap находит их автоматически
 * (см. Sitemap::sources()). Ядро на конкретные модули не ссылается, поэтому
 * удаление модуля (напр. Blog) ничего не ломает.
 */
interface SitemapSource
{
    /**
     * Ссылки модуля для карты сайта.
     *
     * @return iterable<SitemapUrl>
     */
    public function entries(): iterable;
}
