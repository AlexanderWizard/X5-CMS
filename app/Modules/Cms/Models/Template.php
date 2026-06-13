<?php

namespace App\Modules\Cms\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Шаблон вывода страниц сайта.
 *
 * `body` — Blade-разметка. Доступные при рендере переменные:
 *   $title (заголовок), $content (HTML тела страницы), $children (коллекция),
 *   $page (модель Page), $appName (имя приложения).
 *
 * @property int         $id
 * @property string      $name
 * @property string      $slug
 * @property bool        $is_system
 * @property string|null $body
 * @property string      $created_at
 */
class Template extends Model
{
    use LogsActivity;

    protected $table = 'templates';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'is_system',
        'body',
    ];

    protected $casts = [
        'is_system'  => 'boolean',
        'created_at' => 'datetime',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'template_id');
    }
}
