<?php

namespace App\Enums;

enum UserRole: string
{
    case Cliente = 'cliente';
    case Vendedor = 'vendedor';
    case Disenador = 'disenador';
    case OperarioCorte = 'operario_corte';
    case OperarioEstampado = 'operario_estampado';
    case OperarioCostura = 'operario_costura';
    case SupervisorCalidad = 'supervisor_calidad';
    case Almacenero = 'almacenero';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Cliente => 'Cliente',
            self::Vendedor => 'Vendedor',
            self::Disenador => 'Diseñador',
            self::OperarioCorte => 'Operario de corte',
            self::OperarioEstampado => 'Operario de estampado',
            self::OperarioCostura => 'Operario de costura',
            self::SupervisorCalidad => 'Supervisor de calidad',
            self::Almacenero => 'Almacenero',
            self::Admin => 'Administrador',
        };
    }

    public function homeRoute(): string
    {
        return match ($this) {
            self::Cliente => 'cliente.inicio',
            self::Vendedor => 'vendedor.pedidos.index',
            self::Disenador => 'disenador.disenos.index',
            self::OperarioCorte => 'operario.corte.index',
            self::OperarioEstampado => 'operario.estampado.index',
            self::OperarioCostura => 'operario.costura.index',
            self::SupervisorCalidad => 'supervisor.calidad.index',
            self::Almacenero => 'almacenero.inventario.index',
            self::Admin => 'admin.dashboard',
        };
    }
}
