<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('admin.blog.articles.model_plural') }} — {{ $appName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
<body>
    <header class="nav">
        <div class="wrap">
            <a href="{{ url($locale) }}" class="brand">
                <img class="logo" src="{{ asset('images/logo-mark.svg') }}" alt="{{ $appName }}"> {{ $appName }}
            </a>
            @partial('menu')
            <div class="lang">
                @foreach (\App\Modules\System\Models\Language::codes() as $l)
                    <a href="{{ url($l.'/blog') }}" class="{{ $locale === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
                @endforeach
            </div>
            <a href="/admin" class="btn btn-primary">{{ $locale === 'en' ? 'Sign in' : 'Войти' }}</a>
        </div>
    </header>

    <div class="wrap">
        <header class="bl-hero">
            <div class="eyebrow">{{ $locale === 'en' ? 'Journal' : 'Журнал' }}</div>
            <h1>{{ __('admin.blog.articles.model_plural') }}</h1>
            <p>{{ $locale === 'en' ? 'News, announcements and notes — newest first.' : 'Новости, анонсы и заметки — в ленте времени.' }}</p>
        </header>

        @php($activeCat = request('category'))
        @php($activeTag = request('tag'))

        <div class="bl-layout">
            <main>
                @if ($activeCat || $activeTag)
                    <p class="bl-reset"><a class="bl-back" href="{{ url($locale.'/blog') }}">← {{ $locale === 'en' ? 'All posts' : 'Все публикации' }}</a></p>
                @endif

                @if ($articles->isEmpty())
                    <p class="bl-empty">{{ $locale === 'en' ? 'No posts yet.' : 'Пока нет публикаций.' }}</p>
                @else
                    <ol class="timeline">
                        @foreach ($articles as $article)
                            <li class="tl-item">
                                <div class="tl-date">{{ optional($article->published_at)->translatedFormat('d F Y, H:i') }}</div>
                                <article class="bl-card">
                                    @if ($article->image)
                                        <img class="cover" src="{{ $article->image }}" alt="">
                                    @endif
                                    <div class="bl-meta">
                                        @if ($article->category)
                                            <a class="bl-badge cat" href="{{ url($locale.'/blog') }}?category={{ $article->category->slug }}">{{ $article->category->tr('name') }}</a>
                                        @endif
                                        @foreach ($article->tags as $tag)
                                            <a class="bl-badge" href="{{ url($locale.'/blog') }}?tag={{ $tag->slug }}">#{{ $tag->tr('name') }}</a>
                                        @endforeach
                                    </div>
                                    <h2><a href="{{ $article->url }}">{{ $article->tr('title') }}</a></h2>
                                    @if ($article->tr('excerpt'))
                                        <p class="excerpt">{{ $article->tr('excerpt') }}</p>
                                    @endif
                                </article>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </main>

            <aside class="bl-aside">
                <div class="group">
                    <h3>{{ __('admin.blog.categories.model_plural') }}</h3>
                    <ul>
                        @foreach ($categories as $category)
                            <li>
                                <a class="{{ $activeCat === $category->slug ? 'active' : '' }}" href="{{ url($locale.'/blog') }}?category={{ $category->slug }}">{{ $category->tr('name') }}</a>
                                <span class="count">{{ $category->articles_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="group">
                    <h3>{{ __('admin.blog.tags.model_plural') }}</h3>
                    <div class="tagcloud">
                        @foreach ($tags as $tag)
                            <a class="{{ $activeTag === $tag->slug ? 'active' : '' }}" href="{{ url($locale.'/blog') }}?tag={{ $tag->slug }}">#{{ $tag->tr('name') }} {{ $tag->articles_count }}</a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>

    @partial('footer')
</body>
</html>
