<?php

namespace App\Http\Controllers;

use App\Enums\PedidoEstado;
use App\Http\Requests\StoreLoteCorteRequest;
use App\Models\InventarioTela;
use App\Models\LoteCorte;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoteCorteController extends Controller
{
    public function index(): View
    {
        $pedidosPendientes = Pedido::query()
            ->with(['cliente', 'inventarioTela', 'detalles'])
            ->where('estado', PedidoEstado::EnProduccion)
            ->whereDoesntHave('lotesCorte')
            ->orderByDesc('fecha')
            ->get();

        $lotesCompletados = LoteCorte::query()
            ->with(['pedido.cliente', 'inventarioTela'])
            ->where('operario_id', auth()->id())
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();

        return view('operario.corte.index', compact('pedidosPendientes', 'lotesCompletados'));
    }

    public function create(Pedido $pedido): View|RedirectResponse
    {
        if ($pedido->estado !== PedidoEstado::EnProduccion) {
            return redirect()->route('operario.corte.index')
                ->with('error', 'El pedido no está en producción.');
        }

        if ($pedido->lotesCorte()->exists()) {
            return redirect()->route('operario.corte.index')
                ->with('error', 'Este pedido ya tiene lote de corte.');
        }

        $pedido->load(['cliente', 'detalles', 'inventarioTela']);
        $telas = InventarioTela::query()->orderBy('tipo_tela')->get();

        return view('operario.corte.create', compact('pedido', 'telas'));
    }

    public function store(StoreLoteCorteRequest $request, Pedido $pedido): RedirectResponse
    {
        DB::transaction(function () use ($request, $pedido) {
            $tela = InventarioTela::query()
                ->lockForUpdate()
                ->findOrFail($request->input('inventario_tela_id'));

            $metrosUsados = round((float) $request->input('metros_tela_usados'), 2);
            $merma = round((float) $request->input('merma_metros'), 2);
            $totalConsumido = round($metrosUsados + $merma, 2);

            $tela->decrement('stock_actual_metros', $totalConsumido);

            if (! $pedido->inventario_tela_id) {
                $pedido->update(['inventario_tela_id' => $tela->id]);
            }

            LoteCorte::create([
                'pedido_id' => $pedido->id,
                'inventario_tela_id' => $tela->id,
                'operario_id' => $request->user()->id,
                'metros_tela_usados' => $metrosUsados,
                'merma_metros' => $merma,
                'piezas_obtenidas' => (int) $request->input('piezas_obtenidas'),
                'fecha' => $request->input('fecha', now()->toDateString()),
            ]);
        });

        return redirect()
            ->route('operario.corte.index')
            ->with('success', 'Lote de corte registrado. Se descontaron '.number_format(
                (float) $request->input('metros_tela_usados') + (float) $request->input('merma_metros'),
                2
            ).' m de tela del inventario.');
    }
}
