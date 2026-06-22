<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisenoController;
use App\Http\Controllers\LoteCorteController;
use App\Http\Controllers\LoteCosturaController;
use App\Http\Controllers\LoteEstampadoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReclamoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:cliente')->prefix('cliente')->name('cliente.')->group(function () {
        Route::get('/inicio', function () {
            return view('cliente.inicio');
        })->name('inicio');

        Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/crear', [PedidoController::class, 'create'])->name('pedidos.create');
        Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
        Route::get('/pedidos/{pedido}/disenos', [DisenoController::class, 'revisarCliente'])->name('pedidos.disenos.revisar');
        Route::post('/disenos/{diseno}/aprobar', [DisenoController::class, 'aprobar'])->name('disenos.aprobar');
        
        // --- RUTAS DE RECLAMOS ACTUALIZADAS ---
        Route::get('/reclamos', [ReclamoController::class, 'index'])->name('reclamos.index');
        Route::post('/reclamos', [ReclamoController::class, 'store'])->name('reclamos.store');
    });

    Route::middleware('role:vendedor')->prefix('vendedor')->name('vendedor.')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/crear', [PedidoController::class, 'create'])->name('pedidos.create');
        Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
        Route::view('/clientes', 'vendedor.clientes.index')->name('clientes.index');
    });

    Route::middleware('role:disenador')->prefix('disenador')->name('disenador.')->group(function () {
        Route::get('/disenos', [DisenoController::class, 'index'])->name('disenos.index');
        Route::get('/pedidos/{pedido}/disenos/crear', [DisenoController::class, 'create'])->name('disenos.create');
        Route::post('/pedidos/{pedido}/disenos', [DisenoController::class, 'store'])->name('disenos.store');
    });

    Route::get('/disenos/{diseno}/archivo/{version}', [DisenoController::class, 'archivo'])
        ->where('version', 'original|marca-agua')
        ->name('disenos.archivo');

    Route::middleware('role:operario_corte')->prefix('operario')->name('operario.')->group(function () {
        Route::get('/corte', [LoteCorteController::class, 'index'])->name('corte.index');
        Route::get('/corte/pedidos/{pedido}', [LoteCorteController::class, 'create'])->name('corte.create');
        Route::post('/corte/pedidos/{pedido}', [LoteCorteController::class, 'store'])->name('corte.store');
    });

    Route::middleware('role:operario_estampado')->prefix('operario')->name('operario.')->group(function () {
        Route::get('/estampado', [LoteEstampadoController::class, 'index'])->name('estampado.index');
        Route::get('/estampado/lotes-corte/{loteCorte}', [LoteEstampadoController::class, 'create'])->name('estampado.create');
        Route::post('/estampado/lotes-corte/{loteCorte}/prueba', [LoteEstampadoController::class, 'registrarPrueba'])->name('estampado.prueba');
        Route::post('/estampado/lotes/{loteEstampado}', [LoteEstampadoController::class, 'store'])->name('estampado.store');
    });

    Route::middleware('role:operario_costura')->prefix('operario')->name('operario.')->group(function () {
        Route::get('/costura', [LoteCosturaController::class, 'index'])->name('costura.index');
        Route::get('/costura/lotes/{loteEstampado}', [LoteCosturaController::class, 'create'])->name('costura.create');
        Route::post('/costura/lotes/{loteEstampado}', [LoteCosturaController::class, 'store'])->name('costura.store');
    });

    Route::middleware('role:supervisor_calidad')->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::view('/calidad', 'supervisor.calidad.index')->name('calidad.index');
    });

    Route::middleware('role:almacenero')->prefix('almacenero')->name('almacenero.')->group(function () {
        Route::view('/inventario', 'almacenero.inventario.index')->name('inventario.index');
        Route::view('/insumos', 'almacenero.insumos.index')->name('insumos.index');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::view('/usuarios', 'admin.usuarios.index')->name('usuarios.index');
        Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
        Route::view('/inventario', 'admin.inventario.index')->name('inventario.index');
        Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';