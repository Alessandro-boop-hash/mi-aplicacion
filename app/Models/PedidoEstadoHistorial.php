<?php

namespace App\Models;

use App\Enums\PedidoEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoEstadoHistorial extends Model
{
    protected $table = 'pedido_estado_historial';

    protected $fillable = [
        'pedido_id',
        'estado',
        'comentario',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'estado' => PedidoEstado::class,
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
