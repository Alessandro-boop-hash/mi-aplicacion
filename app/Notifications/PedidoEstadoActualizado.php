<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoEstadoActualizado extends Notification
{
    use Queueable;

    public function __construct(
        public Pedido $pedido,
        public string $comentario = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $estadoLabel = $this->pedido->estado->label();
        $comentarioText = $this->comentario ? " ({$this->comentario})" : '';

        return (new MailMessage)
            ->subject("Pedido #{$this->pedido->id} - Estado: {$estadoLabel}")
            ->greeting("¡Hola!")
            ->line("Te informamos que tu pedido #{$this->pedido->id} ha cambiado de estado.")
            ->line("Nuevo estado: **{$estadoLabel}**{$comentarioText}.")
            ->action('Ver detalles del pedido', route('cliente.pedidos.show', $this->pedido))
            ->line('Gracias por confiar en Taller Marte. Si tienes alguna consulta, no dudes en escribirnos.');
    }
}
