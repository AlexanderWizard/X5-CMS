<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Локализация шаблонов сайта: переключатель языка, locale-aware ссылки,
 * локализованные заголовки пунктов меню, hreflang и lang в head/home.
 */
return new class extends Migration
{
    public function up(): void
    {
        $header = <<<'BODY'
<header class="nav">
    <div class="wrap">
        <a href="{{ url($locale ?? 'en') }}" class="brand"><img class="logo" src="{{ asset('images/logo-mark.svg') }}" alt="{{ $appName }}"> {{ $appName }}</a>
        @partial('menu')
        <div class="lang">
            @foreach (\App\Modules\System\Models\Language::codes() as $l)
                <a href="{{ $page->urlFor($l) }}" class="{{ ($locale ?? '') === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
            @endforeach
        </div>
        <a href="/admin" class="btn btn-primary">{{ ($locale ?? '') === 'en' ? 'Sign in' : 'Войти' }}</a>
    </div>
</header>
BODY;

        $menu = <<<'BODY'
<nav class="nav-links">
    <a href="{{ url($locale ?? 'en') }}">{{ ($locale ?? '') === 'en' ? 'Home' : 'Главная' }}</a>
    @foreach (\App\Modules\Cms\Models\Page::navItems() as $item)
        <a href="{{ $item->url }}">{{ $item->tr('title') }}</a>
    @endforeach
    <a href="/docs">API</a>
</nav>
BODY;

        $head = <<<'BODY'
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($metaTitle ?? null) ?: $title }} — {{ $appName }}</title>
    @if (!empty($metaDescription))<meta name="description" content="{{ $metaDescription }}">@endif
    @if (!empty($metaKeywords))<meta name="keywords" content="{{ $metaKeywords }}">@endif
    @foreach (\App\Modules\Cms\Models\Page::LOCALES as $l)
        <link rel="alternate" hreflang="{{ $l }}" href="{{ $page->urlFor($l) }}">
    @endforeach
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
BODY;

        DB::table('templates')->where('slug', 'header')->update(['body' => $header]);
        DB::table('templates')->where('slug', 'menu')->update(['body' => $menu]);
        DB::table('templates')->where('slug', 'head')->update(['body' => $head]);

        // Главная: язык в <html> и локализованные заголовки дочерних страниц
        $home = DB::table('templates')->where('slug', 'home')->value('body');
        if ($home !== null) {
            $home = str_replace('<html lang="ru">', '<html lang="{{ $locale ?? \'en\' }}">', $home);
            $home = str_replace('{{ $child->title }}', '{{ $child->tr(\'title\') }}', $home);
            DB::table('templates')->where('slug', 'home')->update(['body' => $home]);
        }
    }

    public function down(): void
    {
        // Шаблоны правятся в админке — откат не предусмотрен.
    }
};
