<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function getSales()
    {
        try {
            // JOIN con customers para obtener el nombre del cliente
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
                ->get();
            
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCategories()
    {
        try {
            $categories = DB::table('categories')->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCustomers()
    {
        try {
            $customers = DB::table('customers')->get();
            return response()->json($customers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}