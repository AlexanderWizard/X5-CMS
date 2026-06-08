<?php

namespace App\Modules\System\Filament\Resources\RoleResource\Concerns;

use App\Modules\System\Support\Permissions;

/**
 * Преобразование прав между хранением (плоский массив в колонке `permissions`)
 * и формой (сгруппированное дерево `permissions_tree[{module}__{resource}]`).
 *
 * Используется страницами CreateRole и EditRole.
 */
trait HandlesPermissions
{
    /**
     * Edit: разложить плоский список прав по группам ресурсов для формы.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $stored = $data['permissions'] ?? [];
        $tree   = [];

        foreach (Permissions::tree() as $moduleKey => $module) {
            foreach (array_keys($module['resources']) as $resourceKey) {
                $prefix = "{$moduleKey}.{$resourceKey}.";
                $group  = Permissions::groupKey($moduleKey, $resourceKey);

                $tree[$group] = array_values(array_filter(
                    $stored,
                    fn (string $perm) => str_starts_with($perm, $prefix)
                ));
            }
        }

        $data['permissions_tree'] = $tree;

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->flattenPermissions($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->flattenPermissions($data);
    }

    /**
     * Собрать выбранные права из групп формы в плоский уникальный массив.
     */
    private function flattenPermissions(array $data): array
    {
        $flat  = [];
        $valid = Permissions::all();

        foreach (($data['permissions_tree'] ?? []) as $selected) {
            foreach ((array) $selected as $perm) {
                if (in_array($perm, $valid, true)) {
                    $flat[] = $perm;
                }
            }
        }

        $data['permissions'] = array_values(array_unique($flat));

        unset($data['permissions_tree']);

        return $data;
    }
}
