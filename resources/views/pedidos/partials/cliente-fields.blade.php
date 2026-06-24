<div>
    <label for="cliente_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre / Razón social *</label>
    <input type="text" name="cliente_nombre" id="cliente_nombre" value="{{ old('cliente_nombre') }}" x-model="clienteNombre"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>
<div>
    <label for="cliente_tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">Tipo documento *</label>
    <select name="cliente_tipo_documento" id="cliente_tipo_documento"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach ($tiposDocumento as $tipo)
            <option value="{{ $tipo->value }}" @selected(old('cliente_tipo_documento') === $tipo->value)>
                {{ strtoupper($tipo->value) }}
            </option>
        @endforeach
    </select>
</div>
<div>
    <label for="cliente_numero_documento" class="block text-sm font-medium text-gray-700 mb-1">N.º documento *</label>
    <input type="text" name="cliente_numero_documento" id="cliente_numero_documento" value="{{ old('cliente_numero_documento') }}" x-model="clienteNumeroDocumento"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>
<div>
    <label for="cliente_email" class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
    <input type="email" name="cliente_email" id="cliente_email" value="{{ old('cliente_email') }}"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>
<div>
    <label for="cliente_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
    <input type="text" name="cliente_telefono" id="cliente_telefono" value="{{ old('cliente_telefono') }}"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>
<div class="sm:col-span-2">
    <label for="cliente_direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
    <input type="text" name="cliente_direccion" id="cliente_direccion" value="{{ old('cliente_direccion') }}"
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>
