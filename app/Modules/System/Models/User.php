<?php

namespace App\Modules\System\Models;

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
 * @property int    $failed_attempts
 * @property string $locale
 * @property string $created_at
 */
class User extends Authenticatable implements FilamentUser, HasName
{
    private const int MAX_FAILED_ATTEMPTS = 5;

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'login',
        'password',
        'is_active',
        'failed_attempts',
        'locale',
        'timezone',
        'last_login_at',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Роли, назначенные пользователю.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function getFilamentName(): string
    {
        return $this->login;
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
