<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\PlanHelper;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar ventas del usuario autenticado con todas las relaciones necesarias
        $sales = Sale::where('user_id', auth()->id())
            ->with(['customer', 'saleItems.product'])
            ->orderBy('sale_date', 'desc')
            ->get();

        // Ordenar clientes: "Público General" primero, luego los demás alfabéticamente
        $customers = Customer::where('user_id', auth()->id())
            ->orderByRaw("CASE WHEN name = 'Público General' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $products = Product::where('user_id', auth()->id())->orderBy('name')->get();
        
        // Obtener configuración del negocio para el modal
        $nombre_negocio = $this->getBusinessName();
        $logo_url = $this->getLogoUrl();
        
        // Log para debugging
        Log::info('Sales Index - Data loaded', [
            'sales_count' => $sales->count(),
            'customers_count' => $customers->count(),
            'products_count' => $products->count()
        ]);
        
        return view('sales.index', compact('sales', 'customers', 'products', 'nombre_negocio', 'logo_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ordenar clientes: "Público General" primero, luego los demás alfabéticamente
        $customers = Customer::where('user_id', auth()->id())
            ->orderByRaw("CASE WHEN name = 'Público General' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $products = Product::where('user_id', auth()->id())->orderBy('name')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar límites del plan (conteo mensual)
        if (!PlanHelper::canCreate('sale')) {
            $limits = PlanHelper::getLimits();
            $current = PlanHelper::getCount('sale');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'limit_reached' => true,
                    'type' => 'sale',
                    'limit' => $limits['sales_per_month'],
                    'current' => $current,
                    'message' => "Has alcanzado el límite de {$limits['sales_per_month']} ventas por mes de tu plan."
                ], 403);
            }

            return redirect()->route('sales.create')->with('limit_reached', [
                'type' => 'sale',
                'limit' => $limits['sales_per_month'],
                'current' => $current
            ])->withInput();
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string',
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:efectivo,tarjeta_credito,tarjeta_debito,transferencia,otros',
            'amount_received' => 'nullable|numeric|min:0',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Obtener caja abierta del usuario
            $cashRegister = CashRegister::getOpenCashRegister(auth()->id());

            // Si no se proporciona customer_id, usar cliente genérico
            $customerId = $validated['customer_id'] ?? $this->getOrCreateDefaultCustomer();

            // Calcular el monto total
            $totalAmount = 0;
            foreach ($validated['sale_items'] as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            // Calcular cambio si es efectivo
            $changeReturned = null;
            if ($validated['payment_method'] === 'efectivo' && isset($validated['amount_received'])) {
                $amountReceived = floatval($validated['amount_received']);
                if ($amountReceived >= $totalAmount) {
                    $changeReturned = $amountReceived - $totalAmount;
                }
            }

            // Crear la venta
            $sale = Sale::create([
                'customer_id' => $customerId,
                'sale_date' => $validated['sale_date'],
                'amount' => $totalAmount,
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
                'cash_register_id' => $cashRegister ? $cashRegister->id : null,
                'payment_method' => $validated['payment_method'],
                'amount_received' => $validated['amount_received'] ?? null,
                'change_returned' => $changeReturned,
                'payment_reference' => $validated['payment_reference'] ?? null,
            ]);

            // Crear los items de la venta
            foreach ($validated['sale_items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Actualizar el stock del producto (verificar que pertenece al usuario)
                $product = Product::where('id', $item['product_id'])
                                ->where('user_id', auth()->id())
                                ->first();
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            // Actualizar totales de la caja si está abierta
            if ($cashRegister) {
                $cashRegister->updateSalesTotals();
            }

            DB::commit();

            Log::info('Sale created successfully', ['sale_id' => $sale->id, 'cash_register_id' => $cashRegister ? $cashRegister->id : null]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta creada exitosamente',
                    'sale' => $sale
                ]);
            }

            return redirect()->route('sales.index')
                ->with('success', 'Venta creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating sale', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al crear la venta'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta venta.');
        }

        $sale->load(['customer', 'saleItems.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Get sale data for editing (AJAX)
     */
    public function getEditData(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar esta venta.'
            ], 403);
        }

        $sale->load(['customer', 'saleItems.product']);
        
        return response()->json([
            'success' => true,
            'sale' => [
                'id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'sale_date' => Carbon::parse($sale->sale_date)->format('Y-m-d'),
                'notes' => $sale->notes,
                'amount' => $sale->amount,
                'sale_items' => $sale->saleItems->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'product_name' => $item->product->name ?? 'Producto desconocido'
                    ];
                })
            ]
        ]);
    }

    /**
     * Get sale data for viewing (AJAX)
     */
    public function getViewData(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver esta venta.'
            ], 403);
        }

        $sale->load(['customer', 'saleItems.product']);
        
        return response()->json([
            'success' => true,
            'sale' => [
                'id' => $sale->id,
                'customer_name' => $sale->customer->name ?? 'Cliente desconocido',
                'sale_date' => $sale->sale_date,
                'notes' => $sale->notes,
                'amount' => $sale->amount,
                'sale_items' => $sale->saleItems->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'Producto desconocido',
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                })
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta venta.');
        }

        $customers = Customer::where('user_id', auth()->id())->orderBy('name')->get();
        $products = Product::where('user_id', auth()->id())->orderBy('name')->get();
        $sale->load(['customer', 'saleItems.product']);

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para actualizar esta venta.'
                ], 403);
            }
            abort(403, 'No tienes permiso para actualizar esta venta.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string',
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Restaurar el stock de los items anteriores (verificar que pertenece al usuario)
            foreach ($sale->saleItems as $oldItem) {
                $product = Product::where('id', $oldItem->product_id)
                                ->where('user_id', auth()->id())
                                ->first();
                if ($product) {
                    $product->stock += $oldItem->quantity;
                    $product->save();
                }
            }

            // Eliminar items antiguos
            $sale->saleItems()->delete();

            // Calcular el nuevo monto total
            $totalAmount = 0;
            foreach ($validated['sale_items'] as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            // Actualizar la venta
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'amount' => $totalAmount,
                'notes' => $validated['notes'],
            ]);

            // Crear los nuevos items
            foreach ($validated['sale_items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Actualizar el stock del producto (verificar que pertenece al usuario)
                $product = Product::where('id', $item['product_id'])
                                ->where('user_id', auth()->id())
                                ->first();
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            Log::info('Sale updated successfully', ['sale_id' => $sale->id]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta actualizada exitosamente',
                    'sale' => $sale
                ]);
            }

            return redirect()->route('sales.index')
                ->with('success', 'Venta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating sale', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al actualizar la venta'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar esta venta.'
                ], 403);
            }
            abort(403, 'No tienes permiso para eliminar esta venta.');
        }

        try {
            DB::beginTransaction();

            // Restaurar el stock de los productos (verificar que pertenecen al usuario)
            foreach ($sale->saleItems as $item) {
                $product = Product::where('id', $item->product_id)
                                ->where('user_id', auth()->id())
                                ->first();
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            // Eliminar la venta (los items se eliminan por cascada si está configurado)
            $sale->delete();

            DB::commit();
            
            Log::info('Sale deleted successfully', ['sale_id' => $sale->id]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta eliminada exitosamente'
                ]);
            }

            return redirect()->route('sales.index')
                ->with('success', 'Venta eliminada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sale', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al eliminar la venta']);
        }
    }

    /**
     * Generate AI note for sale
     */
    public function generateNote(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'products_list' => 'required|string',
            'total_amount' => 'required|string',
        ]);

        try {
            // Nota generada automáticamente
            $note = "Venta realizada a {$validated['customer_name']}.\n\n";
            $note .= "Productos: {$validated['products_list']}\n\n";
            $note .= "Total: {$validated['total_amount']}\n\n";
            $note .= "Gracias por su compra. Esperamos verle pronto.";

            return response()->json([
                'success' => true,
                'note' => $note
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la nota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show ticket view
     */
    public function ticket(Sale $sale)
    {
        // Verificar que la venta pertenece al usuario autenticado
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        $sale->load(['customer', 'saleItems.product']);

        // Obtener configuración del negocio
        $nombre_negocio = $this->getBusinessName();
        $logo_url = $this->getLogoUrl();
        $telefono = $this->getPhone();
        $ubicacion = $this->getLocation();

        return view('sales.ticket', compact('sale', 'nombre_negocio', 'logo_url', 'telefono', 'ubicacion'));
    }

    /**
     * Obtener el nombre del negocio
     */
    private function getBusinessName()
    {
        $configPath = storage_path('app/settings/business_name.txt');
        
        if (File::exists($configPath)) {
            return File::get($configPath);
        }
        
        return config('app.name', 'Mi Negocio');
    }

    /**
     * Obtener la URL del logo
     */
    private function getLogoUrl()
    {
        if (File::exists(public_path('images/logo.png'))) {
            return asset('images/logo.png');
        }

        $formats = ['jpg', 'jpeg', 'gif', 'svg'];
        foreach ($formats as $format) {
            if (File::exists(public_path("images/logo.{$format}"))) {
                return asset("images/logo.{$format}");
            }
        }

        return asset('images/default_logo.png');
    }

    /**
     * Obtener el teléfono del negocio
     */
    private function getPhone()
    {
        $configPath = storage_path('app/settings/business_phone.txt');

        if (File::exists($configPath)) {
            return trim(File::get($configPath));
        }

        return '';
    }

    /**
     * Obtener la ubicación del negocio
     */
    private function getLocation()
    {
        $configPath = storage_path('app/settings/business_location.txt');

        if (File::exists($configPath)) {
            return trim(File::get($configPath));
        }

        return '';
    }

    /**
     * Obtener o crear cliente genérico "Público General"
     */
    private function getOrCreateDefaultCustomer()
    {
        $defaultCustomer = Customer::where('user_id', auth()->id())
                                    ->where('name', 'Público General')
                                    ->first();

        if (!$defaultCustomer) {
            $defaultCustomer = Customer::create([
                'name' => 'Público General',
                'email' => 'publico@general.com',
                'phone' => 'N/A',
                'address' => 'N/A',
                'user_id' => auth()->id(),
            ]);
        }

        return $defaultCustomer->id;
    }
}