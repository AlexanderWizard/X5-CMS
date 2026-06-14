<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('templates')->where('slug', 'menu')->update([
            'body' => $this->menuBody(),
        ]);
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'menu')->update([
            'body' => $this->staticMenuBody(),
        ]);
    }

    /**
     * Динамическое меню — список страниц из БД.
     */
    private function menuBody(): string
    {
        return <<<'BLADE'
<nav class="nav-links">
    <a href="{{ url('/') }}">Главная</a>
    @foreach (\App\Modules\Cms\Models\Page::navItems() as $item)
        <a href="{{ $item->url }}">{{ $item->title }}</a>
    @endforeach
    <a href="/docs">API</a>
</nav>
BLADE;
    }

    private function staticMenuBody(): string
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
};
