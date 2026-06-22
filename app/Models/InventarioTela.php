<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioTela extends Model
{
    protected $table = 'inventario_tela';

    protected $fillable = [
        'tipo_tela',
        'color',
        'stock_actual_metros',
        'stock_minimo_metros',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual_metros' => 'decimal:2',
            'stock_minimo_metros' => 'decimal:2',
        ];
    }

    public function pedidos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function lotesCorte(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LoteCorte::class);
    }
}
