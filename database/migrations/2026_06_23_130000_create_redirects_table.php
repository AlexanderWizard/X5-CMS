<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица 301/302-редиректов (модуль CMS).
 *
 * from_path / to_path — БЕЗ языкового префикса (slug страниц общий для всех
 * локалей), напр. "about-old" → "about". Пустой to_path = главная.
 * Применяется middleware HandleRedirects внутри языковой группы.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_path', 191)->unique();
            $table->string('to_path', 191)->default('');
            $table->unsignedSmallInteger('status')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
