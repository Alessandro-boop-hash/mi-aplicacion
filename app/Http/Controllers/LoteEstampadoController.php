<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrarPruebaEstampadoRequest;
use App\Http\Requests\StoreLoteEstampadoRequest;
use App\Models\LoteCorte;
use App\Models\LoteEstampado;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoteEstampadoController extends Controller
{
    public function index(): View
    {
        $lotesPendientesPrueba = LoteCorte::query()
            ->with(['pedido.cliente', 'lotesEstampado'])
            ->whereDoesntHave('lotesEstampado', fn ($q) => $q->where('prueba_aprobada', true))
            ->orderByDesc('fecha')
            ->get();

        $lotesPendientesProceso = LoteEstampado::query()
            ->with(['loteCorte.pedido.cliente'])
            ->where('prueba_aprobada', true)
            ->where('procesamiento_completado', false)
            ->orderByDesc('created_at')
            ->get();

        $lotesCompletados = LoteEstampado::query()
            ->with(['loteCorte.pedido.cliente'])
            ->where('procesamiento_completado', true)
            ->where('operario_id', auth()->id())
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();

        return view('operario.estampado.index', compact(
            'lotesPendientesPrueba',
            'lotesPendientesProceso',
            'lotesCompletados'
        ));
    }

    public function create(LoteCorte $loteCorte): View|RedirectResponse
    {
        $loteCorte->load(['pedido.cliente', 'lotesEstampado']);

        $loteEstampado = $loteCorte->lotesEstampado()->first();

        if ($loteEstampado?->procesamiento_completado) {
            return redirect()->route('operario.estampado.index')
                ->with('error', 'Este lote ya fue procesado.');
        }

        return view('operario.estampado.create', compact('loteCorte', 'loteEstampado'));
    }

    public function registrarPrueba(RegistrarPruebaEstampadoRequest $request, LoteCorte $loteCorte): RedirectResponse
    {
        $loteEstampado = $loteCorte->lotesEstampado()->first();

        if ($loteEstampado) {
            $loteEstampado->update([
                'prueba_aprobada' => true,
                'operario_id' => $request->user()->id,
            ]);
        } else {
            LoteEstampado::create([
                'lote_corte_id' => $loteCorte->id,
                'operario_id' => $request->user()->id,
                'prueba_aprobada' => true,
                'procesamiento_completado' => false,
                'piezas_estampadas' => 0,
                'piezas_con_defecto' => 0,
                'reposicion_solicitada' => false,
                'fecha' => now()->toDateString(),
            ]);
        }

        return redirect()
            ->route('operario.estampado.create', $loteCorte)
            ->with('success', 'Prueba de impresión aprobada. Ya puede registrar el procesamiento del lote.');
    }

    public function store(StoreLoteEstampadoRequest $request, LoteEstampado $loteEstampado): RedirectResponse
    {
        $defectos = (int) $request->input('piezas_con_defecto');

        $loteEstampado->update([
            'piezas_estampadas' => (int) $request->input('piezas_estampadas'),
            'piezas_con_defecto' => $defectos,
            'reposicion_solicitada' => $defectos > 0,
            'procesamiento_completado' => true,
            'operario_id' => $request->user()->id,
            'fecha' => $request->input('fecha', now()->toDateString()),
        ]);

        $mensaje = 'Lote de estampado registrado correctamente.';
        if ($defectos > 0) {
            $mensaje .= " Se solicitó reposición de {$defectos} pieza(s) con defecto.";
        }

        return redirect()
            ->route('operario.estampado.index')
            ->with('success', $mensaje);
    }
}
