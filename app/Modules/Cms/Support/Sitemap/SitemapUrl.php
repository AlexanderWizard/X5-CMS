<?php

namespace App\Modules\Cms\Support\Sitemap;

use Closure;

/**
 * Одна запись карты сайта. Сам URL строится под конкретную локаль (slug общий
 * для всех языков, меняется только префикс) — отсюда callable вместо строки;
 * контроллер по нему отрисует `<url>` с hreflang-альтернативами на каждый язык.
 */
final class SitemapUrl
{
    /**
     * @param Closure(string): string $loc     построитель URL по коду локали
     * @param mixed                   $lastmod дата изменения (DateTime|string|null)
     */
    public function __construct(
        public readonly Closure $loc,
        public readonly mixed $lastmod = null,
    ) {
    }
}
