<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Переключает системный шаблон `menu` на рендер из конструктора (menu_items).
 * Старое тело сохраняем в свойстве down() для отката.
 */
return new class extends Migration
{
    private const NEW_BODY = <<<'BLADE'
<nav class="nav-links">
@foreach (\App\Modules\Cms\Models\MenuItem::topMenu() as $item)
 <a href="{{ $item->resolvedUrl($locale ?? 'en') }}"@if ($item->new_tab) target="_blank" rel="noopener"@endif>{{ $item->tr('title', $locale ?? null) }}</a>
@endforeach
</nav>
BLADE;

    private const OLD_BODY = <<<'BLADE'
<nav class="nav-links">
 <a href="{{ url($locale ?? 'en') }}">{{ ($locale ?? '') === 'en' ? 'Home' : 'Главная' }}</a>
 @foreach (\App\Modules\Cms\Models\Page::navItems() as $item)
 <a href="{{ $item->url }}">{{ $item->tr('title') }}</a>
 @endforeach
 <a href="{{ url(($locale ?? 'en').'/blog') }}">{{ ($locale ?? '') === 'en' ? 'Blog' : 'Блог' }}</a>
 <a href="/docs">API</a>
</nav>
BLADE;

    public function up(): void
    {
        DB::table('templates')->where('slug', 'menu')->update(['body' => self::NEW_BODY]);
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'menu')->update(['body' => self::OLD_BODY]);
    }
};
