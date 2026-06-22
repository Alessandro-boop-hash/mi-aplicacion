<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar costura — Lote #{{ $loteEstampado->id }}</h2>
            <a href="{{ route('operario.costura.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">← Volver</a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        cosidas: {{ old('piezas_cosidas') !== null ? old('piezas_cosidas') : 'null' }},
        merma: {{ old('piezas_merma') !== null ? old('piezas_merma') : 'null' }},
        esperadas: {{ $piezasEsperadas }},
        get total() { return (parseInt(this.cosidas) || 0) + (parseInt(this.merma) || 0); },
        get cuadra() { return this.cosidas !== null && this.merma !== null && this.total === this.esperadas; },
        get mensajeError() {
            if (this.cosidas === null || this.merma === null) return '';
            const diff = this.total - this.esperadas;
            if (diff === 0) return '';
            return diff > 0 ? 'Sobran ' + diff + ' pieza(s).' : 'Faltan ' + Math.abs(diff) + ' pieza(s).';
        }
    }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Pedido</dt><dd class="font-medium">#{{ $loteEstampado->loteCorte->pedido_id }}</dd></div>
                    <div><dt class="text-gray-500">Operario</dt><dd class="font-medium">{{ auth()->user()->name }}</dd></div>
                    <div><dt class="text-gray-500">Piezas estampadas OK</dt><dd>{{ $loteEstampado->piezas_estampadas }}</dd></div>
                    <div><dt class="text-gray-500">Defectos estampado</dt><dd>{{ $loteEstampado->piezas_con_defecto }}</dd></div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Piezas recibidas para costura</dt>
                        <dd class="text-lg font-bold text-indigo-700">{{ $piezasEsperadas }} unidades</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('operario.costura.store', $loteEstampado) }}" class="space-y-4 border-t pt-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="piezas_cosidas" class="block text-sm font-medium text-gray-700 mb-1">Piezas cosidas *</label>
                            <input type="number" min="0" name="piezas_cosidas" id="piezas_cosidas" x-model="cosidas" required
                                   placeholder="Cantidad"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="piezas_merma" class="block text-sm font-medium text-gray-700 mb-1">Piezas merma costura *</label>
                            <input type="number" min="0" name="piezas_merma" id="piezas_merma" x-model="merma" required
                                   placeholder="Declarar merma"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="rounded-md p-3 text-sm"
                         :class="cuadra ? 'bg-green-50 text-green-800' : (cosidas !== null && merma !== null ? 'bg-red-50 text-red-800' : 'bg-gray-50 text-gray-600')">
                        Total reportado: <strong x-text="total"></strong> / <strong x-text="esperadas"></strong> esperadas
                        <span x-show="mensajeError" x-text="mensajeError" class="block mt-1 font-medium"></span>
                    </div>

                    @error('piezas_cosidas')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit" :disabled="!cuadra"
                            class="inline-flex px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirmar costura
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
