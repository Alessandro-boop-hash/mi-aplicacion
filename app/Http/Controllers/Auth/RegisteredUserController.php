<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $role = UserRole::tryFrom($request->role) ?? UserRole::Cliente;

        if ($role === UserRole::Admin) {
            $expectedCode = env('ADMIN_REGISTRATION_CODE', 'MARTE_SECRET_2026');
            if ($request->admin_code !== $expectedCode) {
                return back()->withErrors([
                    'admin_code' => 'El código de seguridad ingresado es incorrecto o no estás autorizado para crear una cuenta de administrador.'
                ])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
