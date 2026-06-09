<?php

namespace App\Modules\System\Models;

use App\Modules\System\Support\LogsActivity;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int    $id
 * @property string $login
 * @property string $password
 * @property int    $is_active
 * @property int    $super_user
 * @property int    $failed_attempts
 * @property string $locale
 * @property string $timezone
 * @property string $created_at
 * @property string $last_login_at
 */
class User extends Authenticatable implements FilamentUser, HasName
{
    use LogsActivity;

    private const int MAX_FAILED_ATTEMPTS = 5;

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'login',
        'name',
        'password',
        'is_active',
        'super_user',
        'failed_attempts',
        'locale',
        'timezone',
        'last_login_at',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'super_user'    => 'boolean',
        'created_at'    => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Кэш агрегированных прав на время запроса.
     *
     * @var array<int, string>|null
     */
    protected ?array $cachedPermissions = null;

    /**
     * Роли, назначенные пользователю.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Суперпользователь — флаг `super_user`. Имеет приоритет и полный доступ ко всему,
     * независимо от назначенных ролей и прав.
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->super_user;
    }

    /**
     * Все права пользователя (объединение прав всех его ролей).
     *
     * @return array<int, string>
     */
    public function allPermissions(): array
    {
        return $this->cachedPermissions ??= $this->roles
            ->flatMap(fn (Role $role) => $role->permissions ?? [])
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Есть ли у пользователя право (ключ вида "api.messages.view").
     */
    public function hasPermissionTo(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->allPermissions(), true);
    }

    public function getFilamentName(): string
    {
        return filled($this->name) ? $this->name : $this->login;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isActive();
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function incrementFailedAttempts(): void
    {
        $this->failed_attempts += 1;

        if ($this->failed_attempts >= self::MAX_FAILED_ATTEMPTS) {
            $this->is_active = 0;
        }

        $this->save();
    }

    public function resetFailedAttempts(): void
    {
        $this->failed_attempts = 0;
        $this->save();
    }
}
