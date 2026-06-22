<?php

namespace App\Enums;

enum TicketReclamoEstado: string
{
    case Abierto = 'abierto';
    case EnRevision = 'en_revision';
    case Aprobado = 'aprobado';
    case Rechazado = 'rechazado';
    case Resuelto = 'resuelto';
}
