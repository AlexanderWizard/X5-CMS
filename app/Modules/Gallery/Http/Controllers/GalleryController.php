<?php

namespace App\Modules\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Models\Album;
use App\Modules\Gallery\Models\Photo;
use App\Modules\System\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    /** Фото на «страницу» бесконечной ленты. */
    private const PER_PAGE = 30;

    /**
     * Discover-стена альбомов (masonry из обложек).
     */
    public function index(string $locale): View
    {
        app()->setLocale($locale);

        $albums = Album::query()->activeFeed()->get();

        // Обложка альбома = свежее активное фото (+ счётчик уже в photos_count).
        foreach ($albums as $album) {
            $album->setRelation('coverList', $album->activePhotos()->limit(1)->get());
        }

        return view('gallery.albums', [
            'locale'   => $locale,
            'albums'   => $albums,
            'appName'  => Setting::get('site_name', config('app.name', 'Site')),
            'settings' => Setting::allValues(),
        ]);
    }

    /**
     * Альбом: masonry-сетка с бесконечной подгрузкой и клиентским лайтбоксом.
     * При ?partial=1 отдаёт только плитки очередной страницы (для infinite scroll).
     */
    public function album(string $locale, string $slug): View
    {
        app()->setLocale($locale);

        $album = Album::query()->where('slug', $slug)->where('is_active', 1)->first();

        if (!$album) {
            throw new NotFoundHttpException();
        }

        $photos = $album->activePhotos()->paginate(self::PER_PAGE)->withQueryString();

        // Бесконечная лента: вернуть только плитки (без лейаута).
        if (request()->boolean('partial')) {
            return view('gallery._tiles', [
                'locale' => $locale,
                'album'  => $album,
                'photos' => $photos,
            ]);
        }

        return view('gallery.album', [
            'locale'   => $locale,
            'album'    => $album,
            'photos'   => $photos,
            'appName'  => Setting::get('site_name', config('app.name', 'Site')),
            'settings' => Setting::allValues(),
        ]);
    }

    /**
     * Отдельная фотография (shareable, для SEO/Open Graph) — иммерсивная страница.
     * Инкрементит счётчик просмотров.
     */
    public function photo(string $locale, string $slug, int $id): View
    {
        app()->setLocale($locale);

        $album = Album::query()->where('slug', $slug)->where('is_active', 1)->first();

        if (!$album) {
            throw new NotFoundHttpException();
        }

        $photo = $album->photos()->where('id', $id)->where('is_active', 1)->first();

        if (!$photo) {
            throw new NotFoundHttpException();
        }

        $photo->setRelation('album', $album);
        $album->photos()->whereKey($photo->id)->increment('views');
        $photo->views++;

        return view('gallery.photo', [
            'locale'   => $locale,
            'album'    => $album,
            'photo'    => $photo,
            'near'     => $album->activePhotos()->get(['id', 'album_id', 'path']),
            'appName'  => Setting::get('site_name', config('app.name', 'Site')),
            'settings' => Setting::allValues(),
        ]);
    }

    /**
     * Лёгкий ping-инкремент просмотров (вызывается лайтбоксом при открытии кадра).
     * GET без тела → без CSRF; ничего не рендерит.
     */
    public function viewPing(string $locale, string $slug, int $id): Response
    {
        Photo::query()
            ->where('id', $id)
            ->where('is_active', 1)
            ->whereHas('album', fn ($q) => $q->where('slug', $slug)->where('is_active', 1))
            ->increment('views');

        return response()->noContent();
    }
}
