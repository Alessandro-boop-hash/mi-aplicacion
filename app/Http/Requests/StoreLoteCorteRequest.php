<?php

namespace App\Http\Requests;

use App\Enums\PedidoEstado;
use App\Models\InventarioTela;
use App\Models\Pedido;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLoteCorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(\App\Enums\UserRole::OperarioCorte, \App\Enums\UserRole::Admin) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'inventario_tela_id' => ['required', 'integer', 'exists:inventario_tela,id'],
            'metros_tela_usados' => ['required', 'numeric', 'min:0.01'],
            'merma_metros' => ['required', 'numeric', 'min:0'],
            'piezas_obtenidas' => ['required', 'integer', 'min:1'],
            'fecha' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            /** @var Pedido|null $pedido */
            $pedido = $this->route('pedido');

            if (! $pedido || $pedido->estado !== PedidoEstado::EnProduccion) {
                $validator->errors()->add('pedido', 'El pedido debe estar en estado en_produccion.');

                return;
            }

            if ($pedido->lotesCorte()->exists()) {
                $validator->errors()->add('pedido', 'Este pedido ya tiene un lote de corte registrado.');

                return;
            }

            $tela = InventarioTela::find($this->input('inventario_tela_id'));

            if (! $tela) {
                return;
            }

            $totalMetros = round(
                (float) $this->input('metros_tela_usados') + (float) $this->input('merma_metros'),
                2
            );

            if ($totalMetros > (float) $tela->stock_actual_metros) {
                $validator->errors()->add(
                    'metros_tela_usados',
                    'Stock insuficiente. Disponible: '.number_format($tela->stock_actual_metros, 2).' m, requerido: '.number_format($totalMetros, 2).' m.'
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
            'inventario_tela_id.required' => 'Debe seleccionar la tela a utilizar.',
            'metros_tela_usados.required' => 'Indique los metros de tela usados.',
            'merma_metros.required' => 'Debe declarar explícitamente la merma en metros (puede ser 0).',
            'piezas_obtenidas.required' => 'Indique las piezas obtenidas del corte.',
        ];
    }
}
