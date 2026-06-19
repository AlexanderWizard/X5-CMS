<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Добавляет ссылку «Блог» в общий шаблон меню сайта (перед ссылкой API).
 */
return new class extends Migration
{
    private const LINK = "    <a href=\"{{ url((\$locale ?? 'en').'/blog') }}\">{{ (\$locale ?? '') === 'en' ? 'Blog' : 'Блог' }}</a>\n";

    public function up(): void
    {
        $body = DB::table('templates')->where('slug', 'menu')->value('body');

        if ($body === null || str_contains($body, "'/blog'")) {
            return;
        }

        // Вставляем ссылку перед пунктом API (<a href="/docs">API</a>).
        $anchor  = '<a href="/docs">API</a>';
        $updated = str_contains($body, $anchor)
            ? str_replace($anchor, ltrim(self::LINK) . ' ' . $anchor, $body)
            : str_replace('</nav>', self::LINK . '</nav>', $body);

        DB::table('templates')->where('slug', 'menu')->update(['body' => $updated]);
    }

    public function down(): void
    {
        $body = DB::table('templates')->where('slug', 'menu')->value('body');

        if ($body === null) {
            return;
        }

        $updated = preg_replace('/[ \t]*<a href="\{\{ url\(\(\$locale \?\? \'en\'\)\.\'\/blog\'\) \}\}">.*?<\/a>\n?/', '', $body);

        DB::table('templates')->where('slug', 'menu')->update(['body' => $updated]);
    }
};
