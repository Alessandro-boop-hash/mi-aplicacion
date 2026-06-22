<?php

namespace App\Services;

use App\Enums\TipoArchivoDiseno;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use RuntimeException;

class DisenoArchivoService
{
    private const DISK = 'disenos';

    private const WATERMARK_TEXT = 'PROPUESTA - NO APROBADO';

    /**
     * @return array{
     *     tipo: TipoArchivoDiseno,
     *     archivo_path: string,
     *     archivo_marca_agua_path: string,
     *     resolucion_dpi: ?int,
     *     peso_kb: int,
     *     tiene_marca_agua: bool
     * }
     */
    public function almacenar(UploadedFile $archivo, int $pedidoId): array
    {
        $tipo = $this->resolverTipo($archivo);
        $uuid = Str::uuid()->toString();
        $extension = strtolower($archivo->getClientOriginalExtension());
        $nombreBase = $uuid.'.'.$extension;

        $directorioOriginal = "pedido_{$pedidoId}/originales";
        $directorioMarcaAgua = "pedido_{$pedidoId}/marca_agua";

        $rutaOriginal = $archivo->storeAs($directorioOriginal, $nombreBase, self::DISK);
        $rutaAbsolutaOriginal = Storage::disk(self::DISK)->path($rutaOriginal);

        $resolucionDpi = null;
        $tieneMarcaAgua = false;
        $rutaMarcaAgua = $directorioMarcaAgua.'/'.$nombreBase;

        if (in_array($tipo, [TipoArchivoDiseno::Png, TipoArchivoDiseno::Jpg], true)) {
            $resolucionDpi = $this->obtenerDpi($rutaAbsolutaOriginal);
            $rutaAbsolutaMarcaAgua = Storage::disk(self::DISK)->path($rutaMarcaAgua);
            $this->generarMarcaAguaImagen($rutaAbsolutaOriginal, $rutaAbsolutaMarcaAgua);
            $tieneMarcaAgua = true;
        } else {
            Storage::disk(self::DISK)->copy($rutaOriginal, $rutaMarcaAgua);
        }

        return [
            'tipo' => $tipo,
            'archivo_path' => $rutaOriginal,
            'archivo_marca_agua_path' => $rutaMarcaAgua,
            'resolucion_dpi' => $resolucionDpi,
            'peso_kb' => (int) ceil($archivo->getSize() / 1024),
            'tiene_marca_agua' => $tieneMarcaAgua,
        ];
    }

    public function obtenerDpi(string $rutaAbsoluta): int
    {
        $image = Image::read($rutaAbsoluta);
        $resolution = $image->resolution();

        return (int) round(min($resolution->x(), $resolution->y()));
    }

    public function resolverTipo(UploadedFile $archivo): TipoArchivoDiseno
    {
        $extension = strtolower($archivo->getClientOriginalExtension());

        return match ($extension) {
            'ai' => TipoArchivoDiseno::Ai,
            'png' => TipoArchivoDiseno::Png,
            'pdf' => TipoArchivoDiseno::Pdf,
            'jpg', 'jpeg' => TipoArchivoDiseno::Jpg,
            default => throw new RuntimeException('Formato de archivo no soportado.'),
        };
    }

    private function generarMarcaAguaImagen(string $origen, string $destino): void
    {
        $image = Image::read($origen);
        $ancho = $image->width();
        $alto = $image->height();
        $fontPath = $this->resolverFuente();

        $image->text(self::WATERMARK_TEXT, (int) ($ancho / 2), (int) ($alto / 2), function ($font) use ($fontPath) {
            if ($fontPath !== null) {
                $font->file($fontPath);
            }
            $font->size(max(24, (int) ($ancho / 20)));
            $font->color('rgba(220, 38, 38, 0.55)');
            $font->align('center');
            $font->valign('middle');
            $font->angle(45);
        });

        $image->text(self::WATERMARK_TEXT, (int) ($ancho / 2), (int) ($alto / 3), function ($font) use ($fontPath) {
            if ($fontPath !== null) {
                $font->file($fontPath);
            }
            $font->size(max(18, (int) ($ancho / 28)));
            $font->color('rgba(220, 38, 38, 0.35)');
            $font->align('center');
            $font->valign('middle');
            $font->angle(-30);
        });

        $image->save($destino);
    }

    private function resolverFuente(): ?string
    {
        $candidatos = [
            resource_path('fonts/DejaVuSans.ttf'),
            'C:\\Windows\\Fonts\\arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        ];

        foreach ($candidatos as $ruta) {
            if (is_readable($ruta)) {
                return $ruta;
            }
        }

        return null;
    }
}
