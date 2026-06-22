<?php

namespace App\Http\Requests;

use App\Models\LoteCorte;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegistrarPruebaEstampadoRequest extends FormRequest
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
            'prueba_aprobada' => ['accepted'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var LoteCorte|null $loteCorte */
            $loteCorte = $this->route('loteCorte');

            if (! $loteCorte) {
                return;
            }

            $loteEstampado = $loteCorte->lotesEstampado()->first();

            if ($loteEstampado && $loteEstampado->procesamiento_completado) {
                $validator->errors()->add('prueba_aprobada', 'Este lote ya fue procesado completamente.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'prueba_aprobada.accepted' => 'Debe aprobar la prueba de impresión para continuar.',
        ];
    }
}
