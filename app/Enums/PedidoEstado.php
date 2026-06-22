<?php

namespace App\Enums;

enum PedidoEstado: string
{
    case Pendiente = 'pendiente';
    case EnDiseno = 'en_diseno';
    case EnProduccion = 'en_produccion';
    case EnCalidad = 'en_calidad';
    case EnEmpaque = 'en_empaque';
    case Despachado = 'despachado';
    case Entregado = 'entregado';
    case Cancelado = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::EnDiseno => 'En diseño',
            self::EnProduccion => 'En producción',
            self::EnCalidad => 'En calidad',
            self::EnEmpaque => 'En empaque',
            self::Despachado => 'Despachado',
            self::Entregado => 'Entregado',
            self::Cancelado => 'Cancelado',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pendiente => 'bg-yellow-100 text-yellow-800',
            self::EnDiseno => 'bg-blue-100 text-blue-800',
            self::EnProduccion => 'bg-indigo-100 text-indigo-800',
            self::EnCalidad => 'bg-purple-100 text-purple-800',
            self::EnEmpaque => 'bg-cyan-100 text-cyan-800',
            self::Despachado => 'bg-orange-100 text-orange-800',
            self::Entregado => 'bg-green-100 text-green-800',
            self::Cancelado => 'bg-red-100 text-red-800',
        };
    }
}
