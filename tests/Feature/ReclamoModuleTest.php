<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoEstadoHistorial;
use App\Models\Reclamo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReclamoModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $userCliente;
    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userCliente = User::factory()->create(['role' => UserRole::Cliente]);
        $this->cliente = Cliente::create([
            'user_id' => $this->userCliente->id,
            'nombre' => 'Cliente Reclamos SAC',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20112233445',
        ]);
    }

    private function crearPedido(PedidoEstado $estado): Pedido
    {
        return Pedido::create([
            'cliente_id' => $this->cliente->id,
            'fecha' => now()->subDays(10)->toDateString(),
            'cantidad_total' => 20,
            'precio_total' => 500,
            'anticipo' => 250,
            'saldo_pendiente' => 250,
            'estado' => $estado,
        ]);
    }

    public function test_cliente_can_view_own_reclamos_and_only_delivered_pedidos_in_dropdown(): void
    {
        $pedidoEntregado = $this->crearPedido(PedidoEstado::Entregado);
        $pedidoProduccion = $this->crearPedido(PedidoEstado::EnProduccion);

        $response = $this->actingAs($this->userCliente)->get(route('cliente.reclamos.index'));

        $response->assertOk();
        $response->assertViewHas('pedidosEntregados', function ($pedidos) use ($pedidoEntregado, $pedidoProduccion) {
            return $pedidos->contains($pedidoEntregado) && !$pedidos->contains($pedidoProduccion);
        });
    }

    public function test_cliente_can_register_reclamo_within_seven_days_of_delivery(): void
    {
        $pedido = $this->crearPedido(PedidoEstado::Entregado);

        // Simular historial de estado: entregado hace 3 días usando DB para evitar sobrescritura de timestamps de Eloquent
        \Illuminate\Support\Facades\DB::table('pedido_estado_historial')->insert([
            'pedido_id' => $pedido->id,
            'estado' => PedidoEstado::Entregado->value,
            'user_id' => $this->userCliente->id,
            'comentario' => 'Pedido entregado.',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        $response = $this->actingAs($this->userCliente)->post(route('cliente.reclamos.store'), [
            'pedido_id' => $pedido->id,
            'motivo' => 'Prendas con hilos sueltos y costura débil.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reclamos', [
            'pedido_id' => $pedido->id,
            'motivo' => 'Prendas con hilos sueltos y costura débil.',
            'estado' => 'En revisión',
        ]);
    }

    public function test_cliente_cannot_register_reclamo_after_seven_days_of_delivery(): void
    {
        $pedido = $this->crearPedido(PedidoEstado::Entregado);

        // Simular historial de estado: entregado hace 10 días usando DB para evitar sobrescritura de timestamps de Eloquent
        \Illuminate\Support\Facades\DB::table('pedido_estado_historial')->insert([
            'pedido_id' => $pedido->id,
            'estado' => PedidoEstado::Entregado->value,
            'user_id' => $this->userCliente->id,
            'comentario' => 'Pedido entregado.',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($this->userCliente)->post(route('cliente.reclamos.store'), [
            'pedido_id' => $pedido->id,
            'motivo' => 'Reclamo tardío.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El plazo máximo de 7 días desde la entrega ha expirado para registrar un reclamo sobre este pedido.');
        $this->assertDatabaseCount('reclamos', 0);
    }

    public function test_cliente_cannot_register_reclamo_for_non_delivered_pedido(): void
    {
        $pedido = $this->crearPedido(PedidoEstado::EnProduccion);

        $response = $this->actingAs($this->userCliente)->post(route('cliente.reclamos.store'), [
            'pedido_id' => $pedido->id,
            'motivo' => 'Intento de reclamo en producción.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Solo se pueden registrar reclamos para pedidos entregados.');
        $this->assertDatabaseCount('reclamos', 0);
    }
}
