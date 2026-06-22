<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Diseno;
use App\Models\User;

class DisenoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(
            UserRole::Disenador,
            UserRole::Cliente,
            UserRole::Admin,
        );
    }

    public function view(User $user, Diseno $diseno): bool
    {
        return $this->canAccessPedido($user, $diseno);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Disenador, UserRole::Admin);
    }

    public function approve(User $user, Diseno $diseno): bool
    {
        if ($diseno->bloqueado || $diseno->aprobado) {
            return false;
        }

        if ($user->hasRole(UserRole::Admin)) {
            return true;
        }

        if ($user->role !== UserRole::Cliente) {
            return false;
        }

        return $user->cliente?->id === $diseno->pedido->cliente_id;
    }

    public function viewOriginal(User $user, Diseno $diseno): bool
    {
        return $user->hasRole(UserRole::Disenador, UserRole::Admin);
    }

    public function viewWatermarked(User $user, Diseno $diseno): bool
    {
        if ($user->hasRole(UserRole::Disenador, UserRole::Admin)) {
            return true;
        }

        if ($user->role === UserRole::Cliente) {
            return $user->cliente?->id === $diseno->pedido->cliente_id;
        }

        return false;
    }

    public function update(User $user, Diseno $diseno): bool
    {
        if ($diseno->bloqueado) {
            return false;
        }

        return $user->hasRole(UserRole::Disenador, UserRole::Admin);
    }

    private function canAccessPedido(User $user, Diseno $diseno): bool
    {
        if ($user->hasRole(UserRole::Disenador, UserRole::Admin)) {
            return true;
        }

        if ($user->role === UserRole::Cliente) {
            return $user->cliente?->id === $diseno->pedido->cliente_id;
        }

        return false;
    }
}
