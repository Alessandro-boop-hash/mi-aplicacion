<?php

namespace App\Http\Requests;

use App\Models\LoteEstampado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLoteCosturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(\App\Enums\UserRole::OperarioCostura, \App\Enums\UserRole::Admin) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'piezas_cosidas' => ['required', 'integer', 'min:0'],
            'piezas_merma' => ['required', 'integer', 'min:0'],
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

            if (! $loteEstampado || ! $loteEstampado->procesamiento_completado) {
                $validator->errors()->add('piezas_cosidas', 'El lote de estampado debe estar completado antes de registrar costura.');

                return;
            }

            if ($loteEstampado->lotesCostura()->exists()) {
                $validator->errors()->add('piezas_cosidas', 'Este lote ya tiene un registro de costura.');

                return;
            }

            $esperadas = $loteEstampado->piezas_estampadas - $loteEstampado->piezas_con_defecto;
            $reportadas = (int) $this->input('piezas_cosidas') + (int) $this->input('piezas_merma');

            if ($reportadas !== $esperadas) {
                $diferencia = $reportadas - $esperadas;
                $mensaje = $diferencia > 0
                    ? "Sobran {$diferencia} pieza(s)."
                    : 'Faltan '.abs($diferencia).' pieza(s).';

                $validator->errors()->add(
                    'piezas_cosidas',
                    "El total (cosidas + merma = {$reportadas}) no coincide con las piezas recibidas de estampado ({$esperadas}). {$mensaje}"
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
            'piezas_cosidas.required' => 'Indique las piezas cosidas.',
            'piezas_merma.required' => 'Indique las piezas de merma en costura (puede ser 0).',
        ];
    }
}
