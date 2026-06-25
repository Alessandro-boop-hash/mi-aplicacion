<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General - Taller Marte</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1c1917;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none !important;
            padding: 0 !important;
            vertical-align: top;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #78716c;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .doc-title-block {
            text-align: right;
        }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .doc-date {
            font-size: 11px;
            color: #78716c;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
            border: none !important;
        }
        .stats-grid td {
            width: 50%;
            border: 1px solid #000000 !important;
            padding: 15px !important;
            text-align: center;
            background-color: #ffffff;
        }
        .stats-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #78716c;
            margin-bottom: 5px;
        }
        .stats-value {
            font-size: 22px;
            font-weight: 900;
            color: #000000;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 2px solid #000000;
            padding-bottom: 5px;
            margin-bottom: 12px;
            margin-top: 25px;
        }
        .table-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-list th {
            background-color: #f8f8f9 !important;
            color: #000000;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 9px;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #000000 !important;
            padding: 8px 10px !important;
            text-align: left;
        }
        .table-list td {
            border-bottom: 1px solid #e7e5e4 !important;
            padding: 8px 10px !important;
            font-size: 10px;
        }
        .text-right {
            text-align: right !important;
        }
        .badge {
            background-color: #000000;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 5px;
            display: inline-block;
        }
        .two-column-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
        }
        .two-column-table td {
            width: 48%;
            border: none !important;
            padding: 0 !important;
            vertical-align: top;
        }
        .two-column-spacer {
            width: 4%;
            border: none !important;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #78716c;
            border-top: 1px solid #e7e5e4;
            padding-top: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">MARTE</div>
                <div class="brand-subtitle">Taller de Confección & Estampado</div>
            </td>
            <td class="doc-title-block">
                <div class="doc-title">Reporte General de Operaciones</div>
                <div class="doc-date">Generado: {{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="stats-grid">
        <tr>
            <td>
                <div class="stats-label">Ventas Totales (Activas)</div>
                <div class="stats-value">S/ {{ number_format($ventasTotales, 2) }}</div>
            </td>
            <td>
                <div class="stats-label">Pedidos Registrados</div>
                <div class="stats-value">{{ $pedidosTotales }}</div>
            </td>
        </tr>
    </table>

    <table class="two-column-table">
        <tr>
            <td>
                <div class="section-title">Pedidos por Estado</div>
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th class="text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estadosCount as $key => $estado)
                            <tr>
                                <td>{{ $estado['label'] }}</td>
                                <td class="text-right" style="font-weight: bold;">{{ $estado['count'] }} pedidos</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td class="two-column-spacer"></td>
            <td>
                <div class="section-title">Top Modelos Más Solicitados</div>
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Modelo / Producto</th>
                            <th class="text-right">Total Confeccionado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topModelos as $modelo)
                            <tr>
                                <td style="font-weight: bold;">{{ $modelo->modelo }}</td>
                                <td class="text-right">{{ $modelo->total_piezas }} uds.</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay detalles registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Últimos Pedidos Registrados</div>
    <table class="table-list">
        <thead>
            <tr>
                <th>N.º</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidosRecientes as $pedido)
                <tr>
                    <td style="font-weight: bold;">#{{ $pedido->id }}</td>
                    <td>{{ $pedido->cliente->nombre }}</td>
                    <td>{{ $pedido->fecha->format('d/m/Y') }}</td>
                    <td class="text-right">{{ $pedido->cantidad_total }} uds.</td>
                    <td class="text-right" style="font-weight: bold;">S/ {{ number_format($pedido->precio_total, 2) }}</td>
                    <td>
                        <span class="badge">{{ $pedido->estado->label() }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Marte Taller Textil &bull; Reporte Analítico de Ventas e Inventario
    </div>

</body>
</html>
