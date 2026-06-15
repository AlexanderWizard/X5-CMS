<?php

namespace App\Modules\Cms\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Страница сайта (древовидная структура через parent_id).
 *
 * @property int         $id
 * @property int|null    $parent_id
 * @property string      $title
 * @property string      $slug
 * @property string|null $content
 * @property bool        $is_home
 * @property bool        $is_active
 * @property int         $sort_order
 * @property string      $created_at
 */
class Page extends Model
{
    use LogsActivity;

    /** Поддерживаемые локали сайта и локаль по умолчанию (без префикса → редирект на неё). */
    public const LOCALES = ['en', 'ru'];
    public const DEFAULT_LOCALE = 'en';

    /** Поля контента, которые переводятся (хранятся в i18n[locale]). */
    public const TRANSLATABLE = ['title', 'content', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $table = 'pages';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'template_id',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'i18n',
        'is_home',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'i18n'       => 'array',
        'is_home'    => 'boolean',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Локализованное значение поля контента: текущая локаль → локаль по умолчанию → колонка.
     */
    public function tr(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: static::currentLocale();
        $i18n   = $this->i18n ?? [];

        return $i18n[$locale][$field]
            ?? $i18n[static::DEFAULT_LOCALE][$field]
            ?? $this->getAttribute($field);
    }

    /**
     * Текущая локаль сайта (валидная из списка, иначе — по умолчанию).
     */
    public static function currentLocale(): string
    {
        $locale = app()->getLocale();

        return in_array($locale, static::LOCALES, true) ? $locale : static::DEFAULT_LOCALE;
    }

    protected static function booted(): void
    {
        // Главная страница может быть только одна — снимаем флаг с остальных.
        static::saving(function (Page $page): void {
            if ($page->is_home) {
                static::query()
                    ->where('id', '!=', $page->id ?? 0)
                    ->where('is_home', 1)
                    ->update(['is_home' => 0]);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Глубина вложенности (0 — корень).
     */
    public function getDepthAttribute(): int
    {
        $depth  = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    /**
     * ID самой страницы и всех её потомков (для исключения из выбора родителя).
     *
     * @return array<int, int>
     */
    public function descendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    /**
     * Полный путь страницы из slug-ов предков: about/team.
     * Главная страница прозрачна (её slug не попадает в URL — она живёт на "/").
     */
    public function getPathAttribute(): string
    {
        $segments = [];
        $node     = $this;

        while ($node) {
            if (!$node->is_home) {
                array_unshift($segments, $node->slug);
            }
            $node = $node->parent;
        }

        return implode('/', $segments);
    }

    /**
     * Публичный URL страницы с языковым префиксом (главная → "/{locale}").
     */
    public function getUrlAttribute(): string
    {
        $locale = static::currentLocale();

        return $this->is_home ? url($locale) : url($locale . '/' . $this->path);
    }

    /**
     * URL страницы под конкретной локалью (для переключателя языка).
     */
    public function urlFor(string $locale): string
    {
        return $this->is_home ? url($locale) : url($locale . '/' . $this->path);
    }

    /**
     * Найти активную страницу по иерархическому пути (about/team).
     */
    public static function findByPath(string $path): ?self
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if (empty($segments)) {
            return null;
        }

        $homeId   = static::query()->where('is_home', 1)->value('id');
        $parentId = null;
        $page     = null;
        $first    = true;

        foreach ($segments as $segment) {
            $query = static::query()
                ->where('slug', $segment)
                ->where('is_active', 1);

            if ($first) {
                // Верхний уровень — это корни (parent_id NULL) либо прямые дети главной
                $query->where(function ($w) use ($homeId) {
                    $w->whereNull('parent_id');

                    if ($homeId) {
                        $w->orWhere('parent_id', $homeId);
                    }
                });
            } else {
                $query->where('parent_id', $parentId);
            }

            $page = $query->first();

            if (!$page || $page->is_home) {
                return null;
            }

            $parentId = $page->id;
            $first    = false;
        }

        return $page;
    }

    /**
     * Главная страница лендинга.
     */
    public static function home(): ?self
    {
        return static::query()->where('is_home', 1)->where('is_active', 1)->first();
    }

    /**
     * Пункты главного меню — активные страницы верхнего уровня
     * (корни и прямые дети главной), кроме самой главной.
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function navItems(): \Illuminate\Support\Collection
    {
        $homeId = static::query()->where('is_home', 1)->value('id');

        return static::query()
            ->where('is_active', 1)
            ->where('is_home', 0)
            ->where(function ($q) use ($homeId) {
                $q->whereNull('parent_id');

                if ($homeId) {
                    $q->orWhere('parent_id', $homeId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Цепочка предков от верхнего уровня до самой страницы (для хлебных крошек),
     * без главной страницы-корня.
     *
     * @return array<int, self>
     */
    public function ancestorsTrail(): array
    {
        $trail = [];
        $node  = $this;

        while ($node) {
            if (!$node->is_home) {
                array_unshift($trail, $node);
            }
            $node = $node->parent;
        }

        return $trail;
    }
}
