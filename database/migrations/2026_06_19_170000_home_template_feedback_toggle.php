<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Оборачивает секцию формы обратной связи в шаблоне главной в условие
 * @if (Setting::bool('feedback_enabled')) — форму можно выключить в настройках.
 */
return new class extends Migration
{
    private const OPEN  = '    <section class="feedback" id="feedback">';
    private const CLOSE = "    </section>\n\n    <section class=\"cta\">";

    public function up(): void
    {
        $body = DB::table('templates')->where('slug', 'home')->value('body');

        if ($body === null || str_contains($body, "Setting::bool('feedback_enabled')")) {
            return;
        }

        $body = str_replace(
            self::OPEN,
            "@if (\\App\\Modules\\System\\Models\\Setting::bool('feedback_enabled', true))\n" . self::OPEN,
            $body,
        );

        $body = str_replace(
            self::CLOSE,
            "    </section>\n    @endif\n\n    <section class=\"cta\">",
            $body,
        );

        DB::table('templates')->where('slug', 'home')->update(['body' => $body]);
    }

    public function down(): void
    {
        // Контент шаблона редактируется в админке — откат не предусмотрен.
    }
};
