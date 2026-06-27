<?php

namespace App\Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Правило 301/302-редиректа.
 *
 * from_path / to_path — пути без языкового префикса и ведущего слэша
 * («about-old» → «about», пустой to_path = главная). Сопоставление —
 * middleware App\Http\Middleware\HandleRedirects внутри языковой группы.
 *
 * @property int    $id
 * @property string $from_path
 * @property string $to_path
 * @property int    $status
 * @property bool   $is_active
 * @property int    $hits
 * @property string $created_at
 */
class Redirect extends Model
{
    protected $table = 'redirects';

    public $timestamps = false;

    protected $fillable = ['from_path', 'to_path', 'status', 'is_active', 'hits'];

    protected $casts = [
        'status'     => 'integer',
        'is_active'  => 'boolean',
        'hits'       => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Активные правила как [from_path => Redirect]. Устойчиво к отсутствию таблицы.
     *
     * @return array<string, self>
     */
    public static function activeMap(): array
    {
        try {
            return static::query()->where('is_active', 1)->get()->keyBy('from_path')->all();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Зафиксировать редирект old → new (вызывается при смене slug страницы).
     * Сшивает цепочки (existing → old становится existing → new), не плодит петли.
     */
    public static function capture(string $from, string $to): void
    {
        $from = trim($from, '/');
        $to   = trim($to, '/');

        if ($from === '' || $from === $to) {
            return;
        }

        // Существующие правила, ведущие на старый путь, перецеливаем на новый
        // (A→B при переименовании B→C превращается в A→C, без «висячего» B).
        static::query()->where('to_path', $from)->update(['to_path' => $to]);

        // Новый путь больше никуда не ведёт (если занимал from — снимаем).
        static::query()->where('from_path', $to)->delete();

        static::query()->updateOrCreate(
            ['from_path' => $from],
            ['to_path' => $to, 'status' => 301, 'is_active' => true],
        );
    }
}
