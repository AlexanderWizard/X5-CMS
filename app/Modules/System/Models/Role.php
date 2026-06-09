<?php

namespace App\Modules\System\Models;

use App\Modules\System\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property array       $permissions
 * @property string      $created_at
 */
class Role extends Model
{
    use LogsActivity;

    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
        'created_at'  => 'datetime',
    ];

    /**
     * Есть ли у роли указанное право (ключ вида "api.messages.view").
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? [], true);
    }

    /**
     * Пользователи, которым назначена эта роль.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
