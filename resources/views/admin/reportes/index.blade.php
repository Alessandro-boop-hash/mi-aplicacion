<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes y Estadísticas
            </h2>
            <a href="{{ route('admin.reportes.pdf') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Descargar Reporte PDF
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-black">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ventas Totales (Activas)</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-gray-900">S/ {{ number_format($ventasTotales, 2) }}</dd>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-black">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pedidos Registrados</dt>
                    <dd class="mt-2 text-3xl font-extrabold text-gray-900">{{ $pedidosTotales }}</dd>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Pedidos por Estado -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden lg:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pedidos por Estado</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pedidos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($estadosCount as $key => $estado)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $estado['badge'] }}">
                                                {{ $estado['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-bold text-right">
                                            {{ $estado['count'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Modelos -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Prendas Solicitadas</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modelo</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($topModelos as $modelo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $modelo->modelo }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-bold text-right">{{ $modelo->total_piezas }} uds.</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-center text-sm text-gray-500">No hay prendas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pedidos Recientes -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Últimos Pedidos Registrados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">N.º</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($pedidosRecientes as $pedido)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">#{{ $pedido->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $pedido->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 text-right">{{ $pedido->cantidad_total }} uds.</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">S/ {{ number_format($pedido->precio_total, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado->badgeClass() }}">
                                            {{ $pedido->estado->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
