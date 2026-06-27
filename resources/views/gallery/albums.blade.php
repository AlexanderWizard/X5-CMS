<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('admin.gallery.albums.model_plural') }} — {{ $appName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
<body class="gl-page">
    <header class="nav">
        <div class="wrap">
            <a href="{{ url($locale) }}" class="brand">
                <img class="logo" src="{{ asset('images/logo-mark.svg') }}" alt="{{ $appName }}"> {{ $appName }}
            </a>
            @partial('menu')
            <div class="lang">
                @foreach (\App\Modules\System\Models\Language::codes() as $l)
                    <a href="{{ url($l.'/gallery') }}" class="{{ $locale === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
                @endforeach
            </div>
            <a href="/admin" class="btn btn-primary">{{ $locale === 'en' ? 'Sign in' : 'Войти' }}</a>
        </div>
    </header>

    <div class="wrap">
        <header class="gl-discover-head">
            <div class="gl-eyebrow">{{ $locale === 'en' ? 'Gallery' : 'Галерея' }}</div>
            <h1>{{ __('admin.gallery.albums.model_plural') }}</h1>
            <p>{{ $locale === 'en' ? 'Photo albums — newest updates first.' : 'Фотоальбомы — свежие обновления сверху.' }}</p>
        </header>

        @if ($albums->isEmpty())
            <p class="gl-empty">{{ $locale === 'en' ? 'No albums yet.' : 'Пока нет альбомов.' }}</p>
        @else
            <div class="gl-masonry gl-discover">
                @foreach ($albums as $album)
                    @php($cover = $album->coverList->first())
                    <a class="gl-cover" href="{{ $album->url }}">
                        <span class="gl-ph" style="aspect-ratio: {{ $cover && $cover->width ? $cover->aspectRatio() : '4 / 3' }}">
                            @if ($cover)
                                <img src="{{ $cover->med_url }}" alt="{{ $album->tr('title') }}" loading="lazy" onload="this.parentNode.classList.add('on')">
                            @else
                                <span class="gl-ph-empty"></span>
                            @endif
                        </span>
                        <span class="gl-cover-grad"></span>
                        <span class="gl-cover-body">
                            <span class="gl-cover-title">{{ $album->tr('title') }}</span>
                            <span class="gl-cover-meta">
                                {{ $album->photos_count }} {{ $locale === 'en' ? 'photos' : 'фото' }}
                                @if ($album->updated_at) · {{ $album->updated_at->translatedFormat('M Y') }}@endif
                            </span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @partial('footer')
</body>
</html>
