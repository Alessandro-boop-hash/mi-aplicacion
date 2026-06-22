<?php

namespace Database\Seeders;

use App\Enums\OrdenCompraEstado;
use App\Enums\PedidoEstado;
use App\Enums\TicketReclamoCalificacion;
use App\Enums\TicketReclamoEstado;
use App\Enums\TipoArchivoDiseno;
use App\Enums\TipoDocumento;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Despacho;
use App\Models\DetallePedido;
use App\Models\Diseno;
use App\Models\InspeccionCalidad;
use App\Models\Insumo;
use App\Models\InventarioTela;
use App\Models\LoteCostura;
use App\Models\LoteCorte;
use App\Models\LoteEstampado;
use App\Models\OrdenCompra;
use App\Models\Pedido;
use App\Models\Proveedor;
use App\Models\TicketReclamo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TallerConfeccionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'admin@tallerconfeccion.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);

        $vendedor = User::create([
            'name' => 'María López',
            'email' => 'vendedor@tallerconfeccion.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Vendedor,
        ]);

        $disenador = User::create([
            'name' => 'Ana Torres',
            'email' => 'disenador@tallerconfeccion.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Disenador,
        ]);

        $operarioCorte = User::create([
            'name' => 'Luis Ramírez',
            'email' => 'corte@tallerconfeccion.test',
            'password' => Hash::make('password'),
            'role' => UserRole::OperarioCorte,
        ]);

        $supervisor = User::create([
            'name' => 'Patricia Vega',
            'email' => 'calidad@tallerconfeccion.test',
            'password' => Hash::make('password'),
            'role' => UserRole::SupervisorCalidad,
        ]);

        $clientesData = [
            [
                'user_id' => null,
                'nombre' => 'Distribuidora Norte SAC',
                'tipo_documento' => TipoDocumento::Ruc,
                'numero_documento' => '20123456789',
                'email' => 'contacto@distribuidoranorte.pe',
                'telefono' => '014567890',
                'direccion' => 'Av. Industrial 120, Lima',
            ],
            [
                'user_id' => null,
                'nombre' => 'Sport Wear Perú EIRL',
                'tipo_documento' => TipoDocumento::Ruc,
                'numero_documento' => '20567890123',
                'email' => 'pedidos@sportwear.pe',
                'telefono' => '016789012',
                'direccion' => 'Jr. Comercio 45, Callao',
            ],
            [
                'user_id' => null,
                'nombre' => 'Juan Pérez',
                'tipo_documento' => TipoDocumento::Dni,
                'numero_documento' => '45678901',
                'email' => 'juan.perez@email.com',
                'telefono' => '987654321',
                'direccion' => 'Calle Los Pinos 88, Surco',
            ],
            [
                'user_id' => null,
                'nombre' => 'Moda Urbana SAC',
                'tipo_documento' => TipoDocumento::Ruc,
                'numero_documento' => '20456789012',
                'email' => 'compras@modaurbana.pe',
                'telefono' => '015432109',
                'direccion' => 'Av. La Marina 300, San Miguel',
            ],
            [
                'user_id' => null,
                'nombre' => 'Rosa Quispe',
                'tipo_documento' => TipoDocumento::Dni,
                'numero_documento' => '72345678',
                'email' => 'rosa.quispe@email.com',
                'telefono' => '912345678',
                'direccion' => 'Urb. Las Flores Mz. B Lt. 12, Ate',
            ],
        ];

        $clientes = collect($clientesData)->map(fn (array $data) => Cliente::create($data));

        InventarioTela::insert([
            [
                'tipo_tela' => 'Algodón piqué',
                'color' => 'Blanco',
                'stock_actual_metros' => 850.50,
                'stock_minimo_metros' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_tela' => 'Poliéster dry-fit',
                'color' => 'Negro',
                'stock_actual_metros' => 420.00,
                'stock_minimo_metros' => 150.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_tela' => 'Franela 20/1',
                'color' => 'Gris melange',
                'stock_actual_metros' => 180.75,
                'stock_minimo_metros' => 250.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $proveedor = Proveedor::create([
            'nombre' => 'Textiles Andinos SAC',
            'contacto' => 'Pedro Gutiérrez',
            'telefono' => '014321876',
            'email' => 'ventas@textilesandinos.pe',
        ]);

        $insumo = Insumo::create([
            'nombre' => 'Hilo poliéster 120/2',
            'stock_actual' => 45.00,
            'stock_minimo' => 20.00,
            'proveedor_id' => $proveedor->id,
        ]);

        OrdenCompra::create([
            'insumo_id' => $insumo->id,
            'proveedor_id' => $proveedor->id,
            'cantidad' => 30.00,
            'estado' => OrdenCompraEstado::Enviada,
            'fecha' => now()->subDays(3)->toDateString(),
        ]);

        $estadosPedido = [
            PedidoEstado::Pendiente,
            PedidoEstado::EnDiseno,
            PedidoEstado::EnProduccion,
            PedidoEstado::EnCalidad,
            PedidoEstado::EnEmpaque,
            PedidoEstado::Despachado,
            PedidoEstado::Entregado,
            PedidoEstado::EnProduccion,
            PedidoEstado::EnDiseno,
            PedidoEstado::Cancelado,
        ];

        $pedidosConfig = [
            ['modelo' => 'Polo corporativo', 'tallas' => ['S' => 20, 'M' => 30, 'L' => 25], 'precio' => 35.00],
            ['modelo' => 'Camiseta deportiva', 'tallas' => ['M' => 50, 'L' => 40, 'XL' => 30], 'precio' => 42.00],
            ['modelo' => 'Polo cuello V', 'tallas' => ['S' => 15, 'M' => 25], 'precio' => 38.50],
            ['modelo' => 'Polera oversize', 'tallas' => ['M' => 40, 'L' => 35], 'precio' => 55.00],
            ['modelo' => 'Polo escolar', 'tallas' => ['10' => 30, '12' => 30, '14' => 20], 'precio' => 28.00],
            ['modelo' => 'Camiseta promocional', 'tallas' => ['M' => 100], 'precio' => 22.00],
            ['modelo' => 'Polo ejecutivo', 'tallas' => ['S' => 10, 'M' => 20, 'L' => 15, 'XL' => 5], 'precio' => 48.00],
            ['modelo' => 'Polera básica', 'tallas' => ['M' => 60, 'L' => 60], 'precio' => 30.00],
            ['modelo' => 'Polo con bolsillo', 'tallas' => ['M' => 25, 'L' => 25], 'precio' => 40.00],
            ['modelo' => 'Camiseta evento', 'tallas' => ['L' => 80], 'precio' => 25.00],
        ];

        $pedidos = collect();

        foreach ($estadosPedido as $index => $estado) {
            $config = $pedidosConfig[$index];
            $cliente = $clientes[$index % $clientes->count()];

            $cantidadTotal = array_sum($config['tallas']);
            $precioTotal = $cantidadTotal * $config['precio'];
            $anticipo = round($precioTotal * 0.5, 2);

            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'fecha' => now()->subDays(30 - ($index * 2))->toDateString(),
                'cantidad_total' => $cantidadTotal,
                'precio_total' => $precioTotal,
                'anticipo' => $anticipo,
                'saldo_pendiente' => round($precioTotal - $anticipo, 2),
                'estado' => $estado,
            ]);

            foreach ($config['tallas'] as $talla => $cantidad) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'talla' => (string) $talla,
                    'modelo' => $config['modelo'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $config['precio'],
                ]);
            }

            $pedidos->push($pedido);
        }

        $pedidoEnDiseno = $pedidos->first(fn (Pedido $p) => $p->estado === PedidoEstado::EnDiseno);
        if ($pedidoEnDiseno) {
            Diseno::create([
                'pedido_id' => $pedidoEnDiseno->id,
                'disenador_id' => $disenador->id,
                'archivo_path' => 'disenos/pedido_'.$pedidoEnDiseno->id.'/logo_v1.ai',
                'tipo_archivo' => TipoArchivoDiseno::Ai,
                'resolucion_dpi' => 300,
                'peso_kb' => 2450,
                'tiene_marca_agua' => true,
                'aprobado' => false,
                'bloqueado' => false,
            ]);
        }

        $pedidoProduccion = $pedidos->first(fn (Pedido $p) => $p->estado === PedidoEstado::EnProduccion);
        if ($pedidoProduccion) {
            LoteCorte::create([
                'pedido_id' => $pedidoProduccion->id,
                'operario_id' => $operarioCorte->id,
                'metros_tela_usados' => 45.50,
                'merma_metros' => 2.30,
                'piezas_obtenidas' => $pedidoProduccion->cantidad_total,
                'fecha' => now()->subDays(5)->toDateString(),
            ]);
        }

        $pedidoCalidad = $pedidos->first(fn (Pedido $p) => $p->estado === PedidoEstado::EnCalidad);
        if ($pedidoCalidad) {
            $loteCorte = LoteCorte::create([
                'pedido_id' => $pedidoCalidad->id,
                'operario_id' => $operarioCorte->id,
                'metros_tela_usados' => 30.00,
                'merma_metros' => 1.50,
                'piezas_obtenidas' => $pedidoCalidad->cantidad_total,
                'fecha' => now()->subDays(7)->toDateString(),
            ]);

            $loteEstampado = LoteEstampado::create([
                'lote_corte_id' => $loteCorte->id,
                'operario_id' => $operarioCorte->id,
                'prueba_aprobada' => true,
                'piezas_estampadas' => $pedidoCalidad->cantidad_total,
                'piezas_con_defecto' => 0,
                'fecha' => now()->subDays(6)->toDateString(),
            ]);

            $loteCostura = LoteCostura::create([
                'lote_estampado_id' => $loteEstampado->id,
                'operario_id' => $operarioCorte->id,
                'piezas_cosidas' => $pedidoCalidad->cantidad_total - 2,
                'piezas_merma' => 2,
                'fecha' => now()->subDays(5)->toDateString(),
            ]);

            InspeccionCalidad::create([
                'lote_costura_id' => $loteCostura->id,
                'supervisor_id' => $supervisor->id,
                'tolerancia_cm' => 1.5,
                'piezas_aprobadas' => $pedidoCalidad->cantidad_total - 5,
                'piezas_rechazadas' => 5,
                'firma_digital_path' => 'firmas/supervisor_'.$supervisor->id.'.png',
                'fecha' => now()->subDays(2)->toDateString(),
                'aprobado_para_empaque' => false,
            ]);
        }

        $pedidoDespachado = $pedidos->first(fn (Pedido $p) => $p->estado === PedidoEstado::Despachado);
        if ($pedidoDespachado) {
            Despacho::create([
                'pedido_id' => $pedidoDespachado->id,
                'fecha' => now()->subDays(1)->toDateString(),
                'dni_receptor' => '45678901',
                'nombre_receptor' => 'Juan Pérez',
                'guia_remision_numero' => 'GR-2026-0042',
                'etiqueta_path' => 'etiquetas/pedido_'.$pedidoDespachado->id.'.pdf',
            ]);
        }

        $pedidoEntregado = $pedidos->first(fn (Pedido $p) => $p->estado === PedidoEstado::Entregado);
        if ($pedidoEntregado) {
            TicketReclamo::create([
                'pedido_id' => $pedidoEntregado->id,
                'cliente_id' => $pedidoEntregado->cliente_id,
                'fecha_reclamo' => now()->subDays(2)->toDateString(),
                'descripcion' => 'Dos polos presentaron descosido en el cuello después del primer lavado.',
                'calificacion' => TicketReclamoCalificacion::DefectoFabrica,
                'estado' => TicketReclamoEstado::EnRevision,
                'orden_cambio_generada' => false,
                'costo_cambio' => 0,
            ]);
        }

        unset($admin, $vendedor);
    }
}
