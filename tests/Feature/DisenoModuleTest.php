<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Diseno;
use App\Models\Pedido;
use App\Models\User;
use App\Services\DisenoArchivoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DisenoModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('disenos');
    }

    private function crearPedidoEnDiseno(): Pedido
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente Diseño',
            'tipo_documento' => TipoDocumento::Ruc,
            'numero_documento' => '20101010101',
        ]);

        return Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::Pendiente,
        ]);
    }

    private function pdfDePrueba(): UploadedFile
    {
        return UploadedFile::fake()->create('diseno.pdf', 500, 'application/pdf');
    }

    public function test_disenador_puede_subir_propuesta_pdf(): void
    {
        $disenador = User::factory()->create(['role' => UserRole::Disenador]);
        $pedido = $this->crearPedidoEnDiseno();

        $response = $this->actingAs($disenador)->post(route('disenador.disenos.store', $pedido), [
            'archivo' => $this->pdfDePrueba(),
        ]);

        $response->assertRedirect(route('disenador.disenos.create', $pedido));
        $this->assertDatabaseCount('disenos', 1);
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'estado' => PedidoEstado::EnDiseno->value,
        ]);

        $diseno = Diseno::first();
        $this->assertFalse($diseno->tiene_marca_agua);
        Storage::disk('disenos')->assertExists($diseno->archivo_path);
        Storage::disk('disenos')->assertExists($diseno->archivo_marca_agua_path);
    }

    public function test_rechaza_imagen_con_dpi_insuficiente(): void
    {
        $this->mock(DisenoArchivoService::class, function ($mock): void {
            $mock->shouldReceive('resolverTipo')->andReturn(\App\Enums\TipoArchivoDiseno::Png);
            $mock->shouldReceive('obtenerDpi')->andReturn(72);
        });

        $disenador = User::factory()->create(['role' => UserRole::Disenador]);
        $pedido = $this->crearPedidoEnDiseno();

        $response = $this->actingAs($disenador)->post(route('disenador.disenos.store', $pedido), [
            'archivo' => UploadedFile::fake()->create('baja.png', 100, 'image/png'),
        ]);

        $response->assertSessionHasErrors('archivo');
        $this->assertDatabaseCount('disenos', 0);
    }

    public function test_cliente_puede_aprobar_diseno_y_pedido_pasa_a_produccion(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente Aprobador',
            'tipo_documento' => TipoDocumento::Dni,
            'numero_documento' => '12345678',
        ]);
        $userCliente = User::factory()->create(['role' => UserRole::Cliente]);
        $cliente->update(['user_id' => $userCliente->id]);

        $disenador = User::factory()->create(['role' => UserRole::Disenador]);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::EnDiseno,
        ]);

        $diseno = Diseno::create([
            'pedido_id' => $pedido->id,
            'disenador_id' => $disenador->id,
            'archivo_path' => 'pedido_1/originales/test.png',
            'archivo_marca_agua_path' => 'pedido_1/marca_agua/test.png',
            'tipo_archivo' => 'png',
            'resolucion_dpi' => 300,
            'peso_kb' => 100,
            'tiene_marca_agua' => true,
        ]);

        Storage::disk('disenos')->put($diseno->archivo_path, 'original');
        Storage::disk('disenos')->put($diseno->archivo_marca_agua_path, 'marca');

        $response = $this->actingAs($userCliente)->post(route('cliente.disenos.aprobar', $diseno), [
            'confirmacion' => '1',
        ]);

        $response->assertRedirect(route('cliente.pedidos.disenos.revisar', $pedido));
        $diseno->refresh();
        $pedido->refresh();

        $this->assertTrue($diseno->aprobado);
        $this->assertTrue($diseno->bloqueado);
        $this->assertNotNull($diseno->fecha_aprobacion);
        $this->assertEquals(PedidoEstado::EnProduccion, $pedido->estado);
    }

    public function test_cliente_no_puede_ver_archivo_original(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente',
            'tipo_documento' => TipoDocumento::Dni,
            'numero_documento' => '87654321',
        ]);
        $userCliente = User::factory()->create(['role' => UserRole::Cliente]);
        $cliente->update(['user_id' => $userCliente->id]);

        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad_total' => 12,
            'precio_total' => 420,
            'anticipo' => 210,
            'saldo_pendiente' => 210,
            'estado' => PedidoEstado::EnDiseno,
        ]);

        $diseno = Diseno::create([
            'pedido_id' => $pedido->id,
            'disenador_id' => User::factory()->create(['role' => UserRole::Disenador])->id,
            'archivo_path' => 'pedido_1/originales/test.png',
            'archivo_marca_agua_path' => 'pedido_1/marca_agua/test.png',
            'tipo_archivo' => 'png',
            'peso_kb' => 100,
        ]);

        Storage::disk('disenos')->put($diseno->archivo_path, 'original');
        Storage::disk('disenos')->put($diseno->archivo_marca_agua_path, 'marca');

        $this->actingAs($userCliente)
            ->get(route('disenos.archivo', [$diseno, 'original']))
            ->assertForbidden();

        $this->actingAs($userCliente)
            ->get(route('disenos.archivo', [$diseno, 'marca-agua']))
            ->assertOk();
    }

    public function test_no_se_puede_subir_propuesta_si_hay_diseno_bloqueado(): void
    {
        $disenador = User::factory()->create(['role' => UserRole::Disenador]);
        $pedido = $this->crearPedidoEnDiseno();

        Diseno::create([
            'pedido_id' => $pedido->id,
            'disenador_id' => $disenador->id,
            'archivo_path' => 'pedido_1/originales/a.png',
            'archivo_marca_agua_path' => 'pedido_1/marca_agua/a.png',
            'tipo_archivo' => 'png',
            'peso_kb' => 50,
            'aprobado' => true,
            'bloqueado' => true,
            'fecha_aprobacion' => now(),
        ]);

        $response = $this->actingAs($disenador)->post(route('disenador.disenos.store', $pedido), [
            'archivo' => $this->pdfDePrueba(),
        ]);

        $response->assertSessionHasErrors('archivo');
        $this->assertDatabaseCount('disenos', 1);
    }
}
