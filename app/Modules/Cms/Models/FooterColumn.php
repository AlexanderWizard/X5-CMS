<?php

namespace App\Modules\Cms\Models;

use App\Modules\System\Models\Language;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Колонка футера (конструктор подвала).
 *
 * @property int        $id
 * @property string     $title
 * @property array|null $i18n
 * @property bool       $is_active
 * @property int        $sort_order
 * @property string     $created_at
 */
class FooterColumn extends Model
{
    use LogsActivity;

    protected $table = 'footer_columns';

    public $timestamps = false;

    protected $fillable = ['title', 'i18n', 'is_active', 'sort_order'];

    protected $casts = [
        'i18n'       => 'array',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (FooterColumn $col): void {
            $i18n       = $col->i18n ?? [];
            $col->title = $i18n[Language::default()]['title']
                ?? ($col->title ?: 'Column');
        });
    }

    public function links(): HasMany
    {
        return $this->hasMany(FooterLink::class, 'column_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Локализованный заголовок колонки.
     */
    public function tr(string $field = 'title', ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $locale = Language::isValid($locale) ? $locale : Language::default();
        $i18n   = $this->i18n ?? [];

        return $i18n[$locale][$field]
            ?? $i18n[Language::default()][$field]
            ?? $this->getAttribute($field);
    }

    /**
     * Активные колонки футера с активными ссылками, в порядке сортировки.
     *
     * @return Collection<int, self>
     */
    public static function activeWithLinks(): Collection
    {
        return static::query()
            ->where('is_active', 1)
            ->with(['links' => fn ($q) => $q->where('is_active', 1), 'links.page'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn (self $col) => $col->links->isNotEmpty())
            ->values();
    }
}
