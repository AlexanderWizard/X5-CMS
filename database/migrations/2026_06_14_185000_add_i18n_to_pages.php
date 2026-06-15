<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Мультиязычный контент страниц: per-locale заголовок/контент/SEO в JSON-колонке
 * pages.i18n (MySQL 5.6 не имеет JSON-типа → longText + array-cast в модели).
 * Структура: { "en": {title,content,meta_title,meta_description,meta_keywords}, "ru": {...} }
 * Бэкфилл: текущие поля копируются в обе локали как стартовая точка.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('i18n')->nullable()->after('content');
        });

        foreach (DB::table('pages')->get() as $page) {
            $fields = [
                'title'            => $page->title,
                'content'          => $page->content,
                'meta_title'       => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_keywords'    => $page->meta_keywords,
            ];

            $i18n = ['en' => $fields, 'ru' => $fields];

            DB::table('pages')->where('id', $page->id)->update([
                'i18n' => json_encode($i18n, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('i18n');
        });
    }
};
