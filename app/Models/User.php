<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'daily_rate',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string|array $roles): bool
    {
        if (!$this->role)
            return false;

        $roleType = $this->role->type;

        // Super Admin always has access
        if ($roleType === 'super_admin')
            return true;

        $roles = is_array($roles) ? $roles : explode(',', $roles);

        return in_array($roleType, $roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->type === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->type, ['admin', 'super_admin']);
    }

    public function isEditor(): bool
    {
        return $this->role && $this->role->type === 'editor';
    }

    public function isViewer(): bool
    {
        return $this->role && $this->role->type === 'viewer';
    }

    public function isSales(): bool
    {
        return $this->role && $this->role->type === 'sales';
    }

    public function isClient(): bool
    {
        return $this->role && $this->role->type === 'client';
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

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
            'password' => 'hashed',
        ];
    }
}