<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'phone', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'administrator'], true);
    }

    public function isGateStaff(): bool
    {
        return in_array($this->role, ['controller', 'gate', 'staff', 'checkin'], true);
    }

    public function canAccessEvent(Event $event): bool
    {
        return $this->id === $event->user_id || $this->isAdmin();
    }
}
