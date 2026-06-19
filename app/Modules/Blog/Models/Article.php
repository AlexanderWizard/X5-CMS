<?php

namespace App\Modules\Blog\Models;

use App\Modules\Blog\Models\Concerns\HasI18n;
use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Статья блога.
 *
 * @property int         $id
 * @property int|null    $category_id
 * @property string      $title
 * @property string      $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $image
 * @property bool        $is_published
 * @property string|null $published_at
 * @property string      $created_at
 */
class Article extends Model
{
    use HasI18n;
    use LogsActivity;

    /** Переводимые поля (хранятся в i18n[locale][field]). */
    public const I18N_FIELDS = ['title', 'excerpt', 'content'];

    protected $table = 'blog_articles';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'i18n',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'i18n'         => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            // legacy-колонки title/excerpt/content = значения локали по умолчанию
            $article->syncI18nDefaults();

            // Опубликованным статьям без даты публикации проставляем текущий момент.
            if ($article->is_published && empty($article->published_at)) {
                $article->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_article_tag', 'article_id', 'tag_id');
    }

    /**
     * Публичный URL статьи (/{locale}/blog/{slug}).
     */
    public function getUrlAttribute(): string
    {
        return url(app()->getLocale() . '/blog/' . $this->slug);
    }

    /**
     * Опубликованные статьи в порядке ленты времени (новые сверху).
     */
    public function scopePublishedFeed(Builder $query): Builder
    {
        return $query
            ->where('is_published', 1)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }
}
