<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasCol = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'templates' AND column_name = 'is_default'",
            [config('database.connections.mysql.database')]
        );

        if (!$hasCol[0]->cnt) {
            DB::statement("ALTER TABLE `templates` ADD COLUMN `is_default` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_system`");
        }

        // Дефолтный шаблон обычной страницы (виден в списке выбора, удалять нельзя)
        if (!DB::table('templates')->where('slug', 'default')->exists()) {
            DB::table('templates')->insert([
                'name'       => 'Стандартная страница',
                'slug'       => 'default',
                'is_system'  => 0,
                'is_default' => 1,
                'body'       => $this->defaultBody(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'default')->delete();
        DB::statement("ALTER TABLE `templates` DROP COLUMN IF EXISTS `is_default`");
    }

    private function defaultBody(): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html lang="ru">
@partial('head')
<body>
    @partial('header')

    <section class="content" id="content">
        <div class="wrap">
            <div class="card">
                <h1 style="margin-top:0;">{{ $title }}</h1>
                @if (filled($content))
                    {!! $content !!}
                @else
                    <p class="placeholder">Содержимое страницы пока не заполнено.</p>
                @endif
            </div>
        </div>
    </section>

    @if ($children->isNotEmpty())
        <section class="subpages">
            <div class="wrap">
                <h2>Разделы</h2>
                <div class="grid">
                    @foreach ($children as $child)
                        <a href="{{ $child->url }}">{{ $child->title }} →</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @partial('footer')
</body>
</html>
BLADE;
    }
};
