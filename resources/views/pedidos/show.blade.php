<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pedido #{{ $pedido->id }}
            </h2>
            <a href="{{ route($routePrefix.'.pedidos.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($routePrefix === 'cliente' && $pedido->disenos->isNotEmpty())
                <div class="rounded-md bg-indigo-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-sm text-indigo-900">
                        Tiene {{ $pedido->disenos->count() }} propuesta(s) de diseño para revisar.
                    </p>
                    <a href="{{ route('cliente.pedidos.disenos.revisar', $pedido) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                        Revisar diseños
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información general</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">Cliente</dt>
                                <dd class="font-medium text-gray-900">{{ $pedido->cliente->nombre }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Documento</dt>
                                <dd class="font-medium text-gray-900 uppercase">{{ $pedido->cliente->tipo_documento->value }}: {{ $pedido->cliente->numero_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Fecha</dt>
                                <dd class="font-medium text-gray-900">{{ $pedido->fecha->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Estado actual</dt>
                                <dd>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado->badgeClass() }}">
                                        {{ $pedido->estado->label() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Cantidad total</dt>
                                <dd class="font-medium text-gray-900">{{ $pedido->cantidad_total }} unidades</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Precio total</dt>
                                <dd class="font-medium text-gray-900">S/ {{ number_format($pedido->precio_total, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Anticipo (50%)</dt>
                                <dd class="font-medium text-gray-900">S/ {{ number_format($pedido->anticipo, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Saldo pendiente</dt>
                                <dd class="font-medium text-gray-900">S/ {{ number_format($pedido->saldo_pendiente, 2) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Detalle del pedido</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modelo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Talla</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">P. unit.</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($pedido->detalles as $detalle)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $detalle->modelo }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $detalle->talla }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $detalle->cantidad }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                S/ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de estados</h3>
                        <ol class="relative border-s border-gray-200 ms-3 space-y-6">
                            @forelse ($pedido->historialEstados->sortByDesc('created_at') as $historial)
                                <li class="ms-6">
                                    <span class="absolute -start-1.5 mt-1.5 h-3 w-3 rounded-full bg-indigo-500 border border-white"></span>
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit px-2 py-0.5 text-xs font-semibold rounded-full {{ $historial->estado->badgeClass() }}">
                                            {{ $historial->estado->label() }}
                                        </span>
                                        <time class="text-xs text-gray-500">{{ $historial->created_at->format('d/m/Y H:i') }}</time>
                                        @if ($historial->comentario)
                                            <p class="text-sm text-gray-700">{{ $historial->comentario }}</p>
                                        @endif
                                        @if ($historial->user)
                                            <p class="text-xs text-gray-400">Por: {{ $historial->user->name }}</p>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="ms-6 text-sm text-gray-500">Sin historial registrado.</li>
                            @endforelse
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
