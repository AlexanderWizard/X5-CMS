<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Языки сайта — управляются в админке (раздел «Языки»).
 * Источник правды для списка локалей (URL /{code}, переключатель, вкладки контента).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();   // en, ru, de, ...
            $table->string('name', 64);            // English, Русский
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        $now = now();
        DB::table('languages')->insert([
            ['code' => 'en', 'name' => 'English', 'is_default' => 1, 'is_active' => 1, 'sort_order' => 1, 'created_at' => $now],
            ['code' => 'ru', 'name' => 'Русский', 'is_default' => 0, 'is_active' => 1, 'sort_order' => 2, 'created_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
