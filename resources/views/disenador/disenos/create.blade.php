<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Propuestas — Pedido #{{ $pedido->id }}
            </h2>
            <a href="{{ route('disenador.disenos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                    <div>
                        <dt class="text-gray-500">Cliente</dt>
                        <dd class="font-medium">{{ $pedido->cliente->nombre }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Estado del pedido</dt>
                        <dd>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pedido->estado->badgeClass() }}">
                                {{ $pedido->estado->label() }}
                            </span>
                        </dd>
                    </div>
                </dl>

                @if ($pedido->disenos->where('bloqueado', true)->isEmpty())
                    <form method="POST" action="{{ route('disenador.disenos.store', $pedido) }}" enctype="multipart/form-data" class="space-y-4 border-t pt-6">
                        @csrf
                        <h3 class="text-lg font-semibold text-gray-900">Subir nueva propuesta</h3>

                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700 mb-1">Archivo *</label>
                            <input type="file" name="archivo" id="archivo" required accept=".ai,.png,.pdf,.jpg,.jpeg"
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">
                                Formatos: AI, PNG, PDF, JPG. Máximo 50 MB.
                                PNG/JPG deben tener al menos 300 DPI.
                                AI y PDF: no se valida DPI automáticamente.
                            </p>
                            @error('archivo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentario (opcional)</label>
                            <textarea name="comentario" id="comentario" rows="2"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('comentario') }}</textarea>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Subir propuesta
                        </button>
                    </form>
                @else
                    <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800 border-t pt-6">
                        Este pedido ya tiene un diseño aprobado y bloqueado. No se pueden subir más propuestas.
                    </div>
                @endif
            </div>

            @if ($pedido->disenos->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Propuestas cargadas</h3>
                    <div class="space-y-4">
                        @foreach ($pedido->disenos as $diseno)
                            @include('disenos.partials.card', ['diseno' => $diseno, 'mostrarOriginal' => true])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
