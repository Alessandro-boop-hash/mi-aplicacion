<?php

namespace App\Models;

use App\Enums\PedidoEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_id',
        'fecha',
        'cantidad_total',
        'precio_total',
        'anticipo',
        'saldo_pendiente',
        'estado',
        'inventario_tela_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'precio_total' => 'decimal:2',
            'anticipo' => 'decimal:2',
            'saldo_pendiente' => 'decimal:2',
            'estado' => PedidoEstado::class,
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function inventarioTela(): BelongsTo
    {
        return $this->belongsTo(InventarioTela::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function disenos(): HasMany
    {
        return $this->hasMany(Diseno::class);
    }

    public function lotesCorte(): HasMany
    {
        return $this->hasMany(LoteCorte::class);
    }

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class);
    }

    public function ticketsReclamo(): HasMany
    {
        return $this->hasMany(TicketReclamo::class);
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(PedidoEstadoHistorial::class)->orderBy('created_at');
    }
}
