<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Enums\PedidoEstado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== \App\Enums\UserRole::Admin) {
            abort(403);
        }

        $stats = $this->calculateStats();

        return view('admin.reportes.index', $stats);
    }

    public function descargarPdf(Request $request)
    {
        if (auth()->user()->role !== \App\Enums\UserRole::Admin) {
            abort(403);
        }

        $stats = $this->calculateStats();

        $pdf = Pdf::loadView('admin.reportes.pdf', $stats);
        return $pdf->download('reporte-taller-' . now()->format('Y-m-d') . '.pdf');
    }

    private function calculateStats(): array
    {
        $ventasTotales = Pedido::query()
            ->where('estado', '!=', PedidoEstado::Cancelado)
            ->sum('precio_total');

        $pedidosTotales = Pedido::query()->count();

        // Conteo por estado
        $estadosCount = [];
        foreach (PedidoEstado::cases() as $estado) {
            $estadosCount[$estado->value] = [
                'label' => $estado->label(),
                'count' => Pedido::query()->where('estado', $estado)->count(),
                'badge' => $estado->badgeClass()
            ];
        }

        // Top Modelos
        $topModelos = DetallePedido::query()
            ->select('modelo', \Illuminate\Support\Facades\DB::raw('SUM(cantidad) as total_piezas'))
            ->groupBy('modelo')
            ->orderByDesc('total_piezas')
            ->limit(5)
            ->get();

        // Pedidos Recientes
        $pedidosRecientes = Pedido::query()
            ->with('cliente')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return compact(
            'ventasTotales',
            'pedidosTotales',
            'estadosCount',
            'topModelos',
            'pedidosRecientes'
        );
    }
}
