<?php

namespace App\Enums;

enum TicketReclamoCalificacion: string
{
    case DefectoFabrica = 'defecto_fabrica';
    case UsoIndebido = 'uso_indebido';
    case Otro = 'otro';
}
