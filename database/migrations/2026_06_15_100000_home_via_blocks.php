<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Главная страница собирается из мультиязычных текстовых блоков (home_*).
 * В шаблоне home остаётся только дизайн (разметка) + @block('home_*').
 * Блоки переведены (en/ru) — лендинг мультиязычен автоматически.
 */
return new class extends Migration
{
    public function up(): void
    {
        // slug => [name (метка в админке), ru, en]
        $blocks = [
            'home_hero_badge'   => ['Главная · бейдж', 'CMS, созданная и управляемая ИИ', 'an AI-built, AI-run CMS'],
            'home_hero_title1'  => ['Главная · заголовок 1', 'Сайт, который', 'A website'],
            'home_hero_title2'  => ['Главная · заголовок 2 (градиент)', 'создаёт и ведёт ИИ', 'built and run by AI'],
            'home_hero_lead'    => ['Главная · лид', 'Опишите страницу словами — искусственный интеллект соберёт структуру, шаблоны и контент. Правьте, переводите и публикуйте без кода. Само ядро CMS тоже написано ИИ.', 'Describe a page in plain words — AI assembles the structure, templates and content. Edit, translate and publish without code. The CMS core itself is written by AI.'],
            'home_btn_start'    => ['Главная · кнопка «Начать»', 'Начать с ИИ →', 'Start with AI →'],
            'home_btn_features' => ['Главная · кнопка «Возможности»', 'Возможности', 'Features'],
            'home_trust1'       => ['Главная · доверие 1', 'AI-генерация', 'AI generation'],
            'home_trust2'       => ['Главная · доверие 2', 'Без кода', 'No code'],

            'home_console_prompt' => ['Главная · консоль · промпт', 'Создай страницу «Тарифы» с тремя планами', 'Create a “Pricing” page with three plans'],
            'home_console1' => ['Главная · консоль · строка 1', 'Структура страницы готова', 'Page structure ready'],
            'home_console2' => ['Главная · консоль · строка 2', 'Шаблон pricing сгенерирован', 'pricing template generated'],
            'home_console3' => ['Главная · консоль · строка 3', 'Переводы ru · en добавлены', 'ru · en translations added'],
            'home_console4' => ['Главная · консоль · строка 4', 'Опубликовано', 'Published'],

            'home_stat1' => ['Главная · метрика 1', 'генерация страниц', 'page generation'],
            'home_stat2' => ['Главная · метрика 2', 'строк кода руками', 'lines of code by hand'],
            'home_stat3' => ['Главная · метрика 3', 'ru · en из коробки', 'ru · en out of the box'],
            'home_stat4' => ['Главная · метрика 4', 'собрано и ведётся ИИ', 'built & run by AI'],

            'home_feat_eyebrow' => ['Главная · возможности · надзаголовок', 'Возможности', 'Features'],
            'home_feat_h2'      => ['Главная · возможности · заголовок', 'CMS, в которой работает ИИ', 'A CMS with AI inside'],
            'home_feat_sub'     => ['Главная · возможности · подзаголовок', 'Создавайте и развивайте сайт диалогом — рутину берёт на себя искусственный интеллект.', 'Build and grow your site by chatting — AI handles the routine.'],

            'home_f1_t' => ['Главная · фича 1 · заголовок', 'AI-генерация страниц', 'AI page generation'],
            'home_f1_d' => ['Главная · фича 1 · текст', 'Опишите задачу словами — ИИ создаст страницу, разметку и тексты.', 'Describe the task — AI creates the page, markup and copy.'],
            'home_f2_t' => ['Главная · фича 2 · заголовок', 'AI-шаблоны', 'AI templates'],
            'home_f2_d' => ['Главная · фича 2 · текст', 'Шаблоны вывода генерируются и правятся подсказками на лету.', 'Output templates are generated and tweaked with prompts on the fly.'],
            'home_f3_t' => ['Главная · фича 3 · заголовок', 'Контент без кода', 'No-code content'],
            'home_f3_d' => ['Главная · фича 3 · текст', 'Дерево страниц, блоки и меню — визуально из панели, без разработчика.', 'Page tree, blocks and menus — visually from the panel, no developer.'],
            'home_f4_t' => ['Главная · фича 4 · заголовок', 'Авто-переводы', 'Auto-translations'],
            'home_f4_d' => ['Главная · фича 4 · текст', 'Интерфейс и контент на ru/en; строки редактируются прямо в админке.', 'UI and content in ru/en; strings editable right in the admin.'],
            'home_f5_t' => ['Главная · фича 5 · заголовок', 'Роли и доступ', 'Roles & access'],
            'home_f5_d' => ['Главная · фича 5 · текст', 'Гибкие права по модулям, файрвол по IP и полный аудит действий.', 'Granular per-module permissions, IP firewall and full audit log.'],
            'home_f6_t' => ['Главная · фича 6 · заголовок', 'Журнал действий', 'Action log'],
            'home_f6_d' => ['Главная · фича 6 · текст', 'Каждое изменение — человека и ИИ — фиксируется с фильтрами по событиям.', 'Every change — by humans and AI — is logged with event filters.'],

            'home_steps_eyebrow' => ['Главная · шаги · надзаголовок', 'Как это работает', 'How it works'],
            'home_steps_h2'      => ['Главная · шаги · заголовок', 'Три шага — и сайт готов', 'Three steps to a live site'],
            'home_steps_sub'     => ['Главная · шаги · подзаголовок', 'Опишите идею — остальное соберёт искусственный интеллект.', 'Describe the idea — AI assembles the rest.'],
            'home_s1_t' => ['Главная · шаг 1 · заголовок', 'Опишите словами', 'Describe it'],
            'home_s1_d' => ['Главная · шаг 1 · текст', '«Лендинг услуг с тарифами и формой заявки» — обычным языком.', '“A services landing with pricing and a lead form” — in plain language.'],
            'home_s2_t' => ['Главная · шаг 2 · заголовок', 'ИИ собирает', 'AI assembles'],
            'home_s2_d' => ['Главная · шаг 2 · текст', 'Страницы, шаблоны, блоки и переводы создаются автоматически.', 'Pages, templates, blocks and translations are created automatically.'],
            'home_s3_t' => ['Главная · шаг 3 · заголовок', 'Публикуете', 'Publish'],
            'home_s3_d' => ['Главная · шаг 3 · текст', 'Правите мелочи в панели и жмёте «Опубликовать». Готово.', 'Tweak details in the panel and hit Publish. Done.'],

            'home_rev_eyebrow' => ['Главная · отзывы · надзаголовок', 'Отзывы', 'Reviews'],
            'home_rev_h2'      => ['Главная · отзывы · заголовок', 'Запускают сайты за вечер', 'Sites launched in an evening'],
            'home_rev_sub'     => ['Главная · отзывы · подзаголовок', 'Команды собирают и ведут сайты диалогом с ИИ — без очереди к разработчику.', 'Teams build and run sites by chatting with AI — no queue to a developer.'],
            'home_r1_q' => ['Главная · отзыв 1 · цитата', '«Собрал лендинг и блог за один вечер — просто описал, что нужно. ИИ сделал страницы, шаблоны и переводы.»', '“Built a landing and a blog in one evening — just described what I needed. AI made the pages, templates and translations.”'],
            'home_r1_n' => ['Главная · отзыв 1 · имя', 'Алексей Морозов', 'Alexey Morozov'],
            'home_r1_r' => ['Главная · отзыв 1 · роль', 'основатель студии', 'studio founder'],
            'home_r2_q' => ['Главная · отзыв 2 · цитата', '«Перенесли корпоративный сайт без программиста. Контент теперь правит контент-менеджер прямо в панели.»', '“Migrated our corporate site without a developer. Content is now edited by a content manager right in the panel.”'],
            'home_r2_n' => ['Главная · отзыв 2 · имя', 'Ирина Ковалёва', 'Irina Kovaleva'],
            'home_r2_r' => ['Главная · отзыв 2 · роль', 'руководитель маркетинга', 'head of marketing'],
            'home_r3_q' => ['Главная · отзыв 3 · цитата', '«Роли, журнал действий, файрвол по IP — всё из коробки. Безопасность и аудит закрыли в первый день.»', '“Roles, action log, IP firewall — all out of the box. Security and audit were covered on day one.”'],
            'home_r3_n' => ['Главная · отзыв 3 · имя', 'Дмитрий Власов', 'Dmitry Vlasov'],
            'home_r3_r' => ['Главная · отзыв 3 · роль', 'тимлид', 'team lead'],

            'home_cta_h2'   => ['Главная · CTA · заголовок', 'Готовы собрать сайт силами ИИ?', 'Ready to build a site with AI?'],
            'home_cta_p'    => ['Главная · CTA · текст', 'Опишите идею — X5-CMS соберёт страницы, шаблоны и переводы за вас.', 'Describe the idea — X5-CMS assembles pages, templates and translations for you.'],
            'home_cta_docs' => ['Главная · CTA · кнопка «Документация»', 'Документация', 'Documentation'],
        ];

        $now = now();
        foreach ($blocks as $slug => [$name, $ru, $en]) {
            DB::table('blocks')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name'       => $name,
                    'value'      => $en,   // legacy = локаль по умолчанию (en)
                    'i18n'       => json_encode(['en' => $en, 'ru' => $ru], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                ]
            );
        }

        DB::table('templates')->where('slug', 'home')->update(['body' => $this->homeBody()]);
    }

    private function homeBody(): string
    {
        return <<<'BODY'
<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}">
@partial('head')
<body>
    @partial('header')

    <section class="hero">
        <div class="wrap">
            <div class="hero-copy">
                <span class="badge anim d1"><span class="dot"></span> {{ $appName }} — @block('home_hero_badge')</span>
                <h1 class="anim d2">@block('home_hero_title1')<br><span class="grad">@block('home_hero_title2')</span></h1>
                <p class="lead anim d3">@block('home_hero_lead')</p>
                <div class="actions anim d4">
                    <a href="/admin" class="btn btn-primary">@block('home_btn_start')</a>
                    <a href="#features" class="btn btn-ghost">@block('home_btn_features')</a>
                </div>
                <div class="trust anim d5">
                    <span>@block('home_trust1')</span><span>@block('home_trust2')</span><span>Filament v4</span><span>i18n</span><span>Laravel 13</span>
                </div>
            </div>
            <div class="hero-visual anim d3">
                <div class="code-card">
                    <div class="bar"><i></i><i></i><i></i><small>X5 AI · console</small></div>
