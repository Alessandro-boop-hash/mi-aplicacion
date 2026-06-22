<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-extrabold leading-tight text-stone-900">
            Panel principal - {{ $roleLabel }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="atelier-surface overflow-hidden rounded-lg">
                <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_18rem]">
                    <div>
                        <p class="text-sm font-bold uppercase text-indigo-700">Bienvenido</p>
                        <h3 class="mt-2 text-2xl font-extrabold text-stone-950 sm:text-3xl">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-600 sm:text-base">
                            Has iniciado sesion como <strong>{{ $roleLabel }}</strong>. Usa la navegacion superior para revisar pedidos, produccion, disenos o inventario segun tu rol.
                        </p>
                    </div>

                    <div class="atelier-soft-surface rounded-lg p-5">
                        <p class="text-xs font-bold uppercase text-stone-500">Estado de acceso</p>
                        <p class="mt-2 text-lg font-extrabold text-stone-950">Sesion activa</p>
                        <div class="mt-4 h-2 rounded-full bg-stone-200">
                            <div class="h-2 w-3/4 rounded-full bg-indigo-600"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
