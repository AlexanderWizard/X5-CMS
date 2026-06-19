<?php

namespace App\Modules\Cms\Models;

use App\Modules\Cms\Models\Concerns\ResolvesMenuLink;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ссылка внутри колонки футера. Логика типа/URL/подписи — в трейте ResolvesMenuLink
 * (общая с MenuItem).
 *
 * @property int         $id
 * @property int         $column_id
 * @property string      $title
 * @property array|null  $i18n
 * @property string      $type
 * @property int|null    $page_id
 * @property string|null $url
 * @property bool        $new_tab
 * @property bool        $is_active
 * @property int         $sort_order
 */
class FooterLink extends Model
{
    use LogsActivity;
    use ResolvesMenuLink;

    public const TYPE_HOME = 'home';
    public const TYPE_PAGE = 'page';
    public const TYPE_URL  = 'url';

    protected $table = 'footer_links';

    public $timestamps = false;

    protected $fillable = [
        'column_id',
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
    ];

    protected static function booted(): void
    {
        static::saving(fn (FooterLink $link) => $link->syncTitleDefault());
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(FooterColumn::class, 'column_id');
    }
}
