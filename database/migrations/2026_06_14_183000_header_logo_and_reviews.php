<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * 1) Шапка сайта: CSS-квадрат логотипа → SVG-тайл с вырезом «X5» (logo-mark.svg).
 * 2) Главная: добавлен блок «Отзывы» (социальное доказательство) перед CTA.
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Логотип в шапке ---
        $header = <<<'BODY'
<header class="nav">
    <div class="wrap">
        <a href="{{ url('/') }}" class="brand"><img class="logo" src="{{ asset('images/logo-mark.svg') }}" alt="{{ $appName }}"> {{ $appName }}</a>
        @partial('menu')
        <a href="/admin" class="btn btn-primary">Войти</a>
    </div>
</header>
BODY;
        DB::table('templates')->where('slug', 'header')->update(['body' => $header]);

        // --- Отзывы (вставляем перед секцией CTA) ---
        $reviews = <<<'BODY'
    <section class="reviews">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Отзывы</div>
                <h2>Запускают сайты за вечер</h2>
                <p>Команды собирают и ведут сайты диалогом с ИИ — без очереди к разработчику.</p>
            </div>
            <div class="grid">
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>«Собрал лендинг и блог за один вечер — просто описал, что нужно. ИИ сделал страницы, шаблоны и переводы.»</p>
                    <div class="who"><div class="av">АМ</div><div><b>Алексей Морозов</b><span>основатель студии</span></div></div>
                </div>
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>«Перенесли корпоративный сайт без программиста. Контент теперь правит контент-менеджер прямо в панели.»</p>
                    <div class="who"><div class="av">ИК</div><div><b>Ирина Ковалёва</b><span>руководитель маркетинга</span></div></div>
                </div>
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>«Роли, журнал действий, файрвол по IP — всё из коробки. Безопасность и аудит закрыли в первый день.»</p>
                    <div class="who"><div class="av">ДВ</div><div><b>Дмитрий Власов</b><span>тимлид</span></div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
BODY;

        $home = DB::table('templates')->where('slug', 'home')->value('body');

        if ($home !== null && ! str_contains($home, 'class="reviews"')) {
            $home = str_replace('    <section class="cta">', $reviews, $home);
            DB::table('templates')->where('slug', 'home')->update(['body' => $home]);
        }
    }

    public function down(): void
    {
        // Контент шаблонов правится в админке — откат не предусмотрен.
    }
};
