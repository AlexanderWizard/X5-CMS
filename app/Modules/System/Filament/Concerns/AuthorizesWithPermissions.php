<?php

namespace App\Modules\System\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Привязывает стандартные методы авторизации Filament-ресурса к дереву прав.
 *
 * Ресурс должен объявить префикс права:
 *
 *     protected static string $permissionPrefix = 'system.users';
 *
 * Тогда:
 *   - список / просмотр  → {prefix}.view   (также управляет видимостью в меню)
 *   - создание           → {prefix}.create
 *   - редактирование     → {prefix}.update
 *   - удаление           → {prefix}.delete
 */
trait AuthorizesWithPermissions
{
    public static function canViewAny(): bool
    {
        return static::userCan('view');
    }

    public static function canView(Model $record): bool
    {
        return static::userCan('view');
    }

    public static function canCreate(): bool
    {
        return static::userCan('create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::userCan('update');
    }

    public static function canDelete(Model $record): bool
    {
        return static::userCan('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::userCan('delete');
    }

    protected static function userCan(string $ability): bool
    {
        $user = auth('admin')->user();

        if (! $user) {
            return false;
        }

        return $user->hasPermissionTo(static::$permissionPrefix . '.' . $ability);
    }
}
