<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Флаг системного шаблона
        $hasCol = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'templates' AND column_name = 'is_system'",
            [config('database.connections.mysql.database')]
        );

        if (!$hasCol[0]->cnt) {
            DB::statement("ALTER TABLE `templates` ADD COLUMN `is_system` TINYINT(1) NOT NULL DEFAULT 0 AFTER `slug`");
        }

        // Системные частичные шаблоны
        $partials = [
            'header' => ['name' => 'Шапка сайта', 'body' => $this->headerBody()],
            'menu'   => ['name' => 'Меню',        'body' => $this->menuBody()],
            'footer' => ['name' => 'Подвал сайта','body' => $this->footerBody()],
        ];

        foreach ($partials as $slug => $data) {
            if (!DB::table('templates')->where('slug', $slug)->exists()) {
                DB::table('templates')->insert([
                    'name'      => $data['name'],
                    'slug'      => $slug,
                    'is_system' => 1,
                    'body'      => $data['body'],
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('templates')->whereIn('slug', ['header', 'menu', 'footer'])->delete();
        DB::statement("ALTER TABLE `templates` DROP COLUMN IF EXISTS `is_system`");
    }

    private function headerBody(): string
    {
        return <<<'BLADE'
<header class="nav">
    <div class="wrap">
        <a href="{{ url('/') }}" class="brand"><span class="logo"></span> {{ $appName }}</a>
        @partial('menu')
        <a href="/admin" class="btn btn-primary">Войти</a>
    </div>
</header>
BLADE;
    }

    private function menuBody(): string
    {
        return <<<'BLADE'
<nav class="nav-links">
    <a href="{{ url('/') }}">Главная</a>
    <a href="#features">Возможности</a>
    <a href="/admin">Админка</a>
    <a href="/docs">API</a>
</nav>
BLADE;
    }

    private function footerBody(): string
    {
        return <<<'BLADE'
<footer class="site">
    <div class="wrap">
        <span>© {{ date('Y') }} {{ $appName }}</span>
        <span>Сделано на Laravel + Filament</span>
    </div>
</footer>
BLADE;
    }
};
