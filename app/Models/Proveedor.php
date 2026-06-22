<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
        'email',
    ];

    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class);
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }
}
