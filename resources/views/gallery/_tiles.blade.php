@foreach ($photos as $photo)
    @php($meta = collect([$photo->camera, $photo->focal_length, $photo->shutter_speed])->filter()->join(' · '))
    <a class="gl-tile"
       href="{{ $photo->url }}"
       data-id="{{ $photo->id }}"
       data-full="{{ $photo->image_url }}"
       data-page="{{ $photo->url }}"
       data-ping="{{ url($locale.'/gallery/'.$album->slug.'/'.$photo->id.'/v') }}"
       data-title="{{ $photo->tr('title') }}"
       data-date="{{ $photo->taken_at }}"
       data-camera="{{ $photo->camera }}"
       data-lens="{{ $photo->lens }}"
       data-ss="{{ $photo->shutter_speed }}"
       data-f="{{ $photo->focal_length }}"
       data-iso="{{ $photo->iso }}"
       data-views="{{ $photo->viewsLabel() }}"
       data-tags="{{ implode(',', $photo->tagList()) }}">
        <span class="gl-ph" style="aspect-ratio: {{ $photo->aspectRatio() }}">
            <img src="{{ $photo->med_url }}" alt="{{ $photo->tr('title') ?: $album->tr('title') }}" loading="lazy" onload="this.parentNode.classList.add('on')">
        </span>
        <span class="gl-ov">
            @if ($photo->tr('title'))<span class="gl-ov-t">{{ $photo->tr('title') }}</span>@endif
            @if ($meta)<span class="gl-ov-m">{{ $meta }}</span>@endif
        </span>
        <span class="gl-ov-top">
            <span class="gl-stat"><svg viewBox="0 0 24 24" class="gl-i"><path d="M12 5c-5 0-9 4.5-10 7 1 2.5 5 7 10 7s9-4.5 10-7c-1-2.5-5-7-10-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg>{{ $photo->viewsLabel() }}</span>
        </span>
    </a>
@endforeach
