<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Чинит обёртку формы обратной связи: предыдущая миграция добавила @if, но
 * @endif не вставился (не совпал маркер пробелов). Вставляем @endif перед
 * секцией CTA, если открытый @if есть, а закрывающего нет.
 */
return new class extends Migration
{
    private const MARKER = '    <section class="cta">';

    public function up(): void
    {
        $body = DB::table('templates')->where('slug', 'home')->value('body');

        if ($body === null) {
            return;
        }

        $hasIf  = str_contains($body, "Setting::bool('feedback_enabled'");
        $ifs    = substr_count($body, '@if');
        $endifs = substr_count($body, '@endif');

        // Обёртка есть, но @endif не хватает — добавляем перед CTA.
        if ($hasIf && $endifs < $ifs && str_contains($body, self::MARKER)) {
            $body = preg_replace(
                '/' . preg_quote(self::MARKER, '/') . '/',
                "    @endif\n\n" . self::MARKER,
                $body,
                1,
            );

            DB::table('templates')->where('slug', 'home')->update(['body' => $body]);
        }
    }

    public function down(): void
    {
        // Контент шаблона редактируется в админке — откат не предусмотрен.
    }
};
