<?php

namespace App\Http\Requests;

use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Pedido::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Reglas base para el proceso de pago y detalles
        $rules = [
            'culqi_token' => 'required|string', 
            'detalles' => 'required|array',
            'monto_pagado' => 'required|numeric'
        ];

        $user = $this->user();

        // Reglas para Vendedor o Admin
        if ($user->hasRole(UserRole::Vendedor, UserRole::Admin)) {
            $rules['modo_cliente'] = ['required', Rule::in(['existente', 'nuevo'])];
            $rules['cliente_id'] = [
                'required_if:modo_cliente,existente',
                'nullable',
                'integer',
                'exists:clientes,id',
            ];
            $rules['cliente_nombre'] = ['required_if:modo_cliente,nuevo', 'nullable', 'string', 'max:255'];
            $rules['cliente_tipo_documento'] = [
                'required_if:modo_cliente,nuevo',
                'nullable',
                Rule::enum(TipoDocumento::class),
            ];
            $rules['cliente_numero_documento'] = [
                'required_if:modo_cliente,nuevo',
                'nullable',
                'string',
                'max:20',
                Rule::unique('clientes', 'numero_documento'),
            ];
            $rules['cliente_email'] = ['nullable', 'email', 'max:255'];
            $rules['cliente_telefono'] = ['nullable', 'string', 'max:20'];
            $rules['cliente_direccion'] = ['nullable', 'string', 'max:255'];
        }

        // Reglas para Cliente nuevo
        if ($user->role === UserRole::Cliente && ! $user->cliente) {
            $rules['cliente_nombre'] = ['required', 'string', 'max:255'];
            $rules['cliente_tipo_documento'] = ['required', Rule::enum(TipoDocumento::class)];
            $rules['cliente_numero_documento'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('clientes', 'numero_documento'),
            ];
            $rules['cliente_email'] = ['nullable', 'email', 'max:255'];
            $rules['cliente_telefono'] = ['nullable', 'string', 'max:20'];
            $rules['cliente_direccion'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $detalles = $this->input('detalles', []);
            $cantidadTotal = (int) collect($detalles)->sum(fn ($detalle) => (int) ($detalle['cantidad'] ?? 0));

            if ($cantidadTotal < 12) {
                $validator->errors()->add(
                    'detalles',
                    'La cantidad total mínima del pedido es de 12 unidades (actual: '.$cantidadTotal.').'
                );
            }

            $precioTotal = round(collect($detalles)->sum(function ($detalle) {
                return ((int) ($detalle['cantidad'] ?? 0)) * ((float) ($detalle['precio_unitario'] ?? 0));
            }), 2);

            $anticipoRequerido = round($precioTotal * 0.5, 2);
            $montoPagado = round((float) $this->input('monto_pagado'), 2);

            if ($montoPagado < $anticipoRequerido) {
                $validator->errors()->add(
                    'monto_pagado',
                    'Debe registrar el pago del anticipo del 50% (S/ '.number_format($anticipoRequerido, 2).' mínimo).'
                );
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'culqi_token.required' => 'El pago es obligatorio para confirmar el pedido.',
            'detalles.required' => 'Debe agregar al menos una línea de detalle.',
            'detalles.*.modelo.required' => 'El modelo es obligatorio en cada línea.',
            'detalles.*.talla.required' => 'La talla es obligatoria en cada línea.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria en cada línea.',
            'detalles.*.precio_unitario.required' => 'El precio unitario es obligatorio en cada línea.',
            'monto_pagado.required' => 'Debe registrar el monto del anticipo pagado.',
            'cliente_id.required_if' => 'Seleccione un cliente existente.',
            'cliente_nombre.required_if' => 'El nombre del cliente es obligatorio.',
            'cliente_numero_documento.unique' => 'Este número de documento ya está registrado.',
        ];
    }
}