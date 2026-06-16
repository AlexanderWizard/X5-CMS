<?php

namespace App\Modules\System\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Язык сайта (управляется в админке). Реестр локалей для URL/переключателя/контента.
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 * @property bool   $is_default
 * @property bool   $is_active
 * @property int    $sort_order
 * @property string $created_at
 */
class Language extends Model
{
    use LogsActivity;

    protected $table = 'languages';

    public $timestamps = false;

    protected $fillable = ['code', 'name', 'is_default', 'is_active', 'sort_order'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    /** @var array<string, mixed> кэш на запрос */
    protected static array $cache = [];

    protected static function booted(): void
    {
        // Дефолтный язык — ровно один
        static::saving(function (Language $lang): void {
            if ($lang->is_default) {
                static::query()->where('id', '!=', $lang->id ?? 0)
                    ->where('is_default', 1)->update(['is_default' => 0]);
            }
        });

        $bust = fn () => static::$cache = [];
        static::saved($bust);
        static::deleted($bust);
    }

    /**
     * Активные языки (по порядку). Устойчиво к отсутствию таблицы.
     *
     * @return Collection<int, self>
     */
    public static function active(): Collection
    {
        if (! isset(static::$cache['active'])) {
            try {
                static::$cache['active'] = static::query()
                    ->where('is_active', 1)
                    ->orderBy('sort_order')->orderBy('code')
                    ->get();
            } catch (Throwable) {
                static::$cache['active'] = new Collection();
            }
        }

        return static::$cache['active'];
    }

    /**
     * Коды активных языков: ['en', 'ru']. Фолбэк — ['en'].
     *
     * @return array<int, string>
     */
    public static function codes(): array
    {
        $codes = static::active()->pluck('code')->all();

        return $codes ?: ['en'];
    }

    /**
     * Код языка по умолчанию (активного). Фолбэк — первый активный или 'en'.
     */
    public static function default(): string
    {
        if (! isset(static::$cache['default'])) {
            $default = static::active()->firstWhere('is_default', true)
                ?? static::active()->first();

            static::$cache['default'] = $default?->code ?? 'en';
        }

        return static::$cache['default'];
    }

    /**
     * Валиден ли код (есть среди активных).
     */
    public static function isValid(string $code): bool
    {
        return in_array($code, static::codes(), true);
    }
}
