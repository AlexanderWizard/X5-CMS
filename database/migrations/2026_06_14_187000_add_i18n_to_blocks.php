<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Мультиязычные блоки: значение @block('slug') хранится per-locale в blocks.i18n
 * (longText + array-cast; MySQL 5.6 без JSON-типа). Структура: { "en": "...", "ru": "..." }.
 * Бэкфилл: текущее value копируется в обе локали.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->longText('i18n')->nullable()->after('value');
        });

        foreach (DB::table('blocks')->get() as $block) {
            DB::table('blocks')->where('id', $block->id)->update([
                'i18n' => json_encode(['en' => $block->value, 'ru' => $block->value], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('i18n');
        });
    }
};
