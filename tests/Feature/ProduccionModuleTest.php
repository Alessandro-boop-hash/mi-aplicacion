<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\InventarioTela;
use App\Models\LoteCorte;
use App\Models\LoteEstampado;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduccionModuleTest extends TestCase
{
    use RefreshDatabase;

    private function pedidoEnProduccion(float $stockTela = 100): array
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente Prod',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20999999999',
        ]);

        $tela = InventarioTela::create([
            'tipo_tela' => 'Algodón piqué',
            'color' => 'Blanco',
            'stock_actual_metros' => $stockTela,
            'stock_minimo_metros' => 10,
        ]);

        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 20,
            'precio_total' => 700,
            'anticipo' => 350,
            'saldo_pendiente' => 350,
            'estado' => PedidoEstado::EnProduccion,
            'inventario_tela_id' => $tela->id,
        ]);

        return compact('cliente', 'tela', 'pedido');
    }

    public function test_corte_descuenta_stock_y_registra_merma(): void
    {
        ['tela' => $tela, 'pedido' => $pedido] = $this->pedidoEnProduccion(50);
        $operario = User::factory()->create(['role' => UserRole::OperarioCorte]);

        $response = $this->actingAs($operario)->post(route('operario.corte.store', $pedido), [
            'inventario_tela_id' => $tela->id,
            'metros_tela_usados' => 20,
            'merma_metros' => 2.5,
            'piezas_obtenidas' => 20,
        ]);

        $response->assertRedirect(route('operario.corte.index'));
        $tela->refresh();
        $this->assertEquals(27.5, (float) $tela->stock_actual_metros);
        $this->assertDatabaseHas('lotes_corte', [
            'pedido_id' => $pedido->id,
            'merma_metros' => 2.5,
            'piezas_obtenidas' => 20,
        ]);
    }

    public function test_corte_rechaza_stock_insuficiente(): void
    {
        ['tela' => $tela, 'pedido' => $pedido] = $this->pedidoEnProduccion(5);
        $operario = User::factory()->create(['role' => UserRole::OperarioCorte]);

        $response = $this->actingAs($operario)->post(route('operario.corte.store', $pedido), [
            'inventario_tela_id' => $tela->id,
            'metros_tela_usados' => 4,
            'merma_metros' => 2,
            'piezas_obtenidas' => 20,
        ]);

        $response->assertSessionHasErrors('metros_tela_usados');
        $this->assertDatabaseCount('lotes_corte', 0);
    }

    public function test_estampado_requiere_prueba_antes_de_procesar(): void
    {
        ['pedido' => $pedido] = $this->pedidoEnProduccion();
        $operarioCorte = User::factory()->create(['role' => UserRole::OperarioCorte]);
        $operarioEst = User::factory()->create(['role' => UserRole::OperarioEstampado]);

        $loteCorte = LoteCorte::create([
            'pedido_id' => $pedido->id,
            'inventario_tela_id' => $pedido->inventario_tela_id,
            'operario_id' => $operarioCorte->id,
            'metros_tela_usados' => 10,
            'merma_metros' => 1,
            'piezas_obtenidas' => 20,
            'fecha' => now()->toDateString(),
        ]);

        $loteEstampado = LoteEstampado::create([
            'lote_corte_id' => $loteCorte->id,
            'operario_id' => $operarioEst->id,
            'prueba_aprobada' => false,
            'procesamiento_completado' => false,
            'piezas_estampadas' => 0,
            'piezas_con_defecto' => 0,
            'fecha' => now()->toDateString(),
        ]);

        $this->actingAs($operarioEst)->post(route('operario.estampado.store', $loteEstampado), [
            'piezas_estampadas' => 18,
            'piezas_con_defecto' => 2,
        ])->assertSessionHasErrors('piezas_estampadas');
    }

    public function test_flujo_completo_hasta_costura_actualiza_pedido_a_calidad(): void
    {
        ['pedido' => $pedido] = $this->pedidoEnProduccion();
        $operarioCorte = User::factory()->create(['role' => UserRole::OperarioCorte]);
        $operarioEst = User::factory()->create(['role' => UserRole::OperarioEstampado]);
        $operarioCos = User::factory()->create(['role' => UserRole::OperarioCostura]);

        $loteCorte = LoteCorte::create([
            'pedido_id' => $pedido->id,
            'inventario_tela_id' => $pedido->inventario_tela_id,
            'operario_id' => $operarioCorte->id,
            'metros_tela_usados' => 10,
            'merma_metros' => 0.5,
            'piezas_obtenidas' => 20,
            'fecha' => now()->toDateString(),
        ]);

        $this->actingAs($operarioEst)->post(route('operario.estampado.prueba', $loteCorte), [
            'prueba_aprobada' => '1',
        ]);

        $loteEstampado = $loteCorte->lotesEstampado()->first();

        $this->actingAs($operarioEst)->post(route('operario.estampado.store', $loteEstampado), [
            'piezas_estampadas' => 18,
            'piezas_con_defecto' => 2,
        ]);

        $loteEstampado->refresh();
        $this->assertTrue($loteEstampado->reposicion_solicitada);

        // Piezas recibidas de estampado: 18 estampadas - 2 defecto = 16
        $this->actingAs($operarioCos)->post(route('operario.costura.store', $loteEstampado), [
            'piezas_cosidas' => 14,
            'piezas_merma' => 2,
        ])->assertRedirect(route('operario.costura.index'));

        $pedido->refresh();
        $this->assertEquals(PedidoEstado::EnCalidad, $pedido->estado);
    }

    public function test_costura_rechaza_discrepancia_de_piezas(): void
    {
        ['pedido' => $pedido] = $this->pedidoEnProduccion();
        $operarioCos = User::factory()->create(['role' => UserRole::OperarioCostura]);

        $loteEstampado = LoteEstampado::create([
            'lote_corte_id' => LoteCorte::create([
                'pedido_id' => $pedido->id,
                'inventario_tela_id' => $pedido->inventario_tela_id,
                'operario_id' => User::factory()->create(['role' => UserRole::OperarioCorte])->id,
                'metros_tela_usados' => 5,
                'merma_metros' => 0,
                'piezas_obtenidas' => 20,
                'fecha' => now()->toDateString(),
            ])->id,
            'operario_id' => User::factory()->create(['role' => UserRole::OperarioEstampado])->id,
            'prueba_aprobada' => true,
            'procesamiento_completado' => true,
            'piezas_estampadas' => 18,
            'piezas_con_defecto' => 2,
            'fecha' => now()->toDateString(),
        ]);

        $this->actingAs($operarioCos)->post(route('operario.costura.store', $loteEstampado), [
            'piezas_cosidas' => 15,
            'piezas_merma' => 2,
        ])->assertSessionHasErrors('piezas_cosidas');
    }
}
