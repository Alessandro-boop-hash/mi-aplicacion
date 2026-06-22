<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'MARTE') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased">
        <main class="min-h-screen px-4 py-5 sm:px-6 lg:px-8">
            <nav class="mx-auto flex max-w-7xl items-center justify-between rounded-lg border border-stone-200 bg-white/80 px-4 py-3 shadow-sm backdrop-blur">
                <a href="/" class="flex items-center gap-3">
                    <x-application-logo class="h-11 w-11 text-orange-500" />
                    <span class="text-sm font-extrabold text-stone-900 sm:text-base">MARTE</span>
                </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-md bg-orange-500 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600">
                                Panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm font-bold text-stone-700 transition hover:border-orange-200 hover:bg-orange-50">
                                Ingresar
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hidden rounded-md bg-orange-500 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 sm:inline-flex">
                                    Registrarme
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>

            <section class="mx-auto grid max-w-7xl items-center gap-8 py-10 sm:py-14 lg:grid-cols-[1.05fr_.95fr] lg:py-16">
                <div class="max-w-2xl">
                    <p class="mb-4 inline-flex rounded-full border border-stone-200 bg-white/80 px-3 py-1 text-xs font-bold uppercase text-stone-600 shadow-sm">
                        Gestion integral para pedidos textiles
                    </p>
                    <h1 class="text-4xl font-extrabold leading-tight text-stone-950 sm:text-5xl lg:text-6xl">
                        Ordena cada puntada del taller.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-stone-600 sm:text-lg">
                        Controla pedidos, clientes, disenos, inventario y etapas de produccion desde una interfaz clara, calida y lista para trabajar en cualquier pantalla.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-md bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600">
                                Ir al panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600">
                                Iniciar sesion
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-stone-300 bg-white px-5 py-3 text-sm font-bold text-stone-700 transition hover:border-orange-200 hover:bg-orange-50 sm:hidden">
                                    Crear cuenta
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Catalog Preview --}}
                    <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-stone-500">Catálogo Deportivo Premium</h4>
                            <span class="inline-flex rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-850">Colección 2026</span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                            <div class="group overflow-hidden rounded-lg border border-stone-100 bg-stone-50 p-1.5 flex flex-col justify-between" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div style="aspect-ratio: 1/1; width: 100%; overflow: hidden; border-radius: 6px; background-color: #e7e5e4;">
                                    <img src="/images/prendas/polo.png" alt="Polo Fit" style="height: 100%; width: 100%; object-fit: cover;">
                                </div>
                                <p class="mt-2 text-center text-xs font-bold text-stone-800 truncate">Polo Fit</p>
                            </div>
                            <div class="group overflow-hidden rounded-lg border border-stone-100 bg-stone-50 p-1.5 flex flex-col justify-between" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div style="aspect-ratio: 1/1; width: 100%; overflow: hidden; border-radius: 6px; background-color: #e7e5e4;">
                                    <img src="/images/prendas/casaca.png" alt="Casaca" style="height: 100%; width: 100%; object-fit: cover;">
                                </div>
                                <p class="mt-2 text-center text-xs font-bold text-stone-800 truncate">Cortaviento</p>
                            </div>
                            <div class="group overflow-hidden rounded-lg border border-stone-100 bg-stone-50 p-1.5 flex flex-col justify-between" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div style="aspect-ratio: 1/1; width: 100%; overflow: hidden; border-radius: 6px; background-color: #e7e5e4;">
                                    <img src="/images/prendas/mochila.png" alt="Mochila" style="height: 100%; width: 100%; object-fit: cover;">
                                </div>
                                <p class="mt-2 text-center text-xs font-bold text-stone-800 truncate">Mochila</p>
                            </div>
                        </div>
                    </div>

                    {{-- How it works --}}
                    <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-stone-500">¿Cómo funciona el taller MARTE?</h4>
                        <div class="space-y-3">
                            <div class="flex gap-3.5">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-600">1</span>
                                <div class="space-y-0.5">
                                    <h5 class="text-sm font-bold text-stone-900">Configura tu Carrito</h5>
                                    <p class="text-xs text-stone-600">Añade polos, casacas, licras o mochilas especificando tus tallas y cantidades.</p>
                                </div>
                            </div>
                            <div class="flex gap-3.5 border-t border-stone-100 pt-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-600">2</span>
                                <div class="space-y-0.5">
                                    <h5 class="text-sm font-bold text-stone-900">Sube y Aprueba tu Diseño</h5>
                                    <p class="text-xs text-stone-600">Los diseñadores subirán propuestas de logotipos. Podrás verlas y aprobarlas en línea.</p>
                                </div>
                            </div>
                            <div class="flex gap-3.5 border-t border-stone-100 pt-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-600">3</span>
                                <div class="space-y-0.5">
                                    <h5 class="text-sm font-bold text-stone-900">Rastrea la Producción</h5>
                                    <p class="text-xs text-stone-600">Sigue el avance en las estaciones de Corte, Estampado y Costura en tiempo real.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
