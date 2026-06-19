<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Переключает системный шаблон `footer` на рендер из конструктора (footer_columns/links).
 * Низ подвала (копирайт + @block('footer_made')) сохраняется.
 */
return new class extends Migration
{
    private const NEW_BODY = <<<'BLADE'
<footer class="site">
@php($footerCols = \App\Modules\Cms\Models\FooterColumn::activeWithLinks())
 @if ($footerCols->isNotEmpty())
 <div class="wrap footer-cols">
 @foreach ($footerCols as $col)
 <div class="footer-col">
 <h4>{{ $col->tr('title', $locale ?? null) }}</h4>
 <ul>
 @foreach ($col->links as $link)
 <li><a href="{{ $link->resolvedUrl($locale ?? 'en') }}"@if ($link->new_tab) target="_blank" rel="noopener"@endif>{{ $link->tr('title', $locale ?? null) }}</a></li>
 @endforeach
 </ul>
 </div>
 @endforeach
 </div>
 @endif
 <div class="wrap footer-bottom">
 <span>© {{ date('Y') }} {{ $appName }}</span>
 <span>@block('footer_made')</span>
 </div>
</footer>
BLADE;

    private const OLD_BODY = <<<'BLADE'
<footer class="site">
 <div class="wrap">
 <span>© {{ date('Y') }} {{ $appName }}</span>
 <span>@block('footer_made')</span>
 </div>
</footer>
BLADE;

    public function up(): void
    {
        DB::table('templates')->where('slug', 'footer')->update(['body' => self::NEW_BODY]);
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'footer')->update(['body' => self::OLD_BODY]);
    }
};
