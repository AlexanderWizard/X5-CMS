<?php

namespace App\Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Глобальная настройка сайта (key-value).
 * Источник правды — таблица settings; читается через статические
 * хелперы с мемоизацией на запрос. Значения хранятся строками
 * (булевы — '1'/'0', массивы — JSON).
 *
 * @property int         $id
 * @property string      $key
 * @property string|null $value
 * @property string      $created_at
 */
class Setting extends Model
{
    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /** @var array<string, string|null>|null кэш всех настроек на запрос */
    protected static ?array $cache = null;

    /**
     * Все настройки как [key => value]. Устойчиво к отсутствию таблицы.
     *
     * @return array<string, string|null>
     */
    public static function allValues(): array
    {
        if (static::$cache === null) {
            try {
                static::$cache = static::query()->pluck('value', 'key')->all();
            } catch (Throwable) {
                static::$cache = [];
            }
        }

        return static::$cache;
    }

    /**
     * Значение настройки (или $default, если пусто/нет).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::allValues()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Булева настройка (хранится как '1'/'0').
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::allValues()[$key] ?? null;

        return $value === null ? $default : in_array($value, ['1', 1, true, 'true'], true);
    }

    /**
     * Записать настройку (создаёт ключ при отсутствии) и обновить кэш.
     */
    public static function set(string $key, mixed $value): void
    {
        $value = match (true) {
            is_bool($value)  => $value ? '1' : '0',
            is_array($value) => json_encode($value, JSON_UNESCAPED_UNICODE),
            default          => (string) ($value ?? ''),
        };

        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        if (static::$cache !== null) {
            static::$cache[$key] = $value;
        }
    }

    /**
     * Сбросить кэш (на случай изменений в том же запросе извне).
     */
    public static function flushCache(): void
    {
        static::$cache = null;
    }
}
