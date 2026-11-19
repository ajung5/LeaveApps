<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    // Selaraskan atribut pada objek User baru dengan default is_active=1 di migration.
    // actingAs($user) pada pengujian tidak melakukan query ulang ke database.
    protected $attributes = ['is_active' => true];

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['password' => 'hashed', 'is_active' => 'boolean']; }
    public function employee(): HasOne { return $this->hasOne(Employee::class); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
