<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprobarDisenoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $diseno = $this->route('diseno');

        return $diseno && $this->user()?->can('approve', $diseno);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'confirmacion' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'confirmacion.accepted' => 'Debe marcar la casilla de aprobación para confirmar el diseño.',
        ];
    }
}
