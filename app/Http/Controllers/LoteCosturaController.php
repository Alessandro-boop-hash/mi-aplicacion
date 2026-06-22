<?php

namespace App\Http\Controllers;

use App\Enums\PedidoEstado;
use App\Http\Requests\StoreLoteCosturaRequest;
use App\Models\LoteCostura;
use App\Models\LoteEstampado;
use App\Models\PedidoEstadoHistorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoteCosturaController extends Controller
{
    public function index(): View
    {
        $lotesPendientes = LoteEstampado::query()
            ->with(['loteCorte.pedido.cliente'])
            ->where('procesamiento_completado', true)
            ->whereDoesntHave('lotesCostura')
            ->orderByDesc('fecha')
            ->get();

        $lotesCompletados = LoteCostura::query()
            ->with(['loteEstampado.loteCorte.pedido.cliente'])
            ->where('operario_id', auth()->id())
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();

        return view('operario.costura.index', compact('lotesPendientes', 'lotesCompletados'));
    }

    public function create(LoteEstampado $loteEstampado): View|RedirectResponse
    {
        if (! $loteEstampado->procesamiento_completado) {
            return redirect()->route('operario.costura.index')
                ->with('error', 'El lote de estampado debe estar completado primero.');
        }

        if ($loteEstampado->lotesCostura()->exists()) {
            return redirect()->route('operario.costura.index')
                ->with('error', 'Este lote ya tiene registro de costura.');
        }

        $loteEstampado->load(['loteCorte.pedido.cliente']);
        $piezasEsperadas = $loteEstampado->piezas_estampadas - $loteEstampado->piezas_con_defecto;

        return view('operario.costura.create', compact('loteEstampado', 'piezasEsperadas'));
    }

    public function store(StoreLoteCosturaRequest $request, LoteEstampado $loteEstampado): RedirectResponse
    {
        DB::transaction(function () use ($request, $loteEstampado) {
            LoteCostura::create([
                'lote_estampado_id' => $loteEstampado->id,
                'operario_id' => $request->user()->id,
                'piezas_cosidas' => (int) $request->input('piezas_cosidas'),
                'piezas_merma' => (int) $request->input('piezas_merma'),
                'fecha' => $request->input('fecha', now()->toDateString()),
            ]);

            $pedido = $loteEstampado->loteCorte->pedido;

            if ($pedido->estado !== PedidoEstado::EnCalidad) {
                $pedido->update(['estado' => PedidoEstado::EnCalidad]);

                PedidoEstadoHistorial::create([
                    'pedido_id' => $pedido->id,
                    'estado' => PedidoEstado::EnCalidad,
                    'comentario' => 'Costura completada. Pedido enviado a control de calidad.',
                    'user_id' => $request->user()->id,
                ]);
            }
        });

        return redirect()
            ->route('operario.costura.index')
            ->with('success', 'Lote de costura registrado. El pedido pasó a control de calidad.');
    }
}
