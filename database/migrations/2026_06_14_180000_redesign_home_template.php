<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Редизайн шаблона главной (лендинг): 2-колоночный герой с карточкой API,
 * полоса метрик, секция «как это работает», SVG-иконки вместо эмодзи
 * (4-байтные эмодзи бились в «?» из-за utf8 в MySQL 5.6).
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
                <span class="badge anim d1"><span class="dot"></span> {{ $appName }} — платформа уведомлений и CMS</span>
                <h1 class="anim d2">Уведомления по API<br><span class="grad">и контент под контролем</span></h1>
                <p class="lead anim d3">Принимайте сообщения через REST API и доставляйте по нужным каналам — e-mail, SMS, push. Очередь, права, журнал действий и редактируемый сайт — из коробки.</p>
                <div class="actions anim d4">
                    <a href="#features" class="btn btn-primary">Начать →</a>
                    <a href="/docs" class="btn btn-ghost">API-документация</a>
                </div>
                <div class="trust anim d5">
                    <span>Laravel 13</span><span>Filament v4</span><span>REST API</span><span>RBAC</span><span>i18n</span>
                </div>
            </div>
            <div class="hero-visual anim d3">
                <div class="code-card">
                    <div class="bar"><i></i><i></i><i></i><small>POST /api/message</small></div>
<pre><span class="k">POST</span> <span class="m">/api/message</span>
{
  <span class="k">"channel"</span>: <span class="s">"email"</span>,
  <span class="k">"body"</span>: <span class="s">"Заказ #1024 принят"</span>
}

<span class="ok">201 Created</span> <span class="m">· сообщение в очереди ✓</span></pre>
                </div>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="wrap">
            <div class="stat"><b>REST</b><span>API уведомлений</span></div>
            <div class="stat"><b>RBAC</b><span>роли и права по модулям</span></div>
            <div class="stat"><b>24/7</b><span>очередь по расписанию</span></div>
            <div class="stat"><b>i18n</b><span>ru · en из коробки</span></div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Возможности</div>
                <h2>Всё, что нужно для запуска</h2>
                <p>Готовая инфраструктура для отправки уведомлений и управления сайтом через удобную панель.</p>
            </div>
            <div class="grid">
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div>
                    <h3>Очередь сообщений</h3>
                    <p>Приём через REST API и фоновая обработка по расписанию без потерь.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg></div>
                    <h3>Роли и права</h3>
                    <p>Гибкое дерево прав по модулям с энфорсментом на каждом ресурсе.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 9v12"/></svg></div>
                    <h3>CMS и шаблоны</h3>
                    <p>Древовидные страницы с шаблонами вывода и блоками прямо из базы.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg></div>
                    <h3>Файрвол по IP</h3>
                    <p>Доступ в админку только с разрешённых адресов и подсетей (CIDR).</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg></div>
                    <h3>Журнал действий</h3>
                    <p>Полный аудит: кто, что и когда изменил — с фильтрами по событиям.</p>
                </div>
                <div class="feature">
                    <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.6 2.7 2.6 15.3 0 18"/><path d="M12 3c-2.6 2.7-2.6 15.3 0 18"/></svg></div>
                    <h3>Переводы в БД</h3>
                    <p>Интерфейс на ru/en, строки редактируются прямо в админке.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="steps">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Как это работает</div>
                <h2>Три шага до первого сообщения</h2>
                <p>От запроса до доставки — без настройки инфраструктуры.</p>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="n">1</div>
                    <h3>Отправьте запрос</h3>
                    <p>POST /api/message с каналом и телом сообщения — и всё.</p>
                </div>
                <div class="step">
                    <div class="n">2</div>
                    <h3>Очередь обработает</h3>
                    <p>Фоновый воркер по расписанию разбирает очередь без потерь.</p>
                </div>
                <div class="step">
                    <div class="n">3</div>
                    <h3>Смотрите результат</h3>
                    <p>Статусы и полный журнал действий — в наглядной панели.</p>
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
                <h2>Готовы подключить уведомления?</h2>
                <p>Откройте документацию API и отправьте первое сообщение за пару минут.</p>
                <div class="actions">
                    <a href="/docs" class="btn btn-primary">Перейти к API →</a>
                    <a href="/admin" class="btn btn-ghost">Войти в панель</a>
                </div>
            </div>
        </div>
    </section>

    @partial('footer')
</body>
</html>
BODY;

        DB::table('templates')->where('slug', 'home')->update(['body' => $body]);
    }

    public function down(): void
    {
        // Редизайн контента — откат не предусмотрен (шаблон редактируется в админке).
    }
};
