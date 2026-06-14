<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица переводов интерфейса (i18n в БД).
 * Источник правды для строк интерфейса — эта таблица, а не lang/*.php.
 * Загрузка в рантайме: App\Modules\System\Support\DatabaseTranslationLoader.
 * Начальный сид — database/data/admin_translations.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('group', 64)->default('admin');
            $table->string('key', 191);
            $table->string('locale', 5);
            $table->text('value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['group', 'key', 'locale']);
            $table->index(['locale', 'group']);
        });

        $rows = require database_path('data/admin_translations.php');
        $now  = now();

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('translations')->insert(array_map(
                fn (array $r) => $r + ['created_at' => $now],
                $chunk
            ));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
