<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('access-admin', fn (User $user) => $user->isAdmin());

        Gate::define('manage-users', fn (User $user) => $user->isAdmin());

        Gate::define('manage-pedidos', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::Vendedor,
        ));

        Gate::define('manage-diseno', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::Disenador,
        ));

        Gate::define('register-corte', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::OperarioCorte,
        ));

        Gate::define('register-estampado', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::OperarioEstampado,
        ));

        Gate::define('register-costura', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::OperarioCostura,
        ));

        Gate::define('manage-calidad', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::SupervisorCalidad,
        ));

        Gate::define('manage-inventario', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::Almacenero,
        ));

        Gate::define('view-own-pedidos', fn (User $user) => $user->hasRole(
            UserRole::Admin,
            UserRole::Cliente,
        ));

        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
