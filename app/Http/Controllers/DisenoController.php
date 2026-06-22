<?php

namespace App\Http\Controllers;

use App\Enums\PedidoEstado;
use App\Http\Requests\AprobarDisenoRequest;
use App\Http\Requests\StoreDisenoRequest;
use App\Models\Diseno;
use App\Models\Pedido;
use App\Models\PedidoEstadoHistorial;
use App\Services\DisenoArchivoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DisenoController extends Controller
{
    public function __construct(
        private readonly DisenoArchivoService $archivoService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Diseno::class);

        $pedidos = Pedido::query()
            ->with(['cliente', 'disenos'])
            ->whereIn('estado', [PedidoEstado::Pendiente, PedidoEstado::EnDiseno])
            ->orderByDesc('fecha')
            ->paginate(12);

        return view('disenador.disenos.index', compact('pedidos'));
    }

    public function create(Pedido $pedido): View|RedirectResponse
    {
        $this->authorize('create', Diseno::class);

        if ($pedido->disenos()->where('bloqueado', true)->exists()) {
            return redirect()
                ->route('disenador.disenos.index')
                ->with('error', 'El pedido #'.$pedido->id.' ya tiene un diseño aprobado y bloqueado.');
        }

        if (! in_array($pedido->estado, [PedidoEstado::Pendiente, PedidoEstado::EnDiseno], true)) {
            return redirect()
                ->route('disenador.disenos.index')
                ->with('error', 'El pedido #'.$pedido->id.' no está en fase de diseño.');
        }

        $pedido->load(['cliente', 'disenos.disenador']);

        return view('disenador.disenos.create', compact('pedido'));
    }

    public function store(StoreDisenoRequest $request, Pedido $pedido): RedirectResponse
    {
        $this->authorize('create', Diseno::class);

        if ($pedido->disenos()->where('bloqueado', true)->exists()) {
            return back()->with('error', 'No se pueden subir propuestas: el pedido ya tiene un diseño bloqueado.');
        }

        $archivoData = $this->archivoService->almacenar($request->file('archivo'), $pedido->id);

        DB::transaction(function () use ($request, $pedido, $archivoData) {
            Diseno::create([
                'pedido_id' => $pedido->id,
                'disenador_id' => $request->user()->id,
                'archivo_path' => $archivoData['archivo_path'],
                'archivo_marca_agua_path' => $archivoData['archivo_marca_agua_path'],
                'tipo_archivo' => $archivoData['tipo'],
                'resolucion_dpi' => $archivoData['resolucion_dpi'],
                'peso_kb' => $archivoData['peso_kb'],
                'tiene_marca_agua' => $archivoData['tiene_marca_agua'],
            ]);

            if ($pedido->estado === PedidoEstado::Pendiente) {
                $pedido->update(['estado' => PedidoEstado::EnDiseno]);

                PedidoEstadoHistorial::create([
                    'pedido_id' => $pedido->id,
                    'estado' => PedidoEstado::EnDiseno,
                    'comentario' => 'Propuesta de diseño cargada por el diseñador.',
                    'user_id' => $request->user()->id,
                ]);
            }
        });

        return redirect()
            ->route('disenador.disenos.create', $pedido)
            ->with('success', 'Propuesta de diseño cargada correctamente.');
    }

    public function revisarCliente(Pedido $pedido): View|RedirectResponse
    {
        $this->authorize('view', $pedido);

        $pedido->load(['disenos.disenador']);

        if ($pedido->disenos->isEmpty()) {
            return redirect()
                ->route('cliente.pedidos.show', $pedido)
                ->with('error', 'Aún no hay propuestas de diseño para este pedido.');
        }

        return view('cliente.disenos.revisar', compact('pedido'));
    }

    public function aprobar(AprobarDisenoRequest $request, Diseno $diseno): RedirectResponse
    {
        if ($diseno->bloqueado) {
            return back()->with('error', 'Este diseño está bloqueado y no puede modificarse.');
        }

        if ($diseno->pedido->disenos()->where('bloqueado', true)->where('id', '!=', $diseno->id)->exists()) {
            return back()->with('error', 'Este pedido ya tiene un diseño aprobado.');
        }

        if ($diseno->aprobado) {
            return back()->with('error', 'Este diseño ya fue aprobado.');
        }

        DB::transaction(function () use ($request, $diseno) {
            $diseno->update([
                'aprobado' => true,
                'bloqueado' => true,
                'fecha_aprobacion' => now(),
            ]);

            $pedido = $diseno->pedido;

            if ($pedido->estado !== PedidoEstado::EnProduccion) {
                $pedido->update(['estado' => PedidoEstado::EnProduccion]);

                PedidoEstadoHistorial::create([
                    'pedido_id' => $pedido->id,
                    'estado' => PedidoEstado::EnProduccion,
                    'comentario' => 'Diseño #'.$diseno->id.' aprobado por el cliente. Pedido en producción.',
                    'user_id' => $request->user()->id,
                ]);
            }
        });

        return redirect()
            ->route('cliente.pedidos.disenos.revisar', $diseno->pedido_id)
            ->with('success', 'Diseño aprobado. El pedido pasó a producción.');
    }

    public function archivo(Request $request, Diseno $diseno, string $version): StreamedResponse
    {
        if ($diseno->bloqueado && $request->isMethod('PUT', 'PATCH', 'POST') && $request->routeIs('*update*')) {
            abort(403, 'Este diseño está bloqueado y no puede modificarse.');
        }

        $version = strtolower($version);

        if ($version === 'original') {
            $this->authorize('viewOriginal', $diseno);
            $path = $diseno->archivo_path;
        } elseif ($version === 'marca-agua') {
            $this->authorize('viewWatermarked', $diseno);
            $path = $diseno->archivo_marca_agua_path;
        } else {
            abort(404);
        }

        if (! $path || ! Storage::disk('disenos')->exists($path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('disenos')->response($path, basename($path));
    }
}
