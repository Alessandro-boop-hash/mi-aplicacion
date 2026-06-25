<?php

namespace App\Http\Controllers;

use App\Enums\PedidoEstado;
use App\Enums\UserRole;
use App\Http\Requests\StorePedidoRequest;
use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\PedidoEstadoHistorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\PaymentService;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Pedido::class);

        $query = Pedido::query()
            ->with('cliente')
            ->orderByDesc('fecha')
            ->orderByDesc('id');

        $user = $request->user();

        if ($user->role === UserRole::Cliente) {
            $cliente = $user->cliente;
            if (! $cliente) {
                $pedidos = Pedido::query()->whereRaw('1 = 0')->paginate(15);
                return view('pedidos.index', [
                    'pedidos' => $pedidos,
                    'estados' => PedidoEstado::cases(),
                    'routePrefix' => $this->routePrefix($user),
                    'canCreate' => true,
                ]);
            }
            $query->where('cliente_id', (int) $cliente->id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->string('estado'));
        }

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar')->trim();
            $query->where(function ($builder) use ($buscar) {
                if (is_numeric($buscar)) {
                    $builder->where('id', (int) $buscar);
                } else {
                    $builder->whereHas('cliente', function ($clienteQuery) use ($buscar) {
                        $clienteQuery->where('nombre', 'like', '%'.$buscar.'%');
                    });
                }
            });
        }

        return view('pedidos.index', [
            'pedidos' => $query->paginate(15)->withQueryString(),
            'estados' => PedidoEstado::cases(),
            'routePrefix' => $this->routePrefix($user),
            'canCreate' => $user->can('create', Pedido::class),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Pedido::class);
        $user = $request->user();

        return view('pedidos.create', [
            'clientes' => $user->hasRole(UserRole::Vendedor, UserRole::Admin)
                ? Cliente::query()->orderBy('nombre')->get()
                : collect(),
            'clienteActual' => $user->cliente,
            'routePrefix' => $this->routePrefix($user),
            'isVendedor' => $user->hasRole(UserRole::Vendedor, UserRole::Admin),
            'tiposDocumento' => \App\Enums\TipoDocumento::cases(),
        ]);
    }

    public function store(StorePedidoRequest $request, PaymentService $paymentService): RedirectResponse
    {
        // 1. PROCESAR PAGO PRIMERO
        try {
            $success = $paymentService->charge(
                (float)$request->input('monto_pagado'),
                $request->user()->email,
                $request->input('culqi_token'),
                "Pedido desde Taller Confeccion"
            );

            if (!$success) {
                return back()->with('error', 'El pago fue rechazado por el banco.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }

        // 2. SI EL PAGO ES EXITOSO, GUARDAMOS EN BASE DE DATOS
        $user = $request->user();
        $routePrefix = $this->routePrefix($user);

        $pedido = DB::transaction(function () use ($request, $user) {
            $clienteId = $this->resolveClienteId($request, $user);
            $totals = $this->calculateTotals($request->input('detalles', []));
            $montoPagado = round((float) $request->input('monto_pagado'), 2);

            $pedido = Pedido::create([
                'cliente_id' => (int) $clienteId,
                'fecha' => $request->input('fecha', now()->toDateString()),
                'cantidad_total' => $totals['cantidad_total'],
                'precio_total' => $totals['precio_total'],
                'anticipo' => round($totals['precio_total'] * 0.5, 2),
                'saldo_pendiente' => round($totals['precio_total'] - $montoPagado, 2),
                'estado' => PedidoEstado::Pendiente,
            ]);

            foreach ($request->input('detalles', []) as $detalle) {
                DetallePedido::create([
                    'pedido_id' => (int) $pedido->id,
                    'modelo' => $detalle['modelo'],
                    'talla' => $detalle['talla'],
                    'cantidad' => (int) $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                ]);
            }

            PedidoEstadoHistorial::create([
                'pedido_id' => (int) $pedido->id,
                'estado' => PedidoEstado::Pendiente,
                'comentario' => 'Pago recibido via Culqi. Monto: S/ ' . number_format($montoPagado, 2),
                'user_id' => (int) $user->id,
            ]);

            return $pedido;
        });

        return redirect()
            ->route("{$routePrefix}.pedidos.show", $pedido)
            ->with('success', 'Pago exitoso y pedido #' . $pedido->id . ' registrado correctamente.');
    }

    public function show(Request $request, Pedido $pedido): View
    {
        $this->authorize('view', $pedido);
        $pedido->load(['cliente', 'detalles', 'historialEstados.user', 'disenos']);

        return view('pedidos.show', [
            'pedido' => $pedido,
            'routePrefix' => $this->routePrefix($request->user()),
            'canCreate' => $request->user()->can('create', Pedido::class),
        ]);
    }

    private function routePrefix(\App\Models\User $user): string
    {
        return match ($user->role) {
            UserRole::Vendedor => 'vendedor',
            UserRole::Cliente => 'cliente',
            UserRole::Admin => 'admin',
            default => 'vendedor',
        };
    }

    private function resolveClienteId(StorePedidoRequest $request, \App\Models\User $user): int
    {
        if ($user->role === UserRole::Cliente) {
            if ($user->cliente) {
                return (int) $user->cliente->id;
            }
            $cliente = Cliente::create([
                'user_id' => (int) $user->id,
                'nombre' => $request->input('cliente_nombre'),
                'tipo_documento' => $request->input('cliente_tipo_documento'),
                'numero_documento' => $request->input('cliente_numero_documento'),
                'email' => $request->input('cliente_email', $user->email),
                'telefono' => $request->input('cliente_telefono'),
                'direccion' => $request->input('cliente_direccion'),
            ]);
            return (int) $cliente->id;
        }

        if ($request->input('modo_cliente') === 'nuevo') {
            $cliente = Cliente::create([
                'nombre' => $request->input('cliente_nombre'),
                'tipo_documento' => $request->input('cliente_tipo_documento'),
                'numero_documento' => $request->input('cliente_numero_documento'),
                'email' => $request->input('cliente_email'),
                'telefono' => $request->input('cliente_telefono'),
                'direccion' => $request->input('cliente_direccion'),
            ]);
            return (int) $cliente->id;
        }

        return (int) $request->input('cliente_id');
    }

    private function calculateTotals(array $detalles): array
    {
        $cantidadTotal = 0;
        $precioTotal = 0.0;

        foreach ($detalles as $detalle) {
            $cantidad = (int) $detalle['cantidad'];
            $precioUnitario = (float) $detalle['precio_unitario'];
            $cantidadTotal += $cantidad;
            $precioTotal += $cantidad * $precioUnitario;
        }

        return [
            'cantidad_total' => $cantidadTotal,
            'precio_total' => round($precioTotal, 2),
        ];
    }

    public function descargarPdf(Pedido $pedido)
    {
        $this->authorize('view', $pedido);
        $pedido->load(['cliente', 'detalles', 'historialEstados.user']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pedidos.pdf', compact('pedido'));
        return $pdf->download('pedido-' . str_pad($pedido->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}