<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Muestra la página de reportes
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Genera el PDF del corte de caja diario
     */
    public function corteCaja(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = $validated['fecha'];
        $userId = auth()->id();

        // 1. Obtener todas las ventas del día
        $ventas = Sale::where('user_id', $userId)
            ->whereDate('sale_date', $fecha)
            ->with(['customer', 'saleItems.product'])
            ->orderBy('sale_date', 'asc')
            ->get();

        // 2. Calcular totales
        $totalVentas = $ventas->sum('amount');
        $numeroTickets = $ventas->count();
        $totalUnidades = $ventas->sum(function($venta) {
            return $venta->saleItems->sum('quantity');
        });

        // 3. Productos más vendidos del día
        $productosMasVendidos = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.user_id', $userId)
            ->whereDate('sales.sale_date', $fecha)
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_cantidad'),
                DB::raw('SUM(sale_items.quantity * sale_items.price) as total_monto')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_cantidad')
            ->limit(10)
            ->get();

        // 4. Obtener información del negocio
        $settings = DB::table('settings')->where('user_id', $userId)->first();

        // Si no existe configuración, usar valores por defecto
        $nombreNegocio = $settings ? ($settings->nombre_negocio ?? 'IslaControl') : 'IslaControl';

        // Preparar datos para el PDF
        $data = [
            'fecha' => $fecha,
            'ventas' => $ventas,
            'totalVentas' => $totalVentas,
            'numeroTickets' => $numeroTickets,
            'totalUnidades' => $totalUnidades,
            'productosMasVendidos' => $productosMasVendidos,
            'nombreNegocio' => $nombreNegocio,
        ];

        // Generar PDF
        $pdf = Pdf::loadView('reports.corte-caja-pdf', $data);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('corte-caja-' . $fecha . '.pdf');
    }
}
