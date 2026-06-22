<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoteEstampado extends Model
{
    protected $table = 'lotes_estampado';

    protected $fillable = [
        'lote_corte_id',
        'operario_id',
        'prueba_aprobada',
        'procesamiento_completado',
        'piezas_estampadas',
        'piezas_con_defecto',
        'reposicion_solicitada',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'prueba_aprobada' => 'boolean',
            'procesamiento_completado' => 'boolean',
            'reposicion_solicitada' => 'boolean',
            'fecha' => 'date',
        ];
    }

    public function loteCorte(): BelongsTo
    {
        return $this->belongsTo(LoteCorte::class);
    }

    public function operario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    public function lotesCostura(): HasMany
    {
        return $this->hasMany(LoteCostura::class);
    }
}
