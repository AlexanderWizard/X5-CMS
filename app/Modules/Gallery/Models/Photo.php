<?php

namespace App\Modules\Gallery\Models;

use App\Modules\Blog\Models\Concerns\HasI18n;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Фотография галереи.
 *
 * `path` — базовый путь варианта на диске `gallery` (public/uploads/gallery),
 * без расширения: «{album_id}/{id}». Реальные файлы:
 *  - {path}.jpg       — уменьшенная копия (вписана в 1680×1680, лайтбокс);
 *  - {path}_med.jpg   — средний вариант (вписан в 1080, НЕ обрезан, masonry-сетка);
 *  - {path}_tmb.jpg   — квадратное превью 600×600 (обложки альбомов);
 *  - {path}_tmb2.jpg  — квадратное превью 96×96 (лента-навигатор у фото).
 *
 * @property int         $id
 * @property int         $album_id
 * @property string|null $path
 * @property string|null $title
 * @property string|null $tags
 * @property int         $width
 * @property int         $height
 * @property int         $size
 * @property string|null $camera
 * @property string|null $lens
 * @property string|null $shutter_speed
 * @property string|null $focal_length
 * @property int|null    $iso
 * @property string|null $taken_at
 * @property int|null    $year
 * @property bool        $is_active
 * @property int         $sort_order
 * @property string      $created_at
 */
class Photo extends Model
{
    use HasI18n;
    use LogsActivity;

    /** Переводимые поля (хранятся в i18n[locale][field]). */
    public const I18N_FIELDS = ['title'];

    /** Диск хранения файлов (public/uploads/gallery, см. config/filesystems.php). */
    public const DISK = 'gallery';

    protected $table = 'gallery_photos';

    public $timestamps = false;

    protected $fillable = [
        'album_id',
        'path',
        'title',
        'tags',
        'i18n',
        'width',
        'height',
        'size',
        'camera',
        'lens',
        'shutter_speed',
        'focal_length',
        'iso',
        'taken_at',
        'year',
        'is_active',
        'views',
        'sort_order',
    ];

    protected $casts = [
        'i18n'       => 'array',
        'width'      => 'integer',
        'height'     => 'integer',
        'size'       => 'integer',
        'iso'        => 'integer',
        'year'       => 'integer',
        'is_active'  => 'boolean',
        'views'      => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(fn (Photo $p) => $p->syncI18nDefaults());

        // Появление новой фотографии — пересчёт счётчика альбома.
        static::created(fn (Photo $p) => $p->album?->refreshCounter());

        // Удаление записи уносит файлы вариантов с диска и обновляет счётчик.
        static::deleted(function (Photo $p): void {
            $p->deleteFiles();
            $p->album?->refreshCounter();
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    /** Ориентация по соотношению сторон. */
    public function isPortrait(): bool
    {
        return $this->height > $this->width;
    }

    /** Теги списком (для ссылок-фильтров). @return array<int, string> */
    public function tagList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tags))));
    }

    /** Соотношение сторон «W / H» (для masonry без сдвига вёрстки). */
    public function aspectRatio(): string
    {
        return ($this->width > 0 && $this->height > 0)
            ? $this->width . ' / ' . $this->height
            : '1 / 1';
    }

    /** Просмотры в компактном виде: 1.2k, 3.4M. */
    public function viewsLabel(): string
    {
        $v = (int) $this->views;

        return match (true) {
            $v >= 1_000_000 => rtrim(rtrim(number_format($v / 1_000_000, 1), '0'), '.') . 'M',
            $v >= 1_000     => rtrim(rtrim(number_format($v / 1_000, 1), '0'), '.') . 'k',
            default         => (string) $v,
        };
    }

    /** URL уменьшенной полноразмерной копии (1680², для лайтбокса). */
    public function getImageUrlAttribute(): string
    {
        return $this->path ? Storage::disk(self::DISK)->url($this->path . '.jpg') : '';
    }

    /** URL среднего варианта (≤1080, НЕ обрезан — для masonry-сетки). */
    public function getMedUrlAttribute(): string
    {
        return $this->path ? Storage::disk(self::DISK)->url($this->path . '_med.jpg') : '';
    }

    /** URL квадратного превью 600×600. */
    public function getThumbUrlAttribute(): string
    {
        return $this->path ? Storage::disk(self::DISK)->url($this->path . '_tmb.jpg') : '';
    }

    /** URL квадратного мини-превью 96×96. */
    public function getMicroUrlAttribute(): string
    {
        return $this->path ? Storage::disk(self::DISK)->url($this->path . '_tmb2.jpg') : '';
    }

    /** Публичный URL страницы фото (/{locale}/gallery/{album}/{id}). */
    public function getUrlAttribute(): string
    {
        $slug = $this->album?->slug
            ?? Album::query()->whereKey($this->album_id)->value('slug');

        return url(app()->getLocale() . '/gallery/' . $slug . '/' . $this->id);
    }

    /** Удалить все файлы-варианты фото с диска. */
    public function deleteFiles(): void
    {
        if (!$this->path) {
            return;
        }

        foreach (['.jpg', '_med.jpg', '_tmb.jpg', '_tmb2.jpg'] as $suffix) {
            Storage::disk(self::DISK)->delete($this->path . $suffix);
        }
    }
}
