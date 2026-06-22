<?php

namespace App\Http\Requests;

use App\Models\LoteEstampado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLoteEstampadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(\App\Enums\UserRole::OperarioEstampado, \App\Enums\UserRole::Admin) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'piezas_estampadas' => ['required', 'integer', 'min:1'],
            'piezas_con_defecto' => ['required', 'integer', 'min:0'],
            'fecha' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            /** @var LoteEstampado|null $loteEstampado */
            $loteEstampado = $this->route('loteEstampado');

            if (! $loteEstampado) {
                return;
            }

            if (! $loteEstampado->prueba_aprobada) {
                $validator->errors()->add(
                    'piezas_estampadas',
                    'Debe registrar y aprobar la prueba de impresión antes de procesar el lote completo.'
                );

                return;
            }

            if ($loteEstampado->procesamiento_completado) {
                $validator->errors()->add('piezas_estampadas', 'Este lote ya fue procesado.');

                return;
            }

            $piezasCorte = $loteEstampado->loteCorte->piezas_obtenidas;
            $estampadas = (int) $this->input('piezas_estampadas');
            $defectos = (int) $this->input('piezas_con_defecto');

            if (($estampadas + $defectos) > $piezasCorte) {
                $validator->errors()->add(
                    'piezas_estampadas',
                    "No puede reportar más piezas que las obtenidas en corte ({$piezasCorte} uds.)."
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
            'piezas_estampadas.required' => 'Indique las piezas estampadas correctamente.',
            'piezas_con_defecto.required' => 'Indique las piezas con defecto (puede ser 0).',
        ];
    }
}
