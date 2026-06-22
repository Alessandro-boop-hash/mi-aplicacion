<?php

namespace App\Models;

use App\Enums\TicketReclamoCalificacion;
use App\Enums\TicketReclamoEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReclamo extends Model
{
    protected $table = 'tickets_reclamo';

    protected $fillable = [
        'pedido_id',
        'cliente_id',
        'fecha_reclamo',
        'descripcion',
        'calificacion',
        'estado',
        'orden_cambio_generada',
        'costo_cambio',
    ];

    protected function casts(): array
    {
        return [
            'fecha_reclamo' => 'date',
            'calificacion' => TicketReclamoCalificacion::class,
            'estado' => TicketReclamoEstado::class,
            'orden_cambio_generada' => 'boolean',
            'costo_cambio' => 'decimal:2',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
