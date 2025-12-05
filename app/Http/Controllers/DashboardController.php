<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = Customer::where('user_id', auth()->id())->get();
        $categories = Category::where('user_id', auth()->id())->get();
        $products = Product::where('user_id', auth()->id())->with('category')->get();

        return view('dashboard', compact('customers', 'categories', 'products'));
    }

    /**
     * Verificar si el usuario tiene business_type configurado
     */
    public function checkBusinessType()
    {
        $user = auth()->user();

        return response()->json([
            'has_business_type' => !empty($user->business_type),
            'business_type' => $user->business_type,
            'business_name' => $user->business_name,
        ]);
    }

    /**
     * Guardar el tipo de negocio del usuario
     */
    public function saveBusinessType(Request $request)
    {
        $validated = $request->validate([
            'business_type' => 'required|string|max:50',
            'business_name' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $user->business_type = $validated['business_type'];
        $user->business_name = $validated['business_name'] ?? null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de negocio guardado exitosamente.',
        ]);
    }
}