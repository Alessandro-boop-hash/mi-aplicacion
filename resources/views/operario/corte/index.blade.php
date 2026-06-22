<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis lotes pendientes — Corte</h2>
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
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pedidos pendientes de corte</h3>
                    <p class="text-sm text-gray-500 mt-1">Pedidos en producción sin lote de corte registrado.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Modelo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Tela asignada</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($pedidosPendientes as $pedido)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium">#{{ $pedido->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">
                                        {{ $pedido->detalles->pluck('modelo')->unique()->join(', ') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $pedido->cantidad_total }} uds.</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">
                                        @if ($pedido->inventarioTela)
                                            {{ $pedido->inventarioTela->tipo_tela }} ({{ $pedido->inventarioTela->color }})
                                        @else
                                            <span class="text-amber-600">Por asignar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('operario.corte.create', $pedido) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Registrar corte
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No hay pedidos pendientes de corte.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($lotesCompletados->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis cortes recientes</h3>
                    <ul class="divide-y divide-gray-200 text-sm">
                        @foreach ($lotesCompletados as $lote)
                            <li class="py-3 flex justify-between">
                                <span>Pedido #{{ $lote->pedido_id }} — {{ $lote->piezas_obtenidas }} piezas</span>
                                <span class="text-gray-500">{{ $lote->fecha->format('d/m/Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
