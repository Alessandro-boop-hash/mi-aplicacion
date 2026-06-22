<?php

namespace App\Models;

use App\Enums\TipoArchivoDiseno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diseno extends Model
{
    protected $table = 'disenos';

    protected $fillable = [
        'pedido_id',
        'disenador_id',
        'archivo_path',
        'archivo_marca_agua_path',
        'tipo_archivo',
        'resolucion_dpi',
        'peso_kb',
        'tiene_marca_agua',
        'aprobado',
        'bloqueado',
        'fecha_aprobacion',
    ];

    protected function casts(): array
    {
        return [
            'tipo_archivo' => TipoArchivoDiseno::class,
            'tiene_marca_agua' => 'boolean',
            'aprobado' => 'boolean',
            'bloqueado' => 'boolean',
            'fecha_aprobacion' => 'datetime',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function disenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disenador_id');
    }
}
