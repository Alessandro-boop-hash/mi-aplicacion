<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despacho extends Model
{
    protected $fillable = [
        'pedido_id',
        'fecha',
        'dni_receptor',
        'nombre_receptor',
        'guia_remision_numero',
        'etiqueta_path',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }
}
