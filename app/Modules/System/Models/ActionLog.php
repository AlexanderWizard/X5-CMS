<?php

namespace App\Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Журнал действий пользователей админки.
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string|null $user_login
 * @property string      $event         created | updated | deleted | login
 * @property string|null $subject_type  короткое имя класса (User, Role, ...)
 * @property string|null $subject_label человекочитаемое имя раздела
 * @property int|null    $subject_id
 * @property array|null  $properties
 * @property string|null $ip_address
 * @property string      $created_at
 */
class ActionLog extends Model
{
    protected $table = 'action_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_login',
        'event',
        'subject_type',
        'subject_label',
        'subject_id',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Записать действие в журнал.
     *
     * Логируются только действия, совершённые авторизованным пользователем админки.
     */
    public static function log(
        string $event,
        ?Model $subject = null,
        ?User $actor = null,
        ?array $properties = null,
    ): void {
        $actor ??= auth('admin')->user();

        if (!$actor) {
            return;
        }

        static::create([
            'user_id'       => $actor->id,
            'user_login'    => $actor->login,
            'event'         => $event,
            'subject_type'  => $subject ? class_basename($subject) : null,
            'subject_label' => $subject ? static::labelFor($subject) : null,
            'subject_id'    => $subject?->getKey(),
            'properties'    => $properties,
            'ip_address'    => request()->ip(),
        ]);
    }

    /**
     * Человекочитаемое название раздела по модели.
     */
    public static function labelFor(Model $subject): string
    {
        return match (class_basename($subject)) {
            'MessageQueue' => __('admin.nav.message_queue'),
            'User'         => __('admin.users.model_plural'),
            'Role'         => __('admin.roles.model_plural'),
            'FirewallRule' => __('admin.firewall.model_plural'),
            default        => class_basename($subject),
        };
    }
}
