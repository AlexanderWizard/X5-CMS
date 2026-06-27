<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $photo->tr('title') ?: $album->tr('title') }} — {{ $appName }}</title>
    <meta name="description" content="{{ $photo->tr('title') ?: $album->tr('title') }}{{ $photo->camera ? ' · '.$photo->camera : '' }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $photo->tr('title') ?: $album->tr('title') }}">
    <meta property="og:image" content="{{ url($photo->image_url) }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
<body class="gl-page gl-photo-page">
    <header class="nav">
        <div class="wrap">
            <a href="{{ url($locale) }}" class="brand">
                <img class="logo" src="{{ asset('images/logo-mark.svg') }}" alt="{{ $appName }}"> {{ $appName }}
            </a>
            @partial('menu')
            <div class="lang">
                @foreach (\App\Modules\System\Models\Language::codes() as $l)
                    <a href="{{ url($l.'/gallery/'.$album->slug.'/'.$photo->id) }}" class="{{ $locale === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
                @endforeach
            </div>
            <a href="/admin" class="btn btn-primary">{{ $locale === 'en' ? 'Sign in' : 'Войти' }}</a>
        </div>
    </header>

    <div class="gl-subbar">
        <div class="wrap">
            <div class="gl-subbar-l">
                <a href="{{ url($locale.'/gallery') }}" class="gl-up">{{ __('admin.gallery.albums.model_plural') }}</a>
                <span class="gl-sep">/</span>
                <a href="{{ $album->url }}" class="gl-up">{{ $album->tr('title') }}</a>
                <span class="gl-sep">/</span>
                <span class="gl-cur">#{{ $photo->id }}</span>
            </div>
        </div>
    </div>

    <div class="gl-solo">
        <figure class="gl-solo-img">
            <img src="{{ $photo->image_url }}" alt="{{ $photo->tr('title') ?: $album->tr('title') }}">
        </figure>

        <aside class="gl-solo-info">
            @if ($photo->tr('title'))<h1>{{ $photo->tr('title') }}</h1>@endif
            <div class="gl-lb-stats">
                <span class="gl-stat"><svg viewBox="0 0 24 24" class="gl-i"><path d="M12 5c-5 0-9 4.5-10 7 1 2.5 5 7 10 7s9-4.5 10-7c-1-2.5-5-7-10-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg>{{ $photo->viewsLabel() }}</span>
                <a class="gl-stat gl-act" href="{{ $photo->image_url }}" target="_blank" rel="noopener" title="{{ $locale === 'en' ? 'Open original' : 'Открыть оригинал' }}"><svg viewBox="0 0 24 24" class="gl-i"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg></a>
            </div>

            @php($hasExif = $photo->taken_at || $photo->camera || $photo->lens || $photo->shutter_speed || $photo->focal_length || $photo->iso)
            @if ($hasExif)
                <div class="gl-lb-divider"></div>
                <dl class="gl-lb-exif">
                    @if ($photo->taken_at)<dt>{{ $locale === 'en' ? 'Date' : 'Дата' }}</dt><dd>{{ $photo->taken_at }}</dd>@endif
                    @if ($photo->camera)<dt>{{ $locale === 'en' ? 'Camera' : 'Камера' }}</dt><dd>{{ $photo->camera }}</dd>@endif
                    @if ($photo->lens)<dt>{{ $locale === 'en' ? 'Lens' : 'Объектив' }}</dt><dd>{{ $photo->lens }}</dd>@endif
                    @if ($photo->shutter_speed)<dt>{{ $locale === 'en' ? 'Shutter' : 'Выдержка' }}</dt><dd>{{ $photo->shutter_speed }}</dd>@endif
                    @if ($photo->focal_length)<dt>{{ $locale === 'en' ? 'Aperture' : 'Диафрагма' }}</dt><dd>{{ $photo->focal_length }}</dd>@endif
                    @if ($photo->iso)<dt>ISO</dt><dd>{{ $photo->iso }}</dd>@endif
                </dl>
            @endif

            @if ($photo->tagList())
                <div class="gl-lb-divider"></div>
                <div class="gl-lb-tags">
                    @foreach ($photo->tagList() as $tag)<span class="gl-tag">#{{ $tag }}</span>@endforeach
                </div>
            @endif
        </aside>
    </div>

    @if ($near->count() > 1)
        <div class="wrap">
            <div class="gl-strip">
                @foreach ($near as $n)
                    <a href="{{ url($locale.'/gallery/'.$album->slug.'/'.$n->id) }}"
                       class="gl-strip-item {{ $n->id === $photo->id ? 'is-current' : '' }}">
                        <span style="background-image:url('{{ $n->micro_url }}')"></span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @partial('footer')
</body>
</html>
