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
     * Prueba simple de PDF
     */
    public function testPdf()
    {
        $html = '<h1>PDF de Prueba</h1><p>Si ves esto, dompdf está funcionando correctamente.</p>';
        $pdf = Pdf::loadHTML($html);
        return $pdf->stream('test.pdf');
    }

    /**
     * Genera el PDF del corte de caja diario (GET)
     */
    public function corteCajaPdf(Request $request)
    {
        try {
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
            $settings = DB::table('business_settings')->where('user_id', $userId)->first();

            // Si no existe configuración, usar valores por defecto
            $nombreNegocio = $settings ? ($settings->business_name ?? 'MI NEGOCIO') : 'MI NEGOCIO';

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
            \Log::info('Generando PDF (GET) para fecha: ' . $fecha);

            $pdf = Pdf::loadView('reports.corte-caja-pdf', $data);
            $pdf->setPaper('letter', 'portrait');

            $filename = 'corte-caja-' . $fecha . '.pdf';

            // Generar el PDF y forzar descarga con headers correctos
            return response($pdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error generando PDF de corte de caja (GET): ' . $e->getMessage());

            return response('<h1>Error al generar PDF</h1><p>' . $e->getMessage() . '</p>', 500);
        }
    }

    /**
     * Genera el PDF del corte de caja diario
     */
    public function corteCaja(Request $request)
    {
        try {
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
            $settings = DB::table('business_settings')->where('user_id', $userId)->first();

            // Si no existe configuración, usar valores por defecto
            $nombreNegocio = $settings ? ($settings->business_name ?? 'MI NEGOCIO') : 'MI NEGOCIO';

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
            \Log::info('Generando PDF para fecha: ' . $fecha);
            \Log::info('Ventas encontradas: ' . $ventas->count());

            $pdf = Pdf::loadView('reports.corte-caja-pdf', $data);
            $pdf->setPaper('letter', 'portrait');

            \Log::info('PDF generado exitosamente, iniciando descarga...');

            $filename = 'corte-caja-' . $fecha . '.pdf';

            // Generar el PDF y forzar descarga con headers correctos
            return response($pdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error generando PDF de corte de caja: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Retornar con mensaje de error
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
