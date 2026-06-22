<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function disenos(): HasMany
    {
        return $this->hasMany(Diseno::class, 'disenador_id');
    }

    public function lotesCorte(): HasMany
    {
        return $this->hasMany(LoteCorte::class, 'operario_id');
    }

    public function lotesEstampado(): HasMany
    {
        return $this->hasMany(LoteEstampado::class, 'operario_id');
    }

    public function lotesCostura(): HasMany
    {
        return $this->hasMany(LoteCostura::class, 'operario_id');
    }

    public function inspeccionesCalidad(): HasMany
    {
        return $this->hasMany(InspeccionCalidad::class, 'supervisor_id');
    }

    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }
}
