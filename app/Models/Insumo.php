<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insumo extends Model
{
    protected $fillable = [
        'nombre',
        'stock_actual',
        'stock_minimo',
        'proveedor_id',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'decimal:2',
            'stock_minimo' => 'decimal:2',
        ];
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }
}
