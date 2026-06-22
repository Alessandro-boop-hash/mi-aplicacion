<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis lotes pendientes — Costura</h2>
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
                    <h3 class="text-lg font-semibold">Lotes pendientes de costura</h3>
                    <p class="text-sm text-gray-500">Lotes de estampado completados sin registro de costura.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote estampado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Piezas a coser</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($lotesPendientes as $lote)
                                @php $esperadas = $lote->piezas_estampadas - $lote->piezas_con_defecto; @endphp
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">#{{ $lote->id }}</td>
                                    <td class="px-4 py-3 text-sm">#{{ $lote->loteCorte->pedido_id }} — {{ $lote->loteCorte->pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $esperadas }} uds.</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('operario.costura.create', $lote) }}" class="text-indigo-600 text-sm font-medium">Registrar costura</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No hay lotes pendientes de costura.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
