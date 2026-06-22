<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Estampado — Lote corte #{{ $loteCorte->id }}</h2>
            <a href="{{ route('operario.estampado.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Volver</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4 text-sm mb-6">
                    <div><dt class="text-gray-500">Pedido</dt><dd class="font-medium">#{{ $loteCorte->pedido_id }}</dd></div>
                    <div><dt class="text-gray-500">Piezas del corte</dt><dd class="font-medium">{{ $loteCorte->piezas_obtenidas }}</dd></div>
                </dl>

                @if (! $loteEstampado || ! $loteEstampado->prueba_aprobada)
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Paso 1 — Prueba de impresión</h3>
                        <p class="text-sm text-gray-600 mb-4">Debe aprobar la prueba antes de registrar el procesamiento del lote completo.</p>
                        <form method="POST" action="{{ route('operario.estampado.prueba', $loteCorte) }}" class="space-y-4">
                            @csrf
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" name="prueba_aprobada" value="1" class="mt-0.5 rounded border-gray-300 text-indigo-600"
                                       {{ old('prueba_aprobada') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Confirmo que la prueba de impresión/sublimado fue realizada y aprobada</span>
                            </label>
                            @error('prueba_aprobada')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                            <button type="submit" class="inline-flex px-4 py-2 bg-amber-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-amber-700">
                                Registrar prueba aprobada
                            </button>
                        </form>
                    </div>
                @else
                    <div class="rounded-md bg-green-50 p-3 text-sm text-green-800 mb-6">
                        ✓ Prueba de impresión aprobada el {{ $loteEstampado->updated_at->format('d/m/Y H:i') }}
                    </div>

                    @if (! $loteEstampado->procesamiento_completado)
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2 — Procesamiento del lote</h3>
                            <form method="POST" action="{{ route('operario.estampado.store', $loteEstampado) }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="piezas_estampadas" class="block text-sm font-medium text-gray-700 mb-1">Piezas estampadas correctamente *</label>
                                        <input type="number" min="1" max="{{ $loteCorte->piezas_obtenidas }}" name="piezas_estampadas" id="piezas_estampadas"
                                               value="{{ old('piezas_estampadas') }}" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('piezas_estampadas')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="piezas_con_defecto" class="block text-sm font-medium text-gray-700 mb-1">Piezas con defecto *</label>
                                        <input type="number" min="0" name="piezas_con_defecto" id="piezas_con_defecto"
                                               value="{{ old('piezas_con_defecto') }}" required placeholder="Declarar defectos"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="mt-1 text-xs text-gray-500">Piezas con defecto generan solicitud de reposición automática.</p>
                                        @error('piezas_con_defecto')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <button type="submit" class="inline-flex px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                                    Completar estampado
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-600">
                            Lote procesado: {{ $loteEstampado->piezas_estampadas }} correctas, {{ $loteEstampado->piezas_con_defecto }} defectuosas.
                            @if ($loteEstampado->reposicion_solicitada)
                                <span class="text-amber-700 font-medium">Reposición solicitada.</span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
