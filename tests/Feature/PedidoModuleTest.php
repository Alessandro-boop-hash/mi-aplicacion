<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoModuleTest extends TestCase
{
    use RefreshDatabase;

    private function detallesValidos(): array
    {
        return [
            ['modelo' => 'Polo corporativo', 'talla' => 'M', 'cantidad' => 8, 'precio_unitario' => 35.00],
            ['modelo' => 'Polo corporativo', 'talla' => 'L', 'cantidad' => 4, 'precio_unitario' => 35.00],
        ];
    }

    public function test_vendedor_can_create_pedido_with_minimum_quantity_and_advance(): void
    {
        $vendedor = User::factory()->create(['role' => UserRole::Vendedor]);
        $cliente = Cliente::create([
            'nombre' => 'Cliente Test SAC',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20999888777',
        ]);

        $this->mock(\App\Services\PaymentService::class, function ($mock) {
            $mock->shouldReceive('charge')
                ->once()
                ->andReturn(true);
        });

        $response = $this->actingAs($vendedor)->post(route('vendedor.pedidos.store'), [
            'modo_cliente' => 'existente',
            'cliente_id' => $cliente->id,
            'detalles' => $this->detallesValidos(),
            'monto_pagado' => 210.00,
            'culqi_token' => 'tok_test_123',
        ]);

        $pedido = Pedido::first();

        $response->assertRedirect(route('vendedor.pedidos.show', $pedido));
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'cliente_id' => $cliente->id,
            'cantidad_total' => 12,
            'precio_total' => '420.00',
            'anticipo' => '210.00',
            'estado' => PedidoEstado::Pendiente->value,
        ]);
        $this->assertDatabaseCount('detalle_pedido', 2);
        $this->assertDatabaseHas('pedido_estado_historial', [
            'pedido_id' => $pedido->id,
            'estado' => PedidoEstado::Pendiente->value,
        ]);
    }

    public function test_store_rejects_order_with_less_than_twelve_units(): void
    {
        $vendedor = User::factory()->create(['role' => UserRole::Vendedor]);
        $cliente = Cliente::create([
            'nombre' => 'Cliente Test SAC',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20999888776',
        ]);

        $response = $this->actingAs($vendedor)->post(route('vendedor.pedidos.store'), [
            'modo_cliente' => 'existente',
            'cliente_id' => $cliente->id,
            'detalles' => [
                ['modelo' => 'Polo', 'talla' => 'M', 'cantidad' => 5, 'precio_unitario' => 30.00],
            ],
            'monto_pagado' => 75.00,
            'culqi_token' => 'tok_test_123',
        ]);

        $response->assertSessionHasErrors('detalles');
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_store_rejects_insufficient_advance_payment(): void
    {
        $vendedor = User::factory()->create(['role' => UserRole::Vendedor]);
        $cliente = Cliente::create([
            'nombre' => 'Cliente Test SAC',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20999888775',
        ]);

        $response = $this->actingAs($vendedor)->post(route('vendedor.pedidos.store'), [
            'modo_cliente' => 'existente',
            'cliente_id' => $cliente->id,
            'detalles' => $this->detallesValidos(),
            'monto_pagado' => 100.00,
            'culqi_token' => 'tok_test_123',
        ]);

        $response->assertSessionHasErrors('monto_pagado');
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_cliente_cannot_view_other_client_orders(): void
    {
        $clienteA = Cliente::create([
            'nombre' => 'Cliente A',
            'tipo_documento' => TipoDocumento::Dni,
            'numero_documento' => '11111111',
        ]);
        $clienteB = Cliente::create([
            'nombre' => 'Cliente B',
            'tipo_documento' => TipoDocumento::Dni,
            'numero_documento' => '22222222',
        ]);

        $userA = User::factory()->create(['role' => UserRole::Cliente]);
        $clienteA->update(['user_id' => $userA->id]);

        $pedidoB = Pedido::create([
            'cliente_id' => $clienteB->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::Pendiente,
        ]);

        $this->actingAs($userA)
            ->get(route('cliente.pedidos.show', $pedidoB))
            ->assertForbidden();
    }

    public function test_pedido_index_filters_by_estado_and_search(): void
    {
        $vendedor = User::factory()->create(['role' => UserRole::Vendedor]);
        $cliente = Cliente::create([
            'nombre' => 'Moda Express',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20111222333',
        ]);

        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::EnDiseno,
        ]);

        $this->actingAs($vendedor)
            ->get(route('vendedor.pedidos.index', ['estado' => PedidoEstado::EnDiseno->value, 'buscar' => 'Moda']))
            ->assertOk()
            ->assertSee('#'.$pedido->id)
            ->assertSee('Moda Express');
    }

    public function test_can_download_pedido_pdf(): void
    {
        $vendedor = User::factory()->create(['role' => UserRole::Vendedor]);
        $cliente = Cliente::create([
            'nombre' => 'Moda Express',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20111222333',
        ]);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::EnDiseno,
        ]);

        $response = $this->actingAs($vendedor)->get(route('pedidos.pdf', $pedido));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_notification_sent_on_pedido_status_history_created(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $clienteUser = User::factory()->create(['role' => UserRole::Cliente]);
        $cliente = Cliente::create([
            'user_id' => $clienteUser->id,
            'nombre' => 'Moda Express',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20111222333',
            'email' => 'client@test.com',
        ]);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::EnDiseno,
        ]);

        \App\Models\PedidoEstadoHistorial::create([
            'pedido_id' => $pedido->id,
            'estado' => PedidoEstado::EnProduccion,
            'comentario' => 'Lote iniciado.',
            'user_id' => $clienteUser->id,
        ]);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $clienteUser,
            \App\Notifications\PedidoEstadoActualizado::class,
            function ($notification) use ($pedido) {
                return $notification->pedido->id === $pedido->id;
            }
        );
    }
}
