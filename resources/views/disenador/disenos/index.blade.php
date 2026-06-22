<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pedidos en diseño</h2>
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Propuestas</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($pedidos as $pedido)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $pedido->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pedido->cliente->nombre }}</td>
                                    <td class="px-4 py-3 hidden sm:table-cell">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado->badgeClass() }}">
                                            {{ $pedido->estado->label() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">
                                        {{ $pedido->disenos->count() }}
                                        @if ($pedido->disenos->where('bloqueado', true)->isNotEmpty())
                                            <span class="text-green-600 text-xs">(aprobado)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if ($pedido->disenos->where('bloqueado', true)->isEmpty())
                                            <a href="{{ route('disenador.disenos.create', $pedido) }}"
                                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                Subir propuesta
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">Bloqueado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No hay pedidos pendientes de diseño.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($pedidos->hasPages())
                    <div class="px-4 py-3 border-t">{{ $pedidos->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
