<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\DB;
use App\Helpers\PlanHelper;

class DashboardApiController extends Controller
{
    public function getSales()
    {
        try {
            // JOIN con customers para obtener el nombre del cliente (filtrar por usuario autenticado)
            $sales = DB::table('sales')
                ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
                ->select(
                    'sales.id',
                    'sales.amount as total',           // Renombrar amount a total
                    'sales.amount as monto',           // También como monto
                    'sales.sale_date as fecha',        // Renombrar sale_date a fecha
                    'sales.sale_date',                 // Mantener original
                    'sales.created_at',
                    'customers.name as cliente',       // Nombre del cliente
                    'customers.name as customer'       // También como customer
                )
                ->where('sales.user_id', auth()->id())
                ->orderBy('sales.sale_date', 'desc')
                ->get();

            return response()->json($sales);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProducts()
    {
        try {
            $products = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->select(
                    'products.*',
                    'categories.name as categoria',
                    'categories.name as category'
                )
                ->where('products.user_id', auth()->id())
                ->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCategories()
    {
        try {
            $categories = DB::table('categories')
                        ->where('user_id', auth()->id())
                        ->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCustomers()
    {
        try {
            $customers = DB::table('customers')
                       ->where('user_id', auth()->id())
                       ->get();
            return response()->json($customers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSettings()
    {
        try {
            $businessName = SettingController::getBusinessNameStatic();
            $logoUrl = SettingController::getLogoUrlStatic();
            $phone = SettingController::getPhoneStatic();
            $location = SettingController::getLocationStatic();

            return response()->json([
                'nombre_negocio' => $businessName,
                'logo_url' => $logoUrl,
                'telefono' => $phone,
                'ubicacion' => $location
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTopProducts()
    {
        try {
            // Obtener sale_items del usuario autenticado
            $saleItems = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->select(
                    'sale_items.product_id',
                    'sale_items.quantity',
                    'sale_items.price'
                )
                ->where('sales.user_id', auth()->id())
                ->get();

            return response()->json($saleItems);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSubscriptionInfo()
    {
        try {
            $plan = PlanHelper::getCurrentPlan();
            $limits = PlanHelper::getLimits();
            $hasActiveSubscription = PlanHelper::hasActiveSubscription();
            $planName = $plan['name'] ?? 'Plan Gratuito';

            return response()->json([
                'hasActiveSubscription' => $hasActiveSubscription,
                'planName' => $planName,
                'planLimits' => $limits,
                'currentUsage' => [
                    'products' => PlanHelper::getCount('product'),
                    'customers' => PlanHelper::getCount('customer'),
                    'sales' => PlanHelper::getCount('sale'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}