<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corte de Caja - {{ $fecha }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #10B981;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #374151;
            margin-bottom: 10px;
        }

        .header .date {
            font-size: 12px;
            color: #6B7280;
        }

        .summary-section {
            margin: 20px 0;
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 10px;
        }

        .summary-card {
            background-color: white;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #E5E7EB;
        }

        .summary-card .label {
            font-size: 10px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #10B981;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #D1D5DB;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #10B981;
            color: white;
        }

        table th {
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 10px;
        }

        table tbody tr:hover {
            background-color: #F9FAFB;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #F3F4F6;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #9CA3AF;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>{{ $nombreNegocio }}</h1>
        <h2>CORTE DE CAJA</h2>
        <div class="date">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</div>
    </div>

    <!-- Resumen General -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="label">Total Ventas</div>
                <div class="value">${{ number_format($totalVentas, 2) }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Tickets</div>
                <div class="value">{{ $numeroTickets }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Unidades</div>
                <div class="value">{{ $totalUnidades }}</div>
            </div>
        </div>
    </div>

    <!-- Productos Más Vendidos -->
    @if($productosMasVendidos->count() > 0)
    <div class="section-title">PRODUCTOS MÁS VENDIDOS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 50%;">Producto</th>
                <th style="width: 20%;" class="text-center">Cantidad</th>
                <th style="width: 20%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosMasVendidos as $index => $producto)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $producto->name }}</td>
                <td class="text-center">{{ $producto->total_cantidad }}</td>
                <td class="text-right">${{ number_format($producto->total_monto, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Detalle de Ventas -->
    <div class="section-title">DETALLE DE VENTAS</div>
    @if($ventas->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Ticket</th>
                <th style="width: 15%;">Hora</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 10%;" class="text-center">Items</th>
                <th style="width: 12%;" class="text-right">Monto</th>
                <th style="width: 30%;">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>#{{ $venta->id }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->sale_date)->format('H:i:s') }}</td>
                <td>{{ $venta->customer->name ?? 'N/A' }}</td>
                <td class="text-center">{{ $venta->saleItems->count() }}</td>
                <td class="text-right">${{ number_format($venta->amount, 2) }}</td>
                <td>{{ $venta->notes ?? '-' }}</td>
            </tr>
            <!-- Detalle de productos de la venta -->
            @foreach($venta->saleItems as $item)
            <tr style="background-color: #F9FAFB; font-size: 9px;">
                <td></td>
                <td colspan="2" style="padding-left: 20px;">→ {{ $item->product->name ?? 'Producto eliminado' }}</td>
                <td class="text-center">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</td>
                <td class="text-right">${{ number_format($item->quantity * $item->price, 2) }}</td>
                <td></td>
            </tr>
            @endforeach
            @endforeach

            <!-- Fila de Total -->
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL DEL DÍA:</td>
                <td class="text-right">${{ number_format($totalVentas, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="no-data">No hay ventas registradas para esta fecha</div>
    @endif

    <!-- Pie de página -->
    <div class="footer">
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | IslaControl - Sistema de Punto de Venta</p>
    </div>
</body>
</html>
