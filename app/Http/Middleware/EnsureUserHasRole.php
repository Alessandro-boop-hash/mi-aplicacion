<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'No autorizado.');
        }

        if ($user->role === UserRole::Admin) {
            return $next($request);
        }

        if (! in_array($user->role->value, $roles, true)) {
            abort(403, 'No tiene permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
