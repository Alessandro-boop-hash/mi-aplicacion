<?php

namespace App\Services;

use Culqi\Culqi;
use Exception;

class PaymentService
{
    /**
     * Procesar un cargo utilizando Culqi.
     *
     * @param float $amount Monto en soles.
     * @param string $email Correo electrónico del usuario.
     * @param string $token Token de Culqi generado en el frontend.
     * @param string $description Descripción del pago.
     * @return bool Retorna true si el pago fue exitoso.
     * @throws Exception Si ocurre un error de comunicación o rechazo.
     */
    public function charge(float $amount, string $email, string $token, string $description = 'Pedido desde Taller Confeccion'): bool
    {
        $apiKey = env('CULQI_SECRET_KEY');

        if (!$apiKey) {
            throw new Exception('La clave secreta de Culqi no está configurada.');
        }

        $culqi = new Culqi(['api_key' => $apiKey]);

        $charge = $culqi->Charges->create([
            'amount' => (int) round($amount * 100, 0),
            'currency_code' => 'PEN',
            'email' => $email,
            'source_id' => $token,
            'description' => $description,
        ]);

        return isset($charge->outcome->type) && $charge->outcome->type === 'venta_exitosa';
    }
}
