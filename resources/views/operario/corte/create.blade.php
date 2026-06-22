<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar corte — Pedido #{{ $pedido->id }}</h2>
            <a href="{{ route('operario.corte.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Volver</a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        telaId: '{{ old('inventario_tela_id', $pedido->inventario_tela_id) }}',
        telas: @js($telas->map(fn ($t) => ['id' => $t->id, 'label' => $t->tipo_tela.' — '.$t->color, 'stock' => (float) $t->stock_actual_metros, 'minimo' => (float) $t->stock_minimo_metros])),
        metrosUsados: {{ old('metros_tela_usados', '') ?: 'null' }},
        merma: {{ old('merma_metros') !== null ? old('merma_metros') : 'null' }},
        get telaSeleccionada() {
            return this.telas.find(t => String(t.id) === String(this.telaId));
        },
        get totalConsumo() {
            const u = parseFloat(this.metrosUsados) || 0;
            const m = parseFloat(this.merma) || 0;
            return Math.round((u + m) * 100) / 100;
        },
        get stockInsuficiente() {
            if (!this.telaSeleccionada) return false;
            return this.totalConsumo > this.telaSeleccionada.stock;
        },
        get puedeEnviar() {
            return this.telaId && this.metrosUsados !== null && this.merma !== null && !this.stockInsuficiente;
        }
    }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                    <div>
                        <dt class="text-gray-500">Cliente</dt>
                        <dd class="font-medium">{{ $pedido->cliente->nombre }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Cantidad pedido</dt>
                        <dd class="font-medium">{{ $pedido->cantidad_total }} unidades</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Modelos</dt>
                        <dd class="font-medium">{{ $pedido->detalles->pluck('modelo')->unique()->join(', ') }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('operario.corte.store', $pedido) }}" class="space-y-4 border-t pt-6">
                    @csrf

                    <div>
                        <label for="inventario_tela_id" class="block text-sm font-medium text-gray-700 mb-1">Tela a utilizar *</label>
                        <select name="inventario_tela_id" id="inventario_tela_id" x-model="telaId" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— Seleccione tela —</option>
                            @foreach ($telas as $tela)
                                <option value="{{ $tela->id }}" @selected(old('inventario_tela_id', $pedido->inventario_tela_id) == $tela->id)>
                                    {{ $tela->tipo_tela }} — {{ $tela->color }} (Stock: {{ number_format($tela->stock_actual_metros, 2) }} m)
                                </option>
                            @endforeach
                        </select>
                        @error('inventario_tela_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <template x-if="telaSeleccionada">
                        <div class="rounded-lg p-4 text-sm"
                             :class="telaSeleccionada.stock <= telaSeleccionada.minimo ? 'bg-amber-50 border border-amber-200' : 'bg-green-50 border border-green-200'">
                            <p><strong>Stock disponible:</strong> <span x-text="telaSeleccionada.stock.toFixed(2)"></span> metros</p>
                            <p class="text-gray-600">Stock mínimo: <span x-text="telaSeleccionada.minimo.toFixed(2)"></span> m</p>
                            <p x-show="stockInsuficiente" class="mt-2 text-red-700 font-medium">
                                Stock insuficiente para el consumo indicado (<span x-text="totalConsumo.toFixed(2)"></span> m).
                            </p>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="metros_tela_usados" class="block text-sm font-medium text-gray-700 mb-1">Metros de tela usados *</label>
                            <input type="number" step="0.01" min="0.01" name="metros_tela_usados" id="metros_tela_usados"
                                   x-model="metrosUsados" required placeholder="Ej. 25.50"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('metros_tela_usados')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="merma_metros" class="block text-sm font-medium text-gray-700 mb-1">Merma / desperdicio (m) *</label>
                            <input type="number" step="0.01" min="0" name="merma_metros" id="merma_metros"
                                   x-model="merma" required placeholder="Declarar merma"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Debe ingresar este valor explícitamente (puede ser 0).</p>
                            @error('merma_metros')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="piezas_obtenidas" class="block text-sm font-medium text-gray-700 mb-1">Piezas obtenidas *</label>
                        <input type="number" min="1" name="piezas_obtenidas" id="piezas_obtenidas"
                               value="{{ old('piezas_obtenidas', $pedido->cantidad_total) }}" required
                               class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('piezas_obtenidas')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" :disabled="!puedeEnviar"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Confirmar corte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
