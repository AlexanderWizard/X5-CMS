<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int    $id
 * @property string $login
 * @property string $password
 * @property int    $is_active
 * @property int    $failed_attempts
 * @property string $created_at
 *
 * @method static static|null first()
 * @method static static      updateOrCreate(array $attributes, array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder where(string $column, mixed $value)
 * @method static int         whereIn(string $column, array $values)
 */
class DocsUser extends Authenticatable implements FilamentUser
{
    private const int MAX_FAILED_ATTEMPTS = 5;

    protected $table = 'docs_users';

    public $timestamps = false;

    protected $fillable = [
        'login',
        'password',
        'is_active',
        'failed_attempts',
    ];

    protected $hidden = [
        'password',
    ];

    // Filament использует 'email' по умолчанию — переопределяем на 'login'
    public function getAuthIdentifierName(): string
    {
        return 'login';
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
