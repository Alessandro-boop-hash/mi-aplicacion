<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis reclamos</h2>
            <button onclick="window.dispatchEvent(new CustomEvent('open-modal'))" class="inline-flex items-center px-4 py-2 bg-stone-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-stone-950 focus:outline-none focus:ring-2 focus:ring-stone-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Registrar reclamo
            </button>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ modalOpen: false }" @open-modal.window="modalOpen = true">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($reclamos) && count($reclamos) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reclamo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reclamos as $reclamo)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">REC-{{ str_pad($reclamo->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PED-{{ str_pad($reclamo->pedido_id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reclamo->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En revisión
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500">No tienes reclamos registrados en el sistema.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="modalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form method="POST" action="/cliente/reclamos">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Registrar Nuevo Reclamo</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="pedido_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Pedido Entregado</label>
                                        <select id="pedido_id" name="pedido_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                            <option value="">— Elija un pedido —</option>
                                            @if(isset($pedidosEntregados))
                                                @foreach($pedidosEntregados as $pedido)
                                                    <option value="{{ $pedido->id }}">PED-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</option>
                                                @endforeach
                                            @else
                                                <option value="1">PED-001 (Entregado hace 10 días)</option>
                                                <option value="2">PED-002 (Entregado hace 2 días)</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo del reclamo</label>
                                        <textarea id="motivo" name="motivo" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-stone-900 focus:ring-stone-900 sm:text-sm" placeholder="Describa el problema con las prendas..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-stone-900 text-base font-medium text-white hover:bg-stone-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Guardar Reclamo
                                </button>
                                <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>