<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar pedido</h2>
            <a href="{{ route($routePrefix.'.pedidos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="pedidoStepper(@js([
        'isVendedor' => $isVendedor,
        'hasCliente' => (bool) $clienteActual,
        'modoCliente' => old('modo_cliente', 'existente'),
        'clienteId' => old('cliente_id', ''),
        'clienteNombre' => old('cliente_nombre', ''),
        'clienteNumeroDocumento' => old('cliente_numero_documento', ''),
        'oldDetalles' => old('detalles', [['modelo' => '', 'talla' => '', 'cantidad' => 1, 'precio_unitario' => '']]),
        'oldMontoPagado' => old('monto_pagado', ''),
        'userEmail' => auth()->user()->email,
        'hasErrors' => $errors->any(),
    ]))">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Stepper header --}}
            <nav class="mb-8">
                <ol class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-0">
                    <template x-for="(label, index) in ['Cliente', 'Detalle', 'Pago']" :key="index">
                        <li class="flex items-center flex-1">
                            <div class="flex items-center gap-3 w-full">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold"
                                      :class="step === index + 1 ? 'bg-indigo-600 text-white' : (step > index + 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600')"
                                      x-text="index + 1"></span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900" x-text="label"></p>
                                    <p class="text-xs text-gray-500 hidden sm:block"
                                       x-text="index === 0 ? 'Datos del cliente' : (index === 1 ? 'Modelos y tallas' : 'Anticipo 50%')"></p>
                                </div>
                            </div>
                            <div class="hidden sm:block flex-1 h-0.5 mx-4 last:hidden"
                                 :class="step > index + 1 ? 'bg-green-500' : 'bg-gray-200'"
                                 x-show="index < 2"></div>
                        </li>
                    </template>
                </ol>
            </nav>

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route($routePrefix.'.pedidos.store') }}" @submit="beforeSubmit">
                @csrf
                <input type="hidden" name="culqi_token" id="culqi_token">

                {{-- Paso 1: Cliente --}}
                <div x-show="step === 1" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Paso 1 — Datos del cliente</h3>

                    @if ($isVendedor)
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="modo_cliente" value="existente" x-model="modoCliente" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Cliente existente</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="modo_cliente" value="nuevo" x-model="modoCliente" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Nuevo cliente</span>
                            </label>
                        </div>

                        <div x-show="modoCliente === 'existente'" x-cloak>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar cliente</label>
                            <select name="cliente_id" id="cliente_id" x-model="clienteId"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">— Seleccione —</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                                        {{ $cliente->nombre }} ({{ strtoupper($cliente->tipo_documento->value) }}: {{ $cliente->numero_documento }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="modoCliente === 'nuevo'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('pedidos.partials.cliente-fields')
                        </div>
                    @else
                        @if ($clienteActual)
                            <div class="rounded-md bg-gray-50 p-4 text-sm">
                                <p class="font-medium text-gray-900">{{ $clienteActual->nombre }}</p>
                                <p class="text-gray-600">{{ strtoupper($clienteActual->tipo_documento->value) }}: {{ $clienteActual->numero_documento }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mb-2">Complete sus datos de cliente para continuar:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @include('pedidos.partials.cliente-fields')
                            </div>
                        @endif
                    @endif

                    <div class="flex justify-end pt-4">
                        <button type="button" @click="nextStep()"
                                :disabled="!step1Valid()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Continuar
                        </button>
                    </div>
                </div>

                {{-- Paso 2: Detalle --}}
                <div x-show="step === 2" x-cloak class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Paso 2 — Detalle del pedido</h3>
                        <button type="button" @click="addLinea()"
                                class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                            + Agregar línea
                        </button>
                    </div>

                    <div class="rounded-md p-3 text-sm"
                         :class="cantidadValida ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'">
                        <strong x-text="cantidadTotal + ' unidades'"></strong>
                        <span x-show="!cantidadValida"> — Mínimo requerido: 12 unidades</span>
                        <span x-show="cantidadValida"> — Cantidad mínima cumplida</span>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(linea, index) in detalles" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                                <div class="lg:col-span-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Modelo</label>
                                    <input type="text" :name="'detalles[' + index + '][modelo]'" x-model="linea.modelo" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Talla</label>
                                    <input type="text" :name="'detalles[' + index + '][talla]'" x-model="linea.talla" required placeholder="M"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
                                    <input type="number" min="1" :name="'detalles[' + index + '][cantidad]'" x-model.number="linea.cantidad" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Precio unit. (S/)</label>
                                    <input type="number" min="0.01" step="0.01" :name="'detalles[' + index + '][precio_unitario]'" x-model.number="linea.precio_unitario" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="lg:col-span-2 flex items-end justify-between gap-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Subtotal</p>
                                        <p class="text-sm font-semibold text-gray-900" x-text="'S/ ' + lineSubtotal(linea).toFixed(2)"></p>
                                    </div>
                                    <button type="button" @click="removeLinea(index)" x-show="detalles.length > 1"
                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                        Quitar
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-gray-200">
                        <div class="text-sm">
                            <span class="text-gray-600">Total del pedido:</span>
                            <strong class="text-lg text-gray-900" x-text="'S/ ' + precioTotal.toFixed(2)"></strong>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="step = 1"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                Atrás
                            </button>
                            <button type="button" @click="nextStep()"
                                    :disabled="!step2Valid()"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Continuar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Paso 3: Resumen y pago --}}
                <div x-show="step === 3" x-cloak class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900">Paso 3 — Resumen y anticipo</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-lg bg-gray-50 p-4 text-sm">
                        <div>
                            <p class="text-gray-500">Cantidad total</p>
                            <p class="font-semibold text-gray-900" x-text="cantidadTotal + ' unidades'"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Precio total</p>
                            <p class="font-semibold text-gray-900" x-text="'S/ ' + precioTotal.toFixed(2)"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Anticipo obligatorio (50%)</p>
                            <p class="font-semibold text-indigo-700" x-text="'S/ ' + anticipoRequerido.toFixed(2)"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Saldo pendiente estimado</p>
                            <p class="font-semibold text-gray-900" x-text="'S/ ' + saldoEstimado.toFixed(2)"></p>
                        </div>
                    </div>

                    <div>
                        <label for="monto_pagado" class="block text-sm font-medium text-gray-700 mb-1">
                            Monto pagado (anticipo) *
                        </label>
                        <input type="number" name="monto_pagado" id="monto_pagado" min="0.01" step="0.01"
                               x-model.number="montoPagado" required
                               class="w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-2 text-sm"
                           :class="anticipoValido ? 'text-green-700' : 'text-red-700'">
                            <span x-show="!anticipoValido">
                                Debe registrar al menos S/ <span x-text="anticipoRequerido.toFixed(2)"></span> para confirmar el pedido.
                            </span>
                            <span x-show="anticipoValido">Anticipo registrado correctamente.</span>
                        </p>
                    </div>

                    <input type="hidden" name="fecha" value="{{ old('fecha', now()->toDateString()) }}">

                    <div class="flex gap-2 justify-end pt-4 border-t border-gray-200">
                        <button type="button" @click="step = 2"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                            Atrás
                        </button>
                        <button type="submit"
                                :disabled="!canSubmit()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Confirmar pedido
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://checkout.culqi.com/js/v4"></script>
        <script>
            Culqi.publicKey = '{{ env('CULQI_PUBLIC_KEY') }}';
        </script>
    @endpush
</x-app-layout>
