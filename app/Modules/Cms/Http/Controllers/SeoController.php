<?php

namespace App\Modules\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cms\Support\FrontCache;
use App\Modules\Cms\Support\Sitemap\Sitemap;
use App\Modules\System\Models\Language;
use App\Modules\System\Models\Setting;
use Illuminate\Http\Response;
use Throwable;

/**
 * SEO-выхлоп: sitemap.xml и robots.txt.
 *
 * Оба генерируются динамически. sitemap кэшируется через FrontCache
 * (ключ с хостом — в URL абсолютные ссылки), сбрасывается при правке контента.
 */
class SeoController extends Controller
{
    /**
     * sitemap.xml — страницы и блог по всем активным языкам, с hreflang-альтернативами.
     */
    public function sitemap(): Response
    {
        $origin = request()->getSchemeAndHttpHost();

        $xml = FrontCache::remember('sitemap.' . md5($origin), fn () => $this->buildSitemap());

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * robots.txt — динамический, с учётом режима обслуживания и настройки индексации.
     */
    public function robots(): Response
    {
        $blocked = Setting::bool('maintenance_mode', false) || ! Setting::bool('seo_index', true);

        $lines = ['User-agent: *'];

        if ($blocked) {
            // Сайт закрыт от поисковиков (обслуживание или noindex-режим).
            $lines[] = 'Disallow: /';
        } else {
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /api';
            $lines[] = 'Disallow: /docs';
            $lines[] = 'Allow: /';
            $lines[] = '';
            $lines[] = 'Sitemap: ' . url('sitemap.xml');
        }

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    /**
     * Сборка тела sitemap.xml.
     */
    private function buildSitemap(): string
    {
        $locales = Language::codes();
        $default = Language::default();

        $out   = [];
        $out[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $out[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
            . 'xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // Ссылки собираются из поставщиков модулей (Sitemap-реестр), без прямых
        // зависимостей на конкретные модули — удаление модуля просто убирает его URL.
        foreach (Sitemap::sources() as $source) {
            try {
                foreach ($source->entries() as $entry) {
                    $this->appendUrl($out, $entry->loc, $locales, $default, $entry->lastmod);
                }
            } catch (Throwable) {
                // Сбойный поставщик не должен ронять всю карту.
                continue;
            }
        }

        $out[] = '</urlset>';

        return implode("\n", $out);
    }

    /**
     * Один <url> на каждую локаль с hreflang-альтернативами и x-default.
     *
     * @param array<int, string>      $out      аккумулятор строк (по ссылке)
     * @param callable(string):string $urlFor   построитель URL по локали
     * @param array<int, string>      $locales  коды активных языков
     */
    private function appendUrl(array &$out, callable $urlFor, array $locales, string $default, mixed $lastmod = null): void
    {
        foreach ($locales as $locale) {
            $out[] = '  <url>';
            $out[] = '    <loc>' . e($urlFor($locale)) . '</loc>';

            foreach ($locales as $alt) {
                $out[] = '    <xhtml:link rel="alternate" hreflang="' . e($alt)
                    . '" href="' . e($urlFor($alt)) . '"/>';
            }
            $out[] = '    <xhtml:link rel="alternate" hreflang="x-default" href="'
                . e($urlFor($default)) . '"/>';

            if ($lastmod) {
                $out[] = '    <lastmod>' . e($this->asDate($lastmod)) . '</lastmod>';
            }

            $out[] = '  </url>';
        }
    }

    private function asDate(mixed $value): string
    {
        return $value instanceof \DateTimeInterface
            ? $value->format('Y-m-d')
            : (string) \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
    }
}
