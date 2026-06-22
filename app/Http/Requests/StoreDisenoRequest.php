<?php

namespace App\Http\Requests;

use App\Enums\TipoArchivoDiseno;
use App\Models\Pedido;
use App\Services\DisenoArchivoService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreDisenoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Diseno::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'archivo' => [
                'required',
                'file',
                'max:51200',
                Rule::file()->extensions(['ai', 'png', 'pdf', 'jpg', 'jpeg']),
            ],
            'comentario' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty() || ! $this->hasFile('archivo')) {
                return;
            }

            $archivo = $this->file('archivo');
            $service = app(DisenoArchivoService::class);

            try {
                $tipo = $service->resolverTipo($archivo);
            } catch (\RuntimeException) {
                $validator->errors()->add('archivo', 'Formato no permitido. Use AI, PNG, PDF o JPG.');

                return;
            }

            if (in_array($tipo, [TipoArchivoDiseno::Png, TipoArchivoDiseno::Jpg], true)) {
                $dpi = $service->obtenerDpi($archivo->getRealPath());

                if ($dpi < 300) {
                    $validator->errors()->add(
                        'archivo',
                        "La imagen debe tener al menos 300 DPI (detectado: {$dpi} DPI)."
                    );
                }
            }

            /** @var Pedido|null $pedido */
            $pedido = $this->route('pedido');

            if ($pedido && $pedido->disenos()->where('bloqueado', true)->exists()) {
                $validator->errors()->add(
                    'archivo',
                    'Este pedido ya tiene un diseño aprobado y bloqueado. No se pueden subir nuevas propuestas.'
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
            'archivo.required' => 'Debe seleccionar un archivo de diseño.',
            'archivo.max' => 'El archivo no puede superar los 50 MB.',
            'archivo.extensions' => 'Formatos permitidos: AI, PNG, PDF, JPG.',
        ];
    }
}
