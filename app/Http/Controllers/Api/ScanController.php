<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // o tu modelo correcto

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ], 200);
    }
}
