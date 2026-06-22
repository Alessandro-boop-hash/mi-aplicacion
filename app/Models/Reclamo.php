<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = ['pedido_id', 'motivo', 'estado'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}