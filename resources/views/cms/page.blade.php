<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }}</title>
    <style>
        :root { --accent: #ea580c; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
            color: #1f2937;
            background: #f9fafb;
            line-height: 1.6;
        }
        header.site {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        .wrap { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; }
        header.site .wrap { display: flex; align-items: center; gap: 0.75rem; height: 64px; }
        .brand { display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 1.1rem; color: #111827; text-decoration: none; }
        .brand .dot { width: 22px; height: 22px; border-radius: 6px; background: var(--accent); display: inline-block; }
        nav.crumbs { font-size: 0.85rem; color: #6b7280; padding: 1rem 0 0; }
        nav.crumbs a { color: var(--accent); text-decoration: none; }
        nav.crumbs a:hover { text-decoration: underline; }
        main { padding: 1.5rem 0 4rem; }
        h1.page-title { font-size: 2rem; font-weight: 800; color: #111827; margin: 0.5rem 0 1.5rem; }
        article.content { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        article.content :where(h1,h2,h3) { color: #111827; }
        article.content img { max-width: 100%; height: auto; border-radius: 8px; }
        .children { margin-top: 2rem; }
        .children h2 { font-size: 1rem; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; }
        .children ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.5rem; }
        .children a {
            display: block; padding: 0.85rem 1rem; background: #fff; border: 1px solid #e5e7eb;
            border-radius: 10px; text-decoration: none; color: #111827; font-weight: 600;
            transition: border-color .15s, transform .15s;
        }
        .children a:hover { border-color: var(--accent); transform: translateY(-1px); }
        footer.site { text-align: center; color: #9ca3af; font-size: 0.85rem; padding: 2rem 0; }
        .empty { color: #9ca3af; font-style: italic; }
    </style>
</head>
<body>
    <header class="site">
        <div class="wrap">
            <a href="{{ url('/') }}" class="brand"><span class="dot"></span> {{ config('app.name', 'Site') }}</a>
        </div>
    </header>

    <div class="wrap">
        @if (! $page->is_home)
            <nav class="crumbs">
                <a href="{{ url('/') }}">{{ __('Главная') }}</a>
                @foreach ($page->ancestorsTrail() as $crumb)
                    / @if ($loop->last){{ $crumb->title }}@else<a href="{{ $crumb->url }}">{{ $crumb->title }}</a>@endif
                @endforeach
            </nav>
        @endif

        <main>
            <h1 class="page-title">{{ $page->title }}</h1>

            <article class="content">
                @if (filled($page->content))
                    {!! $page->content !!}
                @else
                    <p class="empty">{{ __('Содержимое страницы пока не заполнено.') }}</p>
                @endif
            </article>

            @if ($children->isNotEmpty())
                <section class="children">
                    <h2>{{ __('Разделы') }}</h2>
                    <ul>
                        @foreach ($children as $child)
                            <li><a href="{{ $child->url }}">{{ $child->title }}</a></li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </main>
    </div>

    <footer class="site">© {{ date('Y') }} {{ config('app.name', 'Site') }}</footer>
</body>
</html>
