<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Глобальные настройки сайта (key-value).
 * Источник правды — таблица settings; чтение в рантайме через
 * App\Modules\System\Models\Setting (кэш на запрос).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->longText('value')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        $now = now();
        $defaults = [
            'site_name'           => config('app.name', 'Site'),
            'site_tagline'        => '',
            'seo_title_suffix'    => '',
            'seo_default_description' => '',
            'seo_default_keywords'    => '',
            'seo_index'           => '1',
            'maintenance_mode'    => '0',
            'maintenance_message' => 'Сайт временно на обслуживании. Скоро вернёмся.',
            'contact_email'       => '',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'created_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
