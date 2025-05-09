<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * Represents a user within the application.
 *
 *
 * @property int $id The unique identifier for the user
 * @property string $name The user's full name
 * @property string $email The user's email address
 * @property string $role The user's role
 * @property \Illuminate\Support\Carbon|null $email_verified_at When the email was verified
 * @property string $password The hashed user password
 * @property string|null $remember_token Authentication remember token
 * @property \Illuminate\Support\Carbon $created_at When the user was created
 * @property \Illuminate\Support\Carbon $updated_at When the user was last updated
 *
 * @method static \Database\Factories\UserFactory factory() Create a new factory instance
 *
 * @uses \Illuminate\Database\Eloquent\Factories\HasFactory For model factory support
 * @uses \Illuminate\Notifications\Notifiable For sending notifications
 * @uses \App\Traits\BelongsToTenant For multi-tenancy support
 *
 * @extends \Illuminate\Foundation\Auth\User
 * @implements \Illuminate\Contracts\Auth\Authenticatable
 * @implements \Illuminate\Contracts\Auth\Access\Authorizable
 * @implements \Illuminate\Contracts\Auth\CanResetPassword
 */
class User extends Authenticatable
{
    use BelongsToTenant, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'tenant_user', 'user_id', 'tenant_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin->value;
    }
}
