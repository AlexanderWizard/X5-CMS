<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Шрифт сайта — Poppins (как на strapi.io). Подключаем Google Fonts в системном
 * шаблоне `head` перед landing.css; сам font-family задан в landing.scss/landing.css.
 */
return new class extends Migration
{
    private const LINKS = '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n"
        . ' <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n"
        . ' <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">';

    public function up(): void
    {
        $head = DB::table('templates')->where('slug', 'head')->value('body');

        if ($head !== null && ! str_contains($head, 'family=Poppins')) {
            // Вставляем ссылки на шрифт прямо перед подключением landing.css.
            $head = str_replace(
                '<link rel="stylesheet" href="{{ asset(\'css/landing.css\')',
                self::LINKS . "\n <link rel=\"stylesheet\" href=\"{{ asset('css/landing.css')",
                $head
            );
            DB::table('templates')->where('slug', 'head')->update(['body' => $head]);
        }
    }

    public function down(): void
    {
        $head = DB::table('templates')->where('slug', 'head')->value('body');

        if ($head !== null) {
            $head = str_replace(self::LINKS . "\n ", '', $head);
            DB::table('templates')->where('slug', 'head')->update(['body' => $head]);
        }
    }
};
