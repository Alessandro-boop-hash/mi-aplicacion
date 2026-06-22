<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoteCorte extends Model
{
    protected $table = 'lotes_corte';

    protected $fillable = [
        'pedido_id',
        'inventario_tela_id',
        'operario_id',
        'metros_tela_usados',
        'merma_metros',
        'piezas_obtenidas',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'metros_tela_usados' => 'decimal:2',
            'merma_metros' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function inventarioTela(): BelongsTo
    {
        return $this->belongsTo(InventarioTela::class);
    }

    public function operario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    public function lotesEstampado(): HasMany
    {
        return $this->hasMany(LoteEstampado::class);
    }
}
