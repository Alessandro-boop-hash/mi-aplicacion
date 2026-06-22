<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis lotes pendientes — Estampado</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Pendientes: prueba de impresión</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote corte</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Piezas corte</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($lotesPendientesPrueba as $lote)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">#{{ $lote->id }}</td>
                                    <td class="px-4 py-3 text-sm">#{{ $lote->pedido_id }} — {{ $lote->pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $lote->piezas_obtenidas }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('operario.estampado.create', $lote) }}" class="text-indigo-600 text-sm font-medium">Registrar prueba</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Sin lotes pendientes de prueba.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Pendientes: procesamiento del lote</h3>
                    <p class="text-sm text-gray-500">Prueba aprobada — falta registrar piezas estampadas.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($lotesPendientesProceso as $lote)
                                <tr>
                                    <td class="px-4 py-3 text-sm">Estampado #{{ $lote->id }} (Corte #{{ $lote->lote_corte_id }})</td>
                                    <td class="px-4 py-3 text-sm">#{{ $lote->loteCorte->pedido_id }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('operario.estampado.create', $lote->loteCorte) }}" class="text-indigo-600 text-sm font-medium">Procesar lote</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">Sin lotes pendientes de procesamiento.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