<pre><span class="ai">✦ X5 AI</span>

<span class="u">› <span class="tw">@block('home_console_prompt')</span><span class="cur">▍</span></span>

<span class="line"><span class="ok">✓</span> @block('home_console1')</span>
<span class="line"><span class="ok">✓</span> @block('home_console2')</span>
<span class="line"><span class="ok">✓</span> @block('home_console3')</span>
<span class="line"><span class="ok">✓</span> @block('home_console4') <span class="m">→ /tariffs</span></span></pre>
                </div>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="wrap">
            <div class="stat"><b>AI</b><span>@block('home_stat1')</span></div>
            <div class="stat"><b>0</b><span>@block('home_stat2')</span></div>
            <div class="stat"><b>i18n</b><span>@block('home_stat3')</span></div>
            <div class="stat"><b>100%</b><span>@block('home_stat4')</span></div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">@block('home_feat_eyebrow')</div>
                <h2>@block('home_feat_h2')</h2>
                <p>@block('home_feat_sub')</p>
            </div>
            <div class="grid">
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7L12 3z"/><path d="M19 14l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8 .8-2z"/></svg></div>
                    <h3>@block('home_f1_t')</h3>
                    <p>@block('home_f1_d')</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M5 19L15 9"/><path d="M17 3l.9 2.1L20 6l-2.1.9L17 9l-.9-2.1L14 6l2.1-.9L17 3z"/></svg></div>
                    <h3>@block('home_f2_t')</h3>
                    <p>@block('home_f2_d')</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 9v12"/></svg></div>
                    <h3>@block('home_f3_t')</h3>
                    <p>@block('home_f3_d')</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.6 2.7 2.6 15.3 0 18"/><path d="M12 3c-2.6 2.7-2.6 15.3 0 18"/></svg></div>
                    <h3>@block('home_f4_t')</h3>
                    <p>@block('home_f4_d')</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg></div>
                    <h3>@block('home_f5_t')</h3>
                    <p>@block('home_f5_d')</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg></div>
                    <h3>@block('home_f6_t')</h3>
                    <p>@block('home_f6_d')</p>
                </div>
            </div>
        </div>
    </section>

    <section class="steps">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">@block('home_steps_eyebrow')</div>
                <h2>@block('home_steps_h2')</h2>
                <p>@block('home_steps_sub')</p>
            </div>
            <div class="steps-grid">
                <div class="step"><div class="n">1</div><h3>@block('home_s1_t')</h3><p>@block('home_s1_d')</p></div>
                <div class="step"><div class="n">2</div><h3>@block('home_s2_t')</h3><p>@block('home_s2_d')</p></div>
                <div class="step"><div class="n">3</div><h3>@block('home_s3_t')</h3><p>@block('home_s3_d')</p></div>
            </div>
        </div>
    </section>

    @if (filled($content))
        <section class="content" id="content">
            <div class="wrap"><div class="card">{!! $content !!}</div></div>
        </section>
    @endif

    @if ($children->isNotEmpty())
        <section class="subpages">
            <div class="wrap">
                <h2>@block('home_steps_eyebrow')</h2>
                <div class="grid">
                    @foreach ($children as $child)
                        <a href="{{ $child->url }}">{{ $child->tr('title') }} →</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="reviews">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">@block('home_rev_eyebrow')</div>
                <h2>@block('home_rev_h2')</h2>
                <p>@block('home_rev_sub')</p>
            </div>
            <div class="grid">
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>@block('home_r1_q')</p>
                    <div class="who"><div class="av">АМ</div><div><b>@block('home_r1_n')</b><span>@block('home_r1_r')</span></div></div>
                </div>
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>@block('home_r2_q')</p>
                    <div class="who"><div class="av">ИК</div><div><b>@block('home_r2_n')</b><span>@block('home_r2_r')</span></div></div>
                </div>
                <div class="review">
                    <div class="stars">★★★★★</div>
                    <p>@block('home_r3_q')</p>
                    <div class="who"><div class="av">ДВ</div><div><b>@block('home_r3_n')</b><span>@block('home_r3_r')</span></div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="wrap">
            <div class="band">
                <h2>@block('home_cta_h2')</h2>
                <p>@block('home_cta_p')</p>
                <div class="actions">
                    <a href="/admin" class="btn btn-primary">@block('home_btn_start')</a>
                    <a href="/docs" class="btn btn-ghost">@block('home_cta_docs')</a>
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
        } else { run(); }
    })();
    </script>
</body>
</html>
BODY;
    }

    public function down(): void
    {
        // Контент правится в админке — откат не предусмотрен.
    }
};
