<?php

namespace App\Models;

use App\Enums\OrdenCompraEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    protected $fillable = [
        'insumo_id',
        'proveedor_id',
        'cantidad',
        'estado',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'estado' => OrdenCompraEstado::class,
            'fecha' => 'date',
        ];
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
