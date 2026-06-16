<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Хлебные крошки на сайте: системный частичный шаблон `breadcrumbs`
 * (@partial('breadcrumbs')) + подключение в шаблоне `default` после шапки.
 * Использует $page->ancestorsTrail() и локализованные заголовки $crumb->tr('title').
 */
return new class extends Migration
{
    public function up(): void
    {
        $body = <<<'BODY'
@if (! $page->is_home)
<nav class="breadcrumbs" aria-label="breadcrumbs">
    <div class="wrap">
        <a href="{{ url($locale ?? 'en') }}">{{ ($locale ?? '') === 'ru' ? 'Главная' : 'Home' }}</a>
        @foreach ($page->ancestorsTrail() as $crumb)
            <span class="sep">/</span>
            @if ($loop->last)
                <span class="current">{{ $crumb->tr('title') }}</span>
            @else
                <a href="{{ $crumb->url }}">{{ $crumb->tr('title') }}</a>
            @endif
        @endforeach
    </div>
</nav>
@endif
BODY;

        // Системный частичный шаблон (idempotent).
        DB::table('templates')->updateOrInsert(
            ['slug' => 'breadcrumbs'],
            [
                'name'       => 'Хлебные крошки',
                'is_system'  => 1,
                'body'       => $body,
                'created_at' => now(),
            ]
        );

        // Подключаем в шаблоне default сразу после шапки (один раз).
        $default = DB::table('templates')->where('slug', 'default')->value('body');
        if ($default !== null && ! str_contains($default, "@partial('breadcrumbs')")) {
            $default = str_replace(
                "@partial('header')",
                "@partial('header')\n @partial('breadcrumbs')",
                $default
            );
            DB::table('templates')->where('slug', 'default')->update(['body' => $default]);
        }
    }

    public function down(): void
    {
        $default = DB::table('templates')->where('slug', 'default')->value('body');
        if ($default !== null) {
            $default = str_replace("@partial('header')\n @partial('breadcrumbs')", "@partial('header')", $default);
            DB::table('templates')->where('slug', 'default')->update(['body' => $default]);
        }

        DB::table('templates')->where('slug', 'breadcrumbs')->delete();
    }
};
