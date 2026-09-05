<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, HasRoles, HasUuids, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = ['name', 'email', 'password', 'is_active', 'must_change_password', 'disabled_at'];
    protected $hidden = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'disabled_at' => 'immutable_datetime',
            'last_login_at' => 'immutable_datetime',
            'two_factor_confirmed_at' => 'immutable_datetime',
        ];
    }
}
