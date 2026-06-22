<div class="border border-gray-200 rounded-lg p-4 {{ $diseno->bloqueado ? 'bg-gray-50 opacity-90' : '' }}">
    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="text-sm font-semibold text-gray-900">Propuesta #{{ $diseno->id }}</span>
                <span class="text-xs uppercase text-gray-500">{{ strtoupper($diseno->tipo_archivo->value) }}</span>
                @if ($diseno->aprobado)
                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aprobado</span>
                @endif
                @if ($diseno->bloqueado)
                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">Bloqueado</span>
                @endif
            </div>

            <p class="text-xs text-gray-500 mb-3">
                Subido el {{ $diseno->created_at->format('d/m/Y H:i') }}
                @if ($diseno->disenador)
                    por {{ $diseno->disenador->name }}
                @endif
                @if ($diseno->resolucion_dpi)
                    · {{ $diseno->resolucion_dpi }} DPI
                @endif
                · {{ number_format($diseno->peso_kb) }} KB
            </p>

            @if (in_array($diseno->tipo_archivo->value, ['png', 'jpg'], true))
                <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-100 max-w-md">
                    <img src="{{ route('disenos.archivo', [$diseno, 'marca-agua']) }}"
                         alt="Vista previa diseño #{{ $diseno->id }}"
                         class="w-full h-auto">
                </div>
            @else
                <div class="rounded-lg border-2 border-dashed border-red-300 bg-red-50 p-4 max-w-md">
                    <p class="text-sm font-semibold text-red-700 uppercase tracking-wide">Propuesta — No aprobado</p>
                    <p class="text-xs text-red-600 mt-1">
                        Archivo {{ strtoupper($diseno->tipo_archivo->value) }}.
                        @if (! $diseno->tiene_marca_agua)
                            La marca de agua embebida no aplica a este formato; use la vista protegida al descargar.
                        @endif
                    </p>
                    <a href="{{ route('disenos.archivo', [$diseno, 'marca-agua']) }}"
                       class="inline-block mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                       target="_blank" rel="noopener">
                        Ver / descargar versión para cliente
                    </a>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-2 shrink-0">
            @if ($mostrarOriginal ?? false)
                <a href="{{ route('disenos.archivo', [$diseno, 'original']) }}"
                   class="inline-flex items-center justify-center px-3 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700"
                   target="_blank" rel="noopener">
                    Original (sin marca)
                </a>
            @endif

            @if ($mostrarAprobacion ?? false)
                @if ($diseno->bloqueado)
                    <p class="text-xs text-gray-500 italic">Diseño bloqueado — no editable</p>
                @else
                    <form method="POST" action="{{ route('cliente.disenos.aprobar', $diseno) }}" class="space-y-2">
                        @csrf
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="confirmacion" value="1"
                                   class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   {{ old('confirmacion') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Apruebo esta propuesta de diseño</span>
                        </label>
                        @error('confirmacion')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-green-700">
                            Confirmar aprobación
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
