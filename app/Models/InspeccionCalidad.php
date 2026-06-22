<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspeccionCalidad extends Model
{
    protected $table = 'inspecciones_calidad';

    protected $fillable = [
        'lote_costura_id',
        'supervisor_id',
        'tolerancia_cm',
        'piezas_aprobadas',
        'piezas_rechazadas',
        'firma_digital_path',
        'fecha',
        'aprobado_para_empaque',
    ];

    protected function casts(): array
    {
        return [
            'tolerancia_cm' => 'decimal:2',
            'fecha' => 'date',
            'aprobado_para_empaque' => 'boolean',
        ];
    }

    public function loteCostura(): BelongsTo
    {
        return $this->belongsTo(LoteCostura::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
