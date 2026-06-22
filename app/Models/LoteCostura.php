<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoteCostura extends Model
{
    protected $table = 'lotes_costura';

    protected $fillable = [
        'lote_estampado_id',
        'operario_id',
        'piezas_cosidas',
        'piezas_merma',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function loteEstampado(): BelongsTo
    {
        return $this->belongsTo(LoteEstampado::class);
    }

    public function operario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    public function inspeccionesCalidad(): HasMany
    {
        return $this->hasMany(InspeccionCalidad::class);
    }

    public function piezasRecibidasDeEstampado(): int
    {
        $lote = $this->loteEstampado;

        return $lote->piezas_estampadas - $lote->piezas_con_defecto;
    }

    public function validarTotalesPiezas(): bool
    {
        return ($this->piezas_cosidas + $this->piezas_merma) === $this->piezasRecibidasDeEstampado();
    }
}
