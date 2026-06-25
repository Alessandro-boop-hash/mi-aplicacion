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
    <body class="font-sans antialiased bg-white text-stone-900">
        <!-- Top Announcement Bar (Adiclub style) -->
        <div class="bg-stone-950 text-white text-center py-2 text-xs font-extrabold tracking-widest uppercase">
            ⚡ ENVÍO GRATIS PARA MIEMBROS DE MARTE CLUB • COMPRA AHORA ⚡
        </div>

        <main class="min-h-screen">
            <!-- Nav Bar -->
            <nav class="border-b border-stone-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-3">
                        <x-application-logo class="h-10 w-10 text-stone-900" />
                        <span class="text-sm font-black text-stone-900 uppercase tracking-widest">MARTE</span>
                    </a>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="inline-flex items-center justify-center rounded-none bg-stone-950 border-2 border-stone-950 text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:bg-white hover:text-stone-950 transition duration-150 shadow-none">
                                    Panel de Control &nbsp; →
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center justify-center rounded-none border-2 border-stone-950 bg-white text-stone-950 px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:bg-stone-950 hover:text-white transition duration-150 shadow-none">
                                    Ingresar
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                       class="hidden sm:inline-flex items-center justify-center rounded-none bg-stone-950 border-2 border-stone-950 text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest hover:bg-white hover:text-stone-950 transition duration-150 shadow-none">
                                        Registrarme
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </nav>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-8">
                {{-- Hero Section --}}
                <div class="relative overflow-hidden rounded-none p-8 sm:p-12 text-white shadow-none bg-stone-900"
                      style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat; border-bottom: 4px solid #000000;">
                    <div class="relative z-10 max-w-2xl space-y-5">
                        <span class="inline-flex rounded-none bg-white text-stone-900 px-3 py-1 text-xs font-black uppercase tracking-widest border-none">
                            LA PASIÓN NO SE DETIENE
                        </span>
                        <h1 class="text-4xl font-black tracking-tighter sm:text-6xl text-white uppercase italic leading-none">
                            ORDENA CADA PUNTADA DEL TALLER
                        </h1>
                        <p class="text-base sm:text-lg text-stone-200 font-medium leading-relaxed max-w-lg">
                            Controla pedidos, clientes, diseños, inventario y etapas de producción en tiempo real desde cualquier pantalla.
                        </p>
                        <div class="pt-4 flex flex-col sm:flex-row gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="inline-flex items-center justify-center rounded-none bg-white text-stone-900 px-6 py-3.5 text-xs font-black uppercase tracking-widest hover:bg-stone-100 transition duration-150">
                                    IR AL PANEL &nbsp; →
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center justify-center rounded-none bg-white text-stone-900 px-6 py-3.5 text-xs font-black uppercase tracking-widest hover:bg-stone-100 transition duration-150">
                                    INICIAR SESIÓN &nbsp; →
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                       class="inline-flex items-center justify-center rounded-none border-2 border-white bg-transparent text-white px-6 py-3.5 text-xs font-black uppercase tracking-widest hover:bg-white hover:text-stone-900 transition duration-150">
                                        REGISTRARME &nbsp; →
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Bottom sections: Catalog preview & How it works --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    {{-- Catalog Preview --}}
                    <div class="rounded-none border border-stone-200 bg-white p-6 shadow-none space-y-4">
                        <div class="flex justify-between items-center border-b border-stone-200 pb-3">
                            <h4 class="text-xs font-black uppercase tracking-widest text-stone-900">Catálogo Deportivo Premium</h4>
                            <span class="inline-flex rounded-none bg-stone-950 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-white">Colección 2026</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="group overflow-hidden rounded-none border border-stone-200 bg-[#f8f8f9] p-2 flex flex-col justify-between" style="height: 100%;">
                                <div class="aspect-square w-full overflow-hidden bg-[#f8f8f9]">
                                    <img src="/images/prendas/polo.png" alt="Polo Fit" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                                </div>
                                <p class="mt-2 text-center text-xs font-black uppercase tracking-wider text-stone-900 truncate">Polo Fit</p>
                            </div>
                            <div class="group overflow-hidden rounded-none border border-stone-200 bg-[#f8f8f9] p-2 flex flex-col justify-between" style="height: 100%;">
                                <div class="aspect-square w-full overflow-hidden bg-[#f8f8f9]">
                                    <img src="/images/prendas/casaca.png" alt="Casaca" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                                </div>
                                <p class="mt-2 text-center text-xs font-black uppercase tracking-wider text-stone-900 truncate">Cortaviento</p>
                            </div>
                            <div class="group overflow-hidden rounded-none border border-stone-200 bg-[#f8f8f9] p-2 flex flex-col justify-between" style="height: 100%;">
                                <div class="aspect-square w-full overflow-hidden bg-[#f8f8f9]">
                                    <img src="/images/prendas/mochila.png" alt="Mochila" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                                </div>
                                <p class="mt-2 text-center text-xs font-black uppercase tracking-wider text-stone-900 truncate">Mochila</p>
                            </div>
                        </div>
                        <div class="pt-2 text-center">
                            <a href="{{ route('login') }}" class="inline-block text-xs font-black uppercase tracking-widest text-stone-900 hover:text-stone-600 underline">
                                Ver todo el catálogo en línea
                            </a>
                        </div>
                    </div>

                    {{-- How it works --}}
                    <div class="rounded-none border border-stone-200 bg-white p-6 shadow-none space-y-5">
                        <h4 class="text-xs font-black uppercase tracking-widest text-stone-900 border-b border-stone-200 pb-3">¿Cómo funciona el taller MARTE?</h4>
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-none bg-stone-950 text-xs font-black text-white">1</span>
                                <div class="space-y-1">
                                    <h5 class="text-xs font-black uppercase tracking-wider text-stone-900">Configura tu Carrito</h5>
                                    <p class="text-xs text-stone-600 leading-relaxed font-semibold">Añade polos, casacas, licras o mochilas especificando tus tallas y cantidades.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 border-t border-stone-200 pt-4">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-none bg-stone-950 text-xs font-black text-white">2</span>
                                <div class="space-y-1">
                                    <h5 class="text-xs font-black uppercase tracking-wider text-stone-900">Sube y Aprueba tu Diseño</h5>
                                    <p class="text-xs text-stone-600 leading-relaxed font-semibold">Los diseñadores subirán propuestas de logotipos. Podrás verlas y aprobarlas en línea.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 border-t border-stone-200 pt-4">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-none bg-stone-950 text-xs font-black text-white">3</span>
                                <div class="space-y-1">
                                    <h5 class="text-xs font-black uppercase tracking-wider text-stone-900">Rastrea la Producción</h5>
                                    <p class="text-xs text-stone-600 leading-relaxed font-semibold">Sigue el avance en las estaciones de Corte, Estampado y Costura en tiempo real.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
