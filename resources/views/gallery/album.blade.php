<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $album->tr('title') }} — {{ $appName }}</title>
    @if ($album->tr('description'))<meta name="description" content="{{ $album->tr('description') }}">@endif
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
                    <a href="{{ url($l.'/gallery/'.$album->slug) }}" class="{{ $locale === $l ? 'on' : '' }}">{{ strtoupper($l) }}</a>
                @endforeach
            </div>
            <a href="/admin" class="btn btn-primary">{{ $locale === 'en' ? 'Sign in' : 'Войти' }}</a>
        </div>
    </header>

    <div class="gl-subbar">
        <div class="wrap">
            <div class="gl-subbar-l">
                <a href="{{ url($locale.'/gallery') }}" class="gl-up">
                    <svg viewBox="0 0 24 24" class="gl-i"><path d="M15 18l-6-6 6-6"/></svg>
                    {{ __('admin.gallery.albums.model_plural') }}
                </a>
                <span class="gl-sep">/</span>
                <span class="gl-cur">{{ $album->tr('title') }}</span>
            </div>
            <div class="gl-subbar-r">{{ $album->photos_count }} {{ $locale === 'en' ? 'photos' : 'фото' }}</div>
        </div>
    </div>

    <div class="wrap">
        <header class="gl-album-head">
            <h1>{{ $album->tr('title') }}</h1>
            @if ($album->tr('description'))<p>{{ $album->tr('description') }}</p>@endif
        </header>

        @if ($photos->isEmpty())
            <p class="gl-empty">{{ $locale === 'en' ? 'No photos in this album yet.' : 'В этом альбоме пока нет фото.' }}</p>
        @else
            <div id="gl-grid" class="gl-masonry"
                 data-base="{{ url($locale.'/gallery/'.$album->slug) }}"
                 data-page="{{ $photos->currentPage() }}"
                 data-last="{{ $photos->lastPage() }}">
                @include('gallery._tiles', ['photos' => $photos, 'album' => $album, 'locale' => $locale])
            </div>
            <div id="gl-sentinel" class="gl-sentinel" aria-hidden="true">
                <span class="gl-spinner"></span>
            </div>
        @endif
    </div>

    {{-- Лайтбокс --}}
    <div id="gl-lb" class="gl-lb" aria-hidden="true">
        <div class="gl-lb-stage">
            <button class="gl-lb-nav prev" data-act="prev" aria-label="{{ $locale === 'en' ? 'Previous' : 'Назад' }}">
                <svg viewBox="0 0 24 24" class="gl-i"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <div class="gl-lb-imgwrap">
                <img id="gl-lb-img" src="" alt="">
            </div>
            <button class="gl-lb-nav next" data-act="next" aria-label="{{ $locale === 'en' ? 'Next' : 'Вперёд' }}">
                <svg viewBox="0 0 24 24" class="gl-i"><path d="M9 18l6-6-6-6"/></svg>
            </button>
            <div id="gl-lb-counter" class="gl-lb-counter"></div>
        </div>

        <aside class="gl-lb-info">
            <div class="gl-lb-info-head">
                <h2 id="gl-lb-title"></h2>
                <button class="gl-lb-x" data-act="close" aria-label="{{ $locale === 'en' ? 'Close' : 'Закрыть' }}">
                    <svg viewBox="0 0 24 24" class="gl-i"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
            <div class="gl-lb-stats">
                <span class="gl-stat"><svg viewBox="0 0 24 24" class="gl-i"><path d="M12 5c-5 0-9 4.5-10 7 1 2.5 5 7 10 7s9-4.5 10-7c-1-2.5-5-7-10-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg><span id="gl-lb-views"></span></span>
                <a id="gl-lb-dl" class="gl-stat gl-act" href="" target="_blank" rel="noopener" title="{{ $locale === 'en' ? 'Open original' : 'Открыть оригинал' }}"><svg viewBox="0 0 24 24" class="gl-i"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg></a>
                <a id="gl-lb-page" class="gl-stat gl-act" href="" title="{{ $locale === 'en' ? 'Permalink' : 'Постоянная ссылка' }}"><svg viewBox="0 0 24 24" class="gl-i"><path d="M10 14a5 5 0 007 0l2-2a5 5 0 00-7-7l-1 1M14 10a5 5 0 00-7 0l-2 2a5 5 0 007 7l1-1"/></svg></a>
            </div>
            <div class="gl-lb-divider"></div>
            <dl id="gl-lb-exif" class="gl-lb-exif"></dl>
            <div id="gl-lb-tagwrap" class="gl-lb-tags"></div>
        </aside>
    </div>

    <script>
        window.GL = {
            exifLabels: {
                date:    @json($locale === 'en' ? 'Date' : 'Дата'),
                camera:  @json($locale === 'en' ? 'Camera' : 'Камера'),
                lens:    @json($locale === 'en' ? 'Lens' : 'Объектив'),
                shutter: @json($locale === 'en' ? 'Shutter' : 'Выдержка'),
                aperture:@json($locale === 'en' ? 'Aperture' : 'Диафрагма'),
                iso:     'ISO'
            },
            counterOf: @json($locale === 'en' ? 'of' : 'из')
        };
    </script>
    <script src="{{ asset('js/gallery.js') }}?v={{ filemtime(public_path('js/gallery.js')) }}"></script>

    @partial('footer')
</body>
</html>
