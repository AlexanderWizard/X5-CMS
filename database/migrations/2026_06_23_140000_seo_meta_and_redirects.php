<?php

use App\Modules\Cms\Support\FrontCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SEO-выхлоп:
 *  - в системный шаблон `head` добавляются canonical, Open Graph / Twitter и
 *    meta robots=noindex (управляется настройкой seo_index) — идемпотентно;
 *  - переводы ресурса «Редиректы» и поля seo_og_image (группа admin).
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->patchHeadTemplate();
        $this->seedTranslations();

        // head изменён мимо Eloquent — сбрасываем фронт-кэш.
        FrontCache::flush();
    }

    private function patchHeadTemplate(): void
    {
        $body = DB::table('templates')->where('slug', 'head')->value('body');

        if (! $body || str_contains($body, 'og:title')) {
            return; // нет шаблона или уже пропатчен
        }

        $meta = <<<'BLADE'
 @unless (\App\Modules\System\Models\Setting::bool('seo_index', true))<meta name="robots" content="noindex, nofollow">@endunless
 <link rel="canonical" href="{{ $page->urlFor(app()->getLocale()) }}">
 <meta property="og:type" content="website">
 <meta property="og:site_name" content="{{ $appName }}">
 <meta property="og:title" content="{{ ($metaTitle ?? null) ?: $title }}">
 @if (!empty($metaDescription))<meta property="og:description" content="{{ $metaDescription }}">@endif
 <meta property="og:url" content="{{ $page->urlFor(app()->getLocale()) }}">
 @php($ogImage = \App\Modules\System\Models\Setting::get('seo_og_image'))
 @if (!empty($ogImage))<meta property="og:image" content="{{ url(\Illuminate\Support\Facades\Storage::url($ogImage)) }}">@endif
 <meta name="twitter:card" content="{{ !empty($ogImage) ? 'summary_large_image' : 'summary' }}">

BLADE;

        $anchor   = ' <link rel="preconnect" href="https://fonts.googleapis.com">';
        $patched  = str_contains($body, $anchor)
            ? str_replace($anchor, $meta . $anchor, $body)
            : str_replace('</head>', $meta . '</head>', $body);

        DB::table('templates')->where('slug', 'head')->update(['body' => $patched]);
    }

    private function seedTranslations(): void
    {
        $rows = [
            // key => [ru, en]
            'cms.redirects.nav'          => ['Редиректы', 'Redirects'],
            'cms.redirects.model'        => ['Редирект', 'Redirect'],
            'cms.redirects.model_plural' => ['Редиректы', 'Redirects'],

            'cms.redirects.field.from'      => ['Откуда', 'From'],
            'cms.redirects.field.from_hint' => ['Путь без языка, напр. about-old', 'Path without language, e.g. about-old'],
            'cms.redirects.field.to'        => ['Куда', 'To'],
            'cms.redirects.field.to_hint'   => ['Путь без языка; пусто = главная', 'Path without language; empty = home'],
            'cms.redirects.field.status'    => ['Код', 'Code'],
            'cms.redirects.field.is_active' => ['Активен', 'Active'],

            'cms.redirects.status.301' => ['301 — постоянный', '301 — permanent'],
            'cms.redirects.status.302' => ['302 — временный', '302 — temporary'],

            'cms.redirects.col.from'       => ['Откуда', 'From'],
            'cms.redirects.col.to'         => ['Куда', 'To'],
            'cms.redirects.col.status'     => ['Код', 'Code'],
            'cms.redirects.col.is_active'  => ['Активен', 'Active'],
            'cms.redirects.col.hits'       => ['Переходов', 'Hits'],
            'cms.redirects.col.created_at' => ['Создан', 'Created'],

            'settings.field.seo_og_image'      => ['Картинка Open Graph', 'Open Graph image'],
            'settings.field.seo_og_image_hint' => [
                'Превью при шеринге в соцсетях (og:image), рекоменд. 1200×630',
                'Preview image for social sharing (og:image), recommended 1200×630',
            ],
        ];

        $now    = now();
        $insert = [];

        foreach ($rows as $key => [$ru, $en]) {
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'ru', 'value' => $ru, 'created_at' => $now];
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'en', 'value' => $en, 'created_at' => $now];
        }

        DB::table('translations')->insertOrIgnore($insert);
    }

    public function down(): void
    {
        DB::table('translations')
            ->where('group', 'admin')
            ->where(function ($q) {
                $q->where('key', 'like', 'cms.redirects.%')
                    ->orWhere('key', 'like', 'settings.field.seo_og_image%');
            })
            ->delete();
    }
};
