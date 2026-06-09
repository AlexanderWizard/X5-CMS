<?php

namespace App\Modules\System\Support;

use App\Modules\System\Models\ActionLog;

/**
 * Автоматически пишет в журнал действий создание/изменение/удаление модели,
 * если действие выполнено авторизованным пользователем админки.
 *
 * Подключается к модели: `use LogsActivity;`
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model): void {
            ActionLog::log('created', $model);
        });

        static::updated(function ($model): void {
            $changed = array_keys($model->getChanges());

            ActionLog::log('updated', $model, properties: $changed ? ['changed' => $changed] : null);
        });

        static::deleted(function ($model): void {
            ActionLog::log('deleted', $model);
        });
    }
}
