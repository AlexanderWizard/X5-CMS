<?php

namespace App\Modules\System\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Перевод строки интерфейса (i18n в БД).
 * Источник правды для строк админки — эта таблица (а не lang/*.php).
 * Читается в рантайме через App\Modules\System\Support\DatabaseTranslationLoader.
 *
 * @property int         $id
 * @property string      $group   группа (= "файл" перевода, напр. admin)
 * @property string      $key     ключ внутри группы, напр. users.nav
 * @property string      $locale  локаль (ru/en)
 * @property string|null $value   перевод
 * @property string      $created_at
 */
class Translation extends Model
{
    use LogsActivity;

    protected $table = 'translations';

    public $timestamps = false;

    protected $fillable = [
        'group',
        'key',
        'locale',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
