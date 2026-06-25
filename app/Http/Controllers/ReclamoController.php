<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reclamo;
use App\Models\Pedido;

class ReclamoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $clienteId = $user->cliente ? $user->cliente->id : null;

        $reclamos = [];
        $pedidosEntregados = [];

        if ($clienteId) {
            $reclamos = Reclamo::whereHas('pedido', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            })->orderBy('created_at', 'desc')->get();

            $pedidosEntregados = Pedido::where('cliente_id', $clienteId)
                ->where('estado', \App\Enums\PedidoEstado::Entregado)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('cliente.reclamos.index', compact('reclamos', 'pedidosEntregados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|integer',
            'motivo'    => 'required|string',
        ]);

        $pedido = Pedido::find($request->input('pedido_id'));

        if (!$pedido) {
            return back()->with('error', 'El pedido seleccionado no existe.');
        }

        $user = $request->user();
        $clienteId = $user->cliente ? $user->cliente->id : null;

        if (!$clienteId || $pedido->cliente_id !== $clienteId) {
            return back()->with('error', 'El pedido seleccionado no le pertenece o no es válido.');
        }

        if ($pedido->estado !== \App\Enums\PedidoEstado::Entregado) {
            return back()->with('error', 'Solo se pueden registrar reclamos para pedidos entregados.');
        }

        // Buscar en el historial de estados la fecha en la que se entregó
        $fechaEntrega = $pedido->historialEstados()
            ->where('estado', \App\Enums\PedidoEstado::Entregado)
            ->first()?->created_at ?? $pedido->updated_at;

        $dias_pasados = $fechaEntrega->diffInDays(now());

        if ($dias_pasados > 7) {
            return back()->with('error', 'El plazo máximo de 7 días desde la entrega ha expirado para registrar un reclamo sobre este pedido.');
        }

        Reclamo::create([
            'pedido_id' => $pedido->id,
            'motivo'    => $request->input('motivo'),
            'estado'    => 'En revisión'
        ]);

        return back()->with('success', 'Su reclamo para el pedido PED-' . str_pad($pedido->id, 4, '0', STR_PAD_LEFT) . ' ha sido registrado en el sistema.');
    }
}