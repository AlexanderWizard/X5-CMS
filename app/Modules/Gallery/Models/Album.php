<?php

namespace App\Modules\Gallery\Models;

use App\Modules\Blog\Models\Concerns\HasI18n;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Фотоальбом.
 *
 * @property int         $id
 * @property string      $title
 * @property string      $slug
 * @property string|null $description
 * @property int         $photos_count
 * @property bool        $is_active
 * @property int         $sort_order
 * @property string      $created_at
 * @property string|null $updated_at
 */
class Album extends Model
{
    use HasI18n;
    use LogsActivity;

    /** Переводимые поля (хранятся в i18n[locale][field]). */
    public const I18N_FIELDS = ['title', 'description'];

    protected $table = 'gallery_albums';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'i18n',
        'photos_count',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'i18n'         => 'array',
        'photos_count' => 'integer',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(fn (Album $a) => $a->syncI18nDefaults());
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'album_id');
    }

    /**
     * Активные фотографии альбома в порядке показа (новые сверху).
     */
    public function activePhotos(): HasMany
    {
        return $this->photos()
            ->where('is_active', 1)
            ->orderByDesc('sort_order')
            ->orderByDesc('id');
    }

    /**
     * Пересчитать и сохранить счётчик фото + дату обновления.
     */
    public function refreshCounter(): void
    {
        $this->forceFill([
            'photos_count' => $this->photos()->count(),
            'updated_at'   => now(),
        ])->saveQuietly();
    }

    /**
     * Активные альбомы в порядке показа.
     */
    public function scopeActiveFeed(Builder $query): Builder
    {
        return $query
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->orderByDesc('id');
    }

    /**
     * Публичный URL альбома (/{locale}/gallery/{slug}).
     */
    public function getUrlAttribute(): string
    {
        return url(app()->getLocale() . '/gallery/' . $this->slug);
    }
}
