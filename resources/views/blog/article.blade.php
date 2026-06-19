<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->tr('title') }} — {{ $appName }}</title>
    @if ($article->tr('excerpt'))<meta name="description" content="{{ $article->tr('excerpt') }}">@endif
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
                    <a href="{{ url($l.'/blog/'.$article->slug) }}" class="{{ $locale === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
                @endforeach
            </div>
            <a href="/admin" class="btn btn-primary">{{ $locale === 'en' ? 'Sign in' : 'Войти' }}</a>
        </div>
    </header>

    <div class="wrap">
        <article class="bl-article">
            <a class="bl-back" href="{{ url($locale.'/blog') }}">← {{ __('admin.blog.articles.model_plural') }}</a>
            <h1>{{ $article->tr('title') }}</h1>
            <div class="bl-meta">
                <span class="tl-date">{{ optional($article->published_at)->translatedFormat('d F Y, H:i') }}</span>
                @if ($article->category)
                    <a class="bl-badge cat" href="{{ url($locale.'/blog') }}?category={{ $article->category->slug }}">{{ $article->category->tr('name') }}</a>
                @endif
                @foreach ($article->tags as $tag)
                    <a class="bl-badge" href="{{ url($locale.'/blog') }}?tag={{ $tag->slug }}">#{{ $tag->tr('name') }}</a>
                @endforeach
            </div>
            @if ($article->image)
                <img class="cover" src="{{ $article->image }}" alt="">
            @endif
            <div class="bl-body">
                {!! $article->tr('content') !!}
            </div>
        </article>
    </div>

    @partial('footer')
</body>
</html>
