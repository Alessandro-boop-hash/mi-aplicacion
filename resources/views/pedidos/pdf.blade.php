<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
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
            letter-spacing: 0.1em;
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
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .doc-number {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
        }
        .info-section {
            margin-bottom: 20px;
            border-top: 2px solid #000000;
            border-bottom: 1px solid #e7e5e4;
            padding: 10px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
        }
        .info-table td {
            border: none !important;
            padding: 4px 0 !important;
            vertical-align: top;
        }
        .info-label {
            color: #78716c;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            width: 120px;
        }
        .info-value {
            font-weight: 500;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f8f8f9 !important;
            color: #000000;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 10px;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #000000 !important;
            padding: 8px 10px !important;
            text-align: left;
        }
        .items-table td {
            border-bottom: 1px solid #e7e5e4 !important;
            padding: 10px !important;
            font-size: 11px;
        }
        .text-right {
            text-align: right !important;
        }
        .financial-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: none !important;
        }
        .financial-table td {
            border: none !important;
            padding: 6px 0 !important;
        }
        .financial-label {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 10px;
            color: #78716c;
        }
        .financial-value {
            text-align: right;
            font-weight: bold;
            font-size: 13px;
        }
        .financial-total {
            border-top: 2px solid #000000 !important;
            padding-top: 10px !important;
            font-size: 15px;
            color: #000000;
        }
        .clear {
            clear: both;
        }
        .history-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .section-heading {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #000000;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
        }
        .history-table td {
            border: none !important;
            border-bottom: 1px solid #f2f0ef !important;
            padding: 6px 10px !important;
            font-size: 11px;
            vertical-align: top;
        }
        .history-badge {
            background-color: #000000;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 6px;
            display: inline-block;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
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
                <div class="doc-title">Ficha de Pedido</div>
                <div class="doc-number">PED-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Cliente</td>
                <td class="info-value">{{ $pedido->cliente->nombre }}</td>
                <td class="info-label">Fecha Registro</td>
                <td class="info-value">{{ $pedido->fecha->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">RUC / DNI</td>
                <td class="info-value">{{ $pedido->cliente->tipo_documento->value }}: {{ $pedido->cliente->numero_documento }}</td>
                <td class="info-label">Estado Actual</td>
                <td class="info-value" style="font-weight: bold; text-transform: uppercase;">
                    {{ $pedido->estado->label() }}
                </td>
            </tr>
            <tr>
                <td class="info-label">Correo</td>
                <td class="info-value">{{ $pedido->cliente->email ?? '-' }}</td>
                <td class="info-label">Teléfono</td>
                <td class="info-value">{{ $pedido->cliente->telefono ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Dirección</td>
                <td class="info-value" colspan="3">{{ $pedido->cliente->direccion ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Modelo / Descripción</th>
                <th>Talla</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->detalles as $detalle)
                <tr>
                    <td style="font-weight: bold;">{{ $detalle->modelo }}</td>
                    <td>{{ $detalle->talla }}</td>
                    <td class="text-right">{{ $detalle->cantidad }} uds.</td>
                    <td class="text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">S/ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="financial-table">
        <tr>
            <td class="financial-label">Subtotal</td>
            <td class="financial-value">S/ {{ number_format($pedido->precio_total, 2) }}</td>
        </tr>
        <tr>
            <td class="financial-label">Anticipo (Pág.)</td>
            <td class="financial-value">S/ {{ number_format($pedido->anticipo, 2) }}</td>
        </tr>
        <tr class="financial-total">
            <td class="financial-label" style="color: #000000; font-size: 11px;">Saldo Pendiente</td>
            <td class="financial-value" style="font-size: 15px; color: #000000;">S/ {{ number_format($pedido->saldo_pendiente, 2) }}</td>
        </tr>
    </table>

    <div class="clear"></div>

    @if($pedido->historialEstados->isNotEmpty())
        <div class="history-section">
            <div class="section-heading">Historial y Seguimiento del Pedido</div>
            <table class="history-table">
                @foreach ($pedido->historialEstados->sortBy('created_at') as $historial)
                    <tr>
                        <td style="width: 120px; color: #78716c;">{{ $historial->created_at->format('d/m/Y H:i') }}</td>
                        <td style="width: 130px;">
                            <span class="history-badge">{{ $historial->estado->label() }}</span>
                        </td>
                        <td>
                            {{ $historial->comentario ?? '-' }}
                            @if($historial->user)
                                <div style="font-size: 9px; color: #78716c; margin-top: 2px;">Registrado por: {{ $historial->user->name }}</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    <div class="footer">
        Marte Taller Textil &bull; Sistema de Gestión de Confección Profesional
    </div>

</body>
</html>
