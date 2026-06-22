<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Revisar diseños — Pedido #{{ $pedido->id }}
            </h2>
            <a href="{{ route('cliente.pedidos.show', $pedido) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Volver al pedido
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

            <div class="rounded-md bg-amber-50 border border-amber-200 p-4 text-sm text-amber-900">
                Las propuestas se muestran con marca de agua <strong>«PROPUESTA - NO APROBADO»</strong>.
                Al aprobar una propuesta, quedará bloqueada y el pedido pasará a producción.
            </div>

            @if ($pedido->disenos->where('bloqueado', true)->isNotEmpty())
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                    Ya existe un diseño aprobado para este pedido. Las demás propuestas permanecen solo como referencia.
                </div>
            @endif

            <div class="space-y-4">
                @foreach ($pedido->disenos as $diseno)
                    @include('disenos.partials.card', [
                        'diseno' => $diseno,
                        'mostrarOriginal' => false,
                        'mostrarAprobacion' => ! $pedido->disenos->where('bloqueado', true)->isNotEmpty(),
                    ])
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
