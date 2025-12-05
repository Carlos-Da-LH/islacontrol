<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\PlanHelper;

class ProductController extends Controller
{
    /**
     * Muestra una lista de productos y el formulario de creación.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene todos los productos del usuario autenticado con su categoría relacionada
        // CORREGIDO: Removida la paginación que causaba problemas
        // Los productos más recientes aparecen primero
        $products = Product::where('user_id', auth()->id())
                         ->with('category')
                         ->orderBy('id', 'desc')
                         ->get();

        // Obtiene todas las categorías del usuario autenticado para la lista desplegable del formulario.
        $categories = Category::where('user_id', auth()->id())->get();

        // Retorna la vista de Blade y le pasa los datos de productos y categorías.
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtiene todas las categorías del usuario autenticado para el formulario de creación.
        $categories = Category::where('user_id', auth()->id())->get();
        // Retorna la vista del formulario para crear un producto, pasando las categorías.
        return view('products.create', compact('categories'));
    }

    /**
     * Almacena un nuevo producto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Verificar límites del plan
        if (!PlanHelper::canCreate('product')) {
            $limits = PlanHelper::getLimits();
            $current = PlanHelper::getCount('product');

            // Si es petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'limit_reached' => true,
                    'type' => 'product',
                    'limit' => $limits['products'],
                    'current' => $current,
                    'message' => "Has alcanzado el límite de {$limits['products']} productos de tu plan."
                ], 403);
            }

            return redirect()->route('products.index')->with('limit_reached', [
                'type' => 'product',
                'limit' => $limits['products'],
                'current' => $current
            ]);
        }

        // Valida los datos del formulario.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:255|unique:products,codigo_barras',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validatedData['user_id'] = auth()->id();
        // Crea el nuevo producto en la base de datos.
        Product::create($validatedData);

        // Siempre redirigir al index de productos
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente!');
    }

    /**
     * Muestra un producto específico (no se usa en este caso).
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Este método no es necesario para la interfaz actual,
        // pero se mantiene para la estructura de recurso.
        return view('products.show', compact('product'));
    }

    /**
     * Muestra el formulario para editar un producto.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // Verificar que el producto pertenece al usuario autenticado
        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este producto.');
        }

        // Obtiene todas las categorías del usuario autenticado para la lista desplegable de edición.
        $categories = Category::where('user_id', auth()->id())->get();

        // Retorna la vista de edición con el producto y las categorías.
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Actualiza un producto existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Verificar que el producto pertenece al usuario autenticado
        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este producto.');
        }

        // Valida los datos de la solicitud.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:255|unique:products,codigo_barras,' . $product->id,
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Actualiza el producto en la base de datos.
        $product->update($validatedData);

        // Siempre redirigir al index de productos
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente!');
    }

    /**
     * Elimina un producto.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Verificar que el producto pertenece al usuario autenticado
        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este producto.');
        }

        // Elimina el producto de la base de datos.
        $product->delete();

        // Siempre redirigir al index de productos
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente!');
    }
}