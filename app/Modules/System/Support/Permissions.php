<?php

namespace App\Modules\System\Support;

/**
 * Реестр прав доступа.
 *
 * Дерево строится из модулей админки и их ресурсов (виджетов):
 *
 *   Модуль (api, system)
 *     └── Ресурс (messages, users, roles)
 *           └── Право (view, create, update, delete)
 *
 * Плоский ключ права: "{module}.{resource}.{ability}" — например "api.messages.view".
 *
 * Чтобы добавить новый ресурс в дерево прав — допишите его в tree().
 */
class Permissions
{
    /**
     * Базовый набор прав (abilities) для каждого ресурса.
     */
    public const ABILITIES = ['view', 'create', 'update', 'delete'];

    /**
     * Дерево: модуль → [label, resources => [resourceKey => label]].
     */
    public static function tree(): array
    {
        return [
            'api' => [
                'label'     => 'API',
                'resources' => [
                    'messages' => __('admin.nav.message_queue'),
                ],
            ],
            'system' => [
                'label'     => 'System',
                'resources' => [
                    'users'    => __('admin.users.model_plural'),
                    'roles'    => __('admin.roles.model_plural'),
                    'firewall' => __('admin.firewall.model_plural'),
                ],
            ],
        ];
    }

    /**
     * Все плоские ключи прав: ['api.messages.view', 'api.messages.create', ...].
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        $keys = [];

        foreach (self::tree() as $moduleKey => $module) {
            foreach (array_keys($module['resources']) as $resourceKey) {
                foreach (self::ABILITIES as $ability) {
                    $keys[] = "{$moduleKey}.{$resourceKey}.{$ability}";
                }
            }
        }

        return $keys;
    }

    /**
     * Опции прав для одного ресурса: ["{module}.{resource}.{ability}" => "Просмотр", ...].
     *
     * @return array<string, string>
     */
    public static function abilityOptions(string $moduleKey, string $resourceKey): array
    {
        $options = [];

        foreach (self::ABILITIES as $ability) {
            $options["{$moduleKey}.{$resourceKey}.{$ability}"] = __("admin.permissions.ability.{$ability}");
        }

        return $options;
    }

    /**
     * Ключ группы формы для ресурса (без точек — точки задают вложенность state).
     */
    public static function groupKey(string $moduleKey, string $resourceKey): string
    {
        return "{$moduleKey}__{$resourceKey}";
    }
}
