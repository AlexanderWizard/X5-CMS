<?php

namespace App\Modules\Cms\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Переиспользуемый текстовый блок (телефон, e-mail, адрес и т.п.).
 * Выводится в шаблонах директивой @block('slug').
 *
 * @property int         $id
 * @property string      $name
 * @property string      $slug
 * @property string|null $value
 * @property string      $created_at
 */
class Block extends Model
{
    use LogsActivity;

    protected $table = 'blocks';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
