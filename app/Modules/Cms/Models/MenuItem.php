<?php

namespace App\Modules\Cms\Models;

use App\Modules\Cms\Models\Concerns\ResolvesMenuLink;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Пункт верхнего меню сайта (конструктор меню).
 *
 * Тип ссылки:
 *   home — локализованная главная (/{locale})
 *   page — страница CMS (по page_id, URL берётся из Page::getUrlAttribute)
 *   url  — произвольный адрес (см. resolvedUrl: схема/ведущий слэш — как есть,
 *          иначе трактуется как локализованный путь /{locale}/{url})
 *
 * @property int         $id
 * @property string      $title
 * @property array|null  $i18n
 * @property string      $type
 * @property int|null    $page_id
 * @property string|null $url
 * @property bool        $new_tab
 * @property bool        $is_active
 * @property int         $sort_order
 * @property string      $created_at
 */
class MenuItem extends Model
{
    use LogsActivity;
    use ResolvesMenuLink;

    public const TYPE_HOME = 'home';
    public const TYPE_PAGE = 'page';
    public const TYPE_URL  = 'url';

    protected $table = 'menu_items';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'i18n',
        'type',
        'page_id',
        'url',
        'new_tab',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'i18n'       => 'array',
        'new_tab'    => 'boolean',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(fn (MenuItem $item) => $item->syncTitleDefault());
    }

    /**
     * Активные пункты верхнего меню в порядке сортировки.
     *
     * @return Collection<int, self>
     */
    public static function topMenu(): Collection
    {
        return static::query()
            ->where('is_active', 1)
            ->with('page')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
