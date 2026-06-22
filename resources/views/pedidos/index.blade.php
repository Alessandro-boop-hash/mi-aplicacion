<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $routePrefix === 'cliente' ? 'Mis pedidos' : 'Pedidos' }}
            </h2>
            @if ($canCreate)
                <a href="{{ route($routePrefix.'.pedidos.create') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Registrar pedido
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6">
                <form method="GET" action="{{ route($routePrefix.'.pedidos.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="buscar" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}"
                               placeholder="N.º pedido o nombre de cliente"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="estado" id="estado"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los estados</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->value }}" @selected(request('estado') === $estado->value)>
                                    {{ $estado->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Filtrar
                        </button>
                        <a href="{{ route($routePrefix.'.pedidos.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">N.º</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pedidos as $pedido)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $pedido->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 hidden sm:table-cell">{{ $pedido->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $pedido->cantidad_total }} uds.</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">S/ {{ number_format($pedido->precio_total, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado->badgeClass() }}">
                                            {{ $pedido->estado->label() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route($routePrefix.'.pedidos.show', $pedido) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No se encontraron pedidos con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pedidos->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $pedidos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
