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
        'i18n',
    ];

    protected $casts = [
        'i18n'       => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // legacy-колонка value = значение локали по умолчанию (фолбэк/совместимость)
        static::saving(function (Block $block): void {
            $i18n = $block->i18n ?? [];
            $block->value = $i18n[Page::DEFAULT_LOCALE]
                ?? ($i18n['ru'] ?? $block->value);
        });
    }

    /**
     * Локализованное значение блока: текущая локаль → дефолт → колонка value.
     */
    public function localized(?string $locale = null): ?string
    {
        $locale = $locale ?: Page::currentLocale();
        $i18n   = $this->i18n ?? [];

        return $i18n[$locale]
            ?? $i18n[Page::DEFAULT_LOCALE]
            ?? $this->value;
    }
}
