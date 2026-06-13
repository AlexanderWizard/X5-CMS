<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Таблица шаблонов
        DB::statement("
            CREATE TABLE IF NOT EXISTS `templates` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`       VARCHAR(191)    NOT NULL,
                `slug`       VARCHAR(191)    NOT NULL,
                `body`       LONGTEXT        NULL DEFAULT NULL,
                `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `templates_slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // 2. Шаблон home — ранее созданный лендинг главной страницы.
        //    Внутри — шаблонный код вывода контента страницы: {!! $content !!}
        $homeBody = $this->homeTemplateBody();

        $exists = DB::table('templates')->where('slug', 'home')->exists();

        if (!$exists) {
            DB::table('templates')->insert([
                'name' => 'Главная (лендинг)',
                'slug' => 'home',
                'body' => $homeBody,
            ]);
        }

        // 3. Связь страниц с шаблоном
        $hasColumn = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'pages' AND column_name = 'template_id'",
            [config('database.connections.mysql.database')]
        );

        if (!$hasColumn[0]->cnt) {
            DB::statement("ALTER TABLE `pages` ADD COLUMN `template_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `parent_id`");
            DB::statement("ALTER TABLE `pages` ADD CONSTRAINT `pages_template_id_fk` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL");
        }

        // 4. Назначаем главной странице шаблон home
        $homeTemplateId = DB::table('templates')->where('slug', 'home')->value('id');

        if ($homeTemplateId) {
            DB::table('pages')->where('is_home', 1)->update(['template_id' => $homeTemplateId]);
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `pages` DROP FOREIGN KEY `pages_template_id_fk`");
        DB::statement("ALTER TABLE `pages` DROP COLUMN IF EXISTS `template_id`");
        DB::statement("DROP TABLE IF EXISTS `templates`");
    }

    /**
     * Тело шаблона home (Blade-разметка лендинга).
     * Доступные переменные при рендере: $title, $content, $children, $page, $appName.
     */
    private function homeTemplateBody(): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        :root { --accent: #ea580c; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif; color: #1f2937; background: #f9fafb; line-height: 1.6; }
        header.site { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .wrap { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; }
        header.site .wrap { display: flex; align-items: center; gap: .75rem; height: 64px; }
        .brand { display: flex; align-items: center; gap: .5rem; font-weight: 700; font-size: 1.1rem; color: #111827; text-decoration: none; }
        .brand .dot { width: 22px; height: 22px; border-radius: 6px; background: var(--accent); display: inline-block; }
        nav.crumbs { font-size: .85rem; color: #6b7280; padding: 1rem 0 0; }
        nav.crumbs a { color: var(--accent); text-decoration: none; }
        nav.crumbs a:hover { text-decoration: underline; }
        main { padding: 1.5rem 0 4rem; }
        h1.page-title { font-size: 2rem; font-weight: 800; color: #111827; margin: .5rem 0 1.5rem; }
        article.content { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        article.content img { max-width: 100%; height: auto; border-radius: 8px; }
        .children { margin-top: 2rem; }
        .children h2 { font-size: 1rem; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; }
        .children ul { list-style: none; padding: 0; margin: 0; display: grid; gap: .5rem; }
        .children a { display: block; padding: .85rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; text-decoration: none; color: #111827; font-weight: 600; transition: border-color .15s, transform .15s; }
        .children a:hover { border-color: var(--accent); transform: translateY(-1px); }
        footer.site { text-align: center; color: #9ca3af; font-size: .85rem; padding: 2rem 0; }
        .empty { color: #9ca3af; font-style: italic; }
    </style>
</head>
<body>
    <header class="site">
        <div class="wrap">
            <a href="{{ url('/') }}" class="brand"><span class="dot"></span> {{ $appName }}</a>
        </div>
    </header>

    <div class="wrap">
        @unless ($page->is_home)
            <nav class="crumbs">
                <a href="{{ url('/') }}">Главная</a>
                @foreach ($page->ancestorsTrail() as $crumb)
                    / @if ($loop->last){{ $crumb->title }}@else<a href="{{ $crumb->url }}">{{ $crumb->title }}</a>@endif
                @endforeach
            </nav>
        @endunless

        <main>
            <h1 class="page-title">{{ $title }}</h1>

            <article class="content">
                @if (filled($content))
                    {!! $content !!}
                @else
                    <p class="empty">Содержимое страницы пока не заполнено.</p>
                @endif
            </article>

            @if ($children->isNotEmpty())
                <section class="children">
                    <h2>Разделы</h2>
                    <ul>
                        @foreach ($children as $child)
                            <li><a href="{{ $child->url }}">{{ $child->title }}</a></li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </main>
    </div>

    <footer class="site">© {{ date('Y') }} {{ $appName }}</footer>
</body>
</html>
BLADE;
    }
};
