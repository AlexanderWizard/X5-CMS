<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Текст футера «Сделано на Laravel + Filament» → мультиязычный блок footer_made;
 * в шаблоне footer остаётся только разметка.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('blocks')->updateOrInsert(
            ['slug' => 'footer_made'],
            [
                'name'       => 'Футер · подпись',
                'value'      => 'Built with Laravel + Filament',
                'i18n'       => json_encode([
                    'en' => 'Built with Laravel + Filament',
                    'ru' => 'Сделано на Laravel + Filament',
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
            ]
        );

        $footer = <<<'BODY'
<footer class="site">
    <div class="wrap">
        <span>© {{ date('Y') }} {{ $appName }}</span>
        <span>@block('footer_made')</span>
    </div>
</footer>
BODY;

        DB::table('templates')->where('slug', 'footer')->update(['body' => $footer]);
    }

    public function down(): void
    {
        // Контент правится в админке — откат не предусмотрен.
    }
};
