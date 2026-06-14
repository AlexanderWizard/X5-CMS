<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Анимация AI-консоли на главной: промпт печатается посимвольно (typewriter),
 * затем строки ответа появляются по очереди. Прогрессивное улучшение:
 * без JS всё видно статично; уважается prefers-reduced-motion.
 */
return new class extends Migration
{
    public function up(): void
    {
        $body = <<<'BODY'
<!DOCTYPE html>
<html lang="ru">
@partial('head')
<body>
    @partial('header')

    <section class="hero">
        <div class="wrap">
            <div class="hero-copy">
                <span class="badge anim d1"><span class="dot"></span> {{ $appName }} — CMS, созданная и управляемая ИИ</span>
                <h1 class="anim d2">Сайт, который<br><span class="grad">создаёт и ведёт ИИ</span></h1>
                <p class="lead anim d3">Опишите страницу словами — искусственный интеллект соберёт структуру, шаблоны и контент. Правьте, переводите и публикуйте без кода. Само ядро CMS тоже написано ИИ.</p>
                <div class="actions anim d4">
                    <a href="/admin" class="btn btn-primary">Начать с ИИ →</a>
                    <a href="#features" class="btn btn-ghost">Возможности</a>
                </div>
                <div class="trust anim d5">
                    <span>AI-генерация</span><span>Без кода</span><span>Filament v4</span><span>i18n</span><span>Laravel 13</span>
                </div>
            </div>
            <div class="hero-visual anim d3">
                <div class="code-card">
                    <div class="bar"><i></i><i></i><i></i><small>X5 AI · console</small></div>
<pre><span class="ai">✦ X5 AI</span>

<span class="u">› <span class="tw">Создай страницу «Тарифы» с тремя планами</span><span class="cur">▍</span></span>

<span class="line"><span class="ok">✓</span> Структура страницы готова</span>
<span class="line"><span class="ok">✓</span> Шаблон <span class="k">pricing</span> сгенерирован</span>
<span class="line"><span class="ok">✓</span> Переводы ru · en добавлены</span>
<span class="line"><span class="ok">✓</span> Опубликовано <span class="m">→ /tariffs</span></span></pre>
                </div>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="wrap">
            <div class="stat"><b>AI</b><span>генерация страниц</span></div>
            <div class="stat"><b>0</b><span>строк кода руками</span></div>
            <div class="stat"><b>i18n</b><span>ru · en из коробки</span></div>
            <div class="stat"><b>100%</b><span>собрано и ведётся ИИ</span></div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Возможности</div>
                <h2>CMS, в которой работает ИИ</h2>
                <p>Создавайте и развивайте сайт диалогом — рутину берёт на себя искусственный интеллект.</p>
            </div>
            <div class="grid">
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7L12 3z"/><path d="M19 14l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8 .8-2z"/></svg></div>
                    <h3>AI-генерация страниц</h3>
                    <p>Опишите задачу словами — ИИ создаст страницу, разметку и тексты.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M5 19L15 9"/><path d="M17 3l.9 2.1L20 6l-2.1.9L17 9l-.9-2.1L14 6l2.1-.9L17 3z"/></svg></div>
                    <h3>AI-шаблоны</h3>
                    <p>Шаблоны вывода генерируются и правятся подсказками на лету.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 9v12"/></svg></div>
                    <h3>Контент без кода</h3>
                    <p>Дерево страниц, блоки и меню — визуально из панели, без разработчика.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.6 2.7 2.6 15.3 0 18"/><path d="M12 3c-2.6 2.7-2.6 15.3 0 18"/></svg></div>
                    <h3>Авто-переводы</h3>
                    <p>Интерфейс и контент на ru/en; строки редактируются прямо в админке.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg></div>
                    <h3>Роли и доступ</h3>
                    <p>Гибкие права по модулям, файрвол по IP и полный аудит действий.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg></div>
                    <h3>Журнал действий</h3>
                    <p>Каждое изменение — человека и ИИ — фиксируется с фильтрами по событиям.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="steps">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Как это работает</div>
                <h2>Три шага — и сайт готов</h2>
                <p>Опишите идею — остальное соберёт искусственный интеллект.</p>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="n">1</div>
                    <h3>Опишите словами</h3>
                    <p>«Лендинг услуг с тарифами и формой заявки» — обычным языком.</p>
                </div>
                <div class="step">
                    <div class="n">2</div>
                    <h3>ИИ собирает</h3>
                    <p>Страницы, шаблоны, блоки и переводы создаются автоматически.</p>
                </div>
                <div class="step">
                    <div class="n">3</div>
                    <h3>Публикуете</h3>
                    <p>Правите мелочи в панели и жмёте «Опубликовать». Готово.</p>
                </div>
            </div>
        </div>
    </section>

    @if (filled($content))
        <section class="content" id="content">
            <div class="wrap">
                <div class="card">{!! $content !!}</div>
            </div>
        </section>
    @endif

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

    <section class="cta">
        <div class="wrap">
            <div class="band">
                <h2>Готовы собрать сайт силами ИИ?</h2>
                <p>Опишите идею — X5-CMS соберёт страницы, шаблоны и переводы за вас.</p>
                <div class="actions">
                    <a href="/admin" class="btn btn-primary">Начать с ИИ →</a>
                    <a href="/docs" class="btn btn-ghost">Документация</a>
                </div>
            </div>
        </div>
    </section>

    @partial('footer')

    <script>
    (function () {
        var card = document.querySelector('.code-card');
        if (!card) return;
        var tw = card.querySelector('.tw');
        var cur = card.querySelector('.cur');
        var lines = card.querySelectorAll('.line');
        if (!tw) return;
        var text = tw.textContent;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        function run() {
            card.classList.add('tw-on');
            tw.textContent = '';
            for (var j = 0; j < lines.length; j++) lines[j].classList.remove('show');
            var i = 0;
            (function type() {
                tw.textContent = text.slice(0, i);
                if (i < text.length) { i++; setTimeout(type, 42 + Math.random() * 45); return; }
                for (var k = 0; k < lines.length; k++) {
                    (function (el, idx) { setTimeout(function () { el.classList.add('show'); }, 420 + idx * 430); })(lines[k], k);
                }
                setTimeout(run, 420 + lines.length * 430 + 4200);
            })();
        }

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { io.disconnect(); run(); } });
            io.observe(card);
        } else {
            run();
        }
    })();
    </script>
</body>
</html>
BODY;

        DB::table('templates')->where('slug', 'home')->update(['body' => $body]);
    }

    public function down(): void
    {
        // Контент главной — откат не предусмотрен (правится в админке).
    }
};
