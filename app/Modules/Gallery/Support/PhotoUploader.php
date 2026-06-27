<?php

namespace App\Modules\Gallery\Support;

use App\Modules\Gallery\Models\Album;
use App\Modules\Gallery\Models\Photo;
use Illuminate\Support\Facades\Storage;

/**
 * Приём одной загруженной фотографии в альбом: создаёт запись Photo, генерирует
 * варианты изображения (большой + два квадратных превью) и вытягивает EXIF.
 *
 * Базовый путь варианта: {album_id}/{photo_id} (на диске gallery →
 * public/uploads/gallery). Порядок «сначала запись, потом путь» нужен,
 * чтобы имя файла = id фото.
 */
class PhotoUploader
{
    /**
     * @param string $sourcePath Абсолютный путь к исходному файлу (временная загрузка).
     */
    public static function ingest(Album $album, string $sourcePath): ?Photo
    {
        if (!is_file($sourcePath)) {
            return null;
        }

        [$w, $h] = ImageProcessor::dimensions($sourcePath);

        if ($w === 0 || $h === 0) {
            return null; // не изображение
        }

        // Пустая запись — чтобы получить id для имени файла.
        $photo = Photo::create([
            'album_id'  => $album->id,
            'width'     => $w,
            'height'    => $h,
            'size'      => (int) (@filesize($sourcePath) ?: 0),
            'is_active' => true,
        ]);

        $base     = $album->id . '/' . $photo->id;
        $diskRoot = Storage::disk(Photo::DISK)->path('');

        ImageProcessor::fitJpeg($sourcePath, $diskRoot . $base . '.jpg', 1680, 1680, 90);
        ImageProcessor::fitJpeg($sourcePath, $diskRoot . $base . '_med.jpg', 1080, 1080, 86);
        ImageProcessor::squareThumbnail($sourcePath, $diskRoot . $base . '_tmb.jpg', 600, 88);
        ImageProcessor::squareThumbnail($sourcePath, $diskRoot . $base . '_tmb2.jpg', 96, 85);

        $exif = Exif::read($sourcePath);

        // Счётчик альбома обновляет сам Photo (событие created); здесь только
        // дописываем путь и EXIF к уже созданной записи.
        $photo->forceFill(array_merge($exif, ['path' => $base]))->save();

        return $photo;
    }
}
