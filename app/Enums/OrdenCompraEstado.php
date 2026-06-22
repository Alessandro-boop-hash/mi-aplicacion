<?php

namespace App\Enums;

enum OrdenCompraEstado: string
{
    case Generada = 'generada';
    case Enviada = 'enviada';
    case Recibida = 'recibida';
}
