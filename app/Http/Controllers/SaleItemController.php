<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleItem;

class SaleItemController extends Controller
{
    /**
     * Muestra una lista de items de venta.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saleItems = SaleItem::with('sale', 'product')->get();
        return response()->json($saleItems);
    }

    /**
     * Almacena un nuevo item de venta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $saleItem = SaleItem::create($validatedData);
        return response()->json($saleItem, 201);
    }

    /**
     * Muestra un item de venta específico.
     *
     * @param  \App\Models\SaleItem  $saleItem
     * @return \Illuminate\Http\Response
     */
    public function show(SaleItem $saleItem)
    {
        $saleItem->load('sale', 'product');
        return response()->json($saleItem);
    }

    /**
     * Actualiza un item de venta existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleItem  $saleItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleItem $saleItem)
    {
        $validatedData = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $saleItem->update($validatedData);
        return response()->json($saleItem);
    }

    /**
     * Elimina un item de venta.
     *
     * @param  \App\Models\SaleItem  $saleItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleItem $saleItem)
    {
        $saleItem->delete();
        return response()->json(null, 204);
    }
}
