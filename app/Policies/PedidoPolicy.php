<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Pedido;
use App\Models\User;

class PedidoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(
            UserRole::Vendedor,
            UserRole::Cliente,
            UserRole::Admin,
        );
    }

    public function view(User $user, Pedido $pedido): bool
    {
        if ($user->hasRole(UserRole::Vendedor, UserRole::Admin)) {
            return true;
        }

        if ($user->role === UserRole::Cliente) {
            return $user->cliente?->id === $pedido->cliente_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Vendedor, UserRole::Cliente);
    }
}
