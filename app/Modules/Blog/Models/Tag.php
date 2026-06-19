<?php

namespace App\Modules\Blog\Models;

use App\Modules\Blog\Models\Concerns\HasI18n;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Тег блога.
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 */
class Tag extends Model
{
    use HasI18n;
    use LogsActivity;

    /** Переводимые поля (хранятся в i18n[locale][field]). */
    public const I18N_FIELDS = ['name'];

    protected $table = 'blog_tags';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'i18n'];

    protected $casts = [
        'i18n'       => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(fn (Tag $t) => $t->syncI18nDefaults());
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'blog_article_tag', 'tag_id', 'article_id');
    }
}
