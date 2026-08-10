<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const PRIMARY_SUPER_ADMIN_EMAIL = 'superadmin@example.com';

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
        'status',
        'google_id',
        'avatar',
        'last_login_at',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function usesGoogleAuthentication(): bool
    {
        return filled($this->google_id);
    }

    public function authProviderLabel(): string
    {
        return $this->usesGoogleAuthentication() ? 'Google' : 'Email';
    }

    public function isProtectedPrimarySuperAdmin(): bool
    {
        return $this->isSuperAdmin() && strcasecmp($this->email, self::PRIMARY_SUPER_ADMIN_EMAIL) === 0;
    }

    /**
     * Resolve the current avatar without emitting broken or duplicated storage URLs.
     */
    public function getAvatarUrlAttribute(): string
    {
        $avatar = trim((string) $this->avatar);

        if (Str::startsWith($avatar, ['https://', 'http://'])) {
            return $avatar;
        }

        $path = ltrim($avatar, '/');
        $path = Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path;

        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return $this->default_avatar_url;
    }

    public function getDefaultAvatarUrlAttribute(): string
    {
        return asset('assets/images/personal-information.png');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'created_by');
    }
}
